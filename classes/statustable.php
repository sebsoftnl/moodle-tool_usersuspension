<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * this file contains the status table class to display certain users statusses.
 *
 * File         util.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_usersuspension;

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir . '/tablelib.php');

/**
 * tool_usersuspension\util
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class statustable extends \table_sql {

    /**
     * table type identifier for suspended users
     */
    const SUSPENDED = 'suspended';
    /**
     * table type identifier for users to suspend
     */
    const TOSUSPEND = 'tosuspend';
    /**
     * table type identifier for generic status
     */
    const STATUS = 'status';
    /**
     * table type identifier for users to delete
     */
    const DELETE = 'delete';

    /**
     * Localised 'suspend user' string
     *
     * @var string
     */
    protected $strsuspend;

    /**
     * Localised 'unsuspend user' string
     *
     * @var string
     */
    protected $strunsuspend;

    /**
     * Localised 'exclude user' string
     *
     * @var string
     */
    protected $strexclude;

    /**
     * internal display type
     *
     * @var string
     */
    protected $displaytype;

    /**
     * current user has capability to update user?
     *
     * @var bool
     */
    protected $capuserupdate;

    /**
     * Create a new instance of the statustable
     *
     * @param string $type table render type
     */
    public function __construct($type = 'status') {
        global $USER;
        parent::__construct(__CLASS__. '-' . $USER->id . '-' . $type);
        $this->displaytype = $type;
        $this->strsuspend = get_string('suspenduser', 'admin');
        $this->strunsuspend = get_string('unsuspenduser', 'admin');
        $this->strexclude = get_string('excludeuser', 'tool_usersuspension');
        $this->capuserupdate = has_capability('moodle/user:update', \context_system::instance());
        $this->no_sorting('action');
    }

    /**
     * Return a list of applicable viewtypes for this table
     *
     * @return array list of view types
     */
    static public function get_viewtypes() {
        return array(
            self::STATUS,
            self::SUSPENDED,
            self::TOSUSPEND,
            self::DELETE,
        );
    }

    /**
     *
     * Set the sql to query the db.
     * This method is disabled for this class, since we use internal queries
     *
     * @param string $fields
     * @param string $from
     * @param string $where
     * @param array $params
     * @throws exception
     */
    public function set_sql($fields, $from, $where, array $params = null) {
        // We'll disable this method.
        throw new exception('err:statustable:set_sql');
    }

    /**
     * Display the general suspension status table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    public function render($pagesize, $useinitialsbar = true) {
        switch ($this->displaytype) {
            case self::STATUS:
                $this->render_statusses($pagesize, $useinitialsbar);
                break;
            case self::DELETE:
                $this->render_to_delete($pagesize, $useinitialsbar);
                break;
            case self::SUSPENDED:
                $this->render_suspended($pagesize, $useinitialsbar);
                break;
            case self::TOSUSPEND:
                $this->render_to_suspend($pagesize, $useinitialsbar);
                break;
        }
    }

    /**
     * Display the general suspension status table for users that haven't
     * been excluded
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    protected function render_statusses($pagesize, $useinitialsbar = true) {
        global $DB;
        $this->define_columns(array('username', 'name', 'lastlogin', 'timemodified', 'action'));
        $this->define_headers(array(
            get_string('thead:username', 'tool_usersuspension'),
            get_string('thead:name', 'tool_usersuspension'),
            get_string('thead:lastlogin', 'tool_usersuspension'),
            get_string('thead:timemodified', 'tool_usersuspension'),
            get_string('thead:action', 'tool_usersuspension'))
        );
        $fields = 'u.id,u.username,' . $DB->sql_fullname('u.firstname', 'u.lastname') .
                ' AS name,u.lastlogin,u.timemodified,u.suspended,u.deleted,NULL AS action';
        $where = 'deleted = ?';
        $params = array(0);
        $this->add_exclude_users($where, $params);
        parent::set_sql($fields, '{user} u', $where, $params);
        $this->out($pagesize, $useinitialsbar);
    }

    /**
     * Display the status table for suspended users
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    protected function render_suspended($pagesize, $useinitialsbar = true) {
        global $DB;
        $this->define_columns(array('username', 'name', 'lastlogin', 'timemodified', 'action'));
        $this->define_headers(array('username', 'name', 'lastlogin', 'timemodified', 'action'));
        $fields = 'u.id,u.username,' . $DB->sql_fullname('u.firstname', 'u.lastname') .
                ' AS name,u.lastlogin,u.timemodified,u.suspended,u.deleted,NULL AS action';
        $where = 'suspended = ? AND deleted = ?';
        $params = array(1, 0);
        $this->add_exclude_users($where, $params);
        parent::set_sql($fields, '{user} u', $where, $params);
        $this->out($pagesize, $useinitialsbar);
    }

    /**
     * Display the status table for users that are to be suspended
     * within the timeframe of suspension.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    protected function render_to_suspend($pagesize, $useinitialsbar = true) {
        global $DB;
        $this->define_columns(array('username', 'name', 'timedetect', 'suspendin', 'action'));
        $this->define_headers(array('username', 'name', 'timedetect', 'suspendin', 'action'));

        $suspendinsql = '('.config::get('smartdetect_suspendafter') .
                ' - (UNIX_TIMESTAMP() - GREATEST(u.firstaccess, u.lastaccess, u.timemodified))) AS suspendin,';
        $fields = 'u.id,u.username,' . $DB->sql_fullname('u.firstname', 'u.lastname') .
                ' AS name,u.lastlogin,u.firstaccess,u.lastaccess,u.timemodified,u.suspended,u.deleted,' .
                'GREATEST(u.firstaccess, u.lastaccess, u.timemodified) AS timedetect,'.
                $suspendinsql.
                'NULL as action';

        list($where, $params) = util::get_suspension_query(false);
        list($where2, $params2) = util::get_suspension_query(true);
        parent::set_sql($fields, '{user} u', "({$where}) OR ({$where2})", array_merge($params, $params2));
        $this->out($pagesize, $useinitialsbar);
    }

    /**
     * Display the status table for users that are to be deleted
     * within the timeframe of deletion.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    protected function render_to_delete($pagesize, $useinitialsbar = true) {
        global $DB;
        $this->define_columns(array('username', 'name', 'timedetect', 'deletein', 'action'));
        $this->define_headers(array('username', 'name', 'timedetect', 'deletein', 'action'));

        $deleteinsql = '('.config::get('cleanup_deleteafter') .
                ' - (UNIX_TIMESTAMP() - u.timemodified)) AS deletein,';
        $fields = 'u.id,u.username,' . $DB->sql_fullname('u.firstname', 'u.lastname') .
                ' AS name,u.lastlogin,u.firstaccess,u.lastaccess,u.timemodified,u.suspended,u.deleted,'.
                'GREATEST(u.firstaccess, u.lastaccess, u.timemodified) AS timedetect,'.
                $deleteinsql.
                'NULL as action';

        list($where, $params) = util::get_deletion_query(false);
        list($where2, $params2) = util::get_deletion_query(true);
        parent::set_sql($fields, '{user} u', "({$where}) OR ({$where2})", array_merge($params, $params2));
        $this->out($pagesize, $useinitialsbar);
    }

    /**
     * Take the data returned from the db_query and go through all the rows
     * processing each col using either col_{columnname} method or other_cols
     * method or if other_cols returns NULL then put the data straight into the
     * table.
     */
    public function build_table() {
        if ($this->rawdata) {
            foreach ($this->rawdata as $row) {
                $formattedrow = $this->format_row($row);
                $this->add_data_keyed($formattedrow, $this->get_row_class($row));
            }
        }
    }

    /**
     * Render visual representation of the 'username' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_username($row) {
        global $CFG;
        $link = new \moodle_url($CFG->wwwroot . '/user/profile.php', array('id' => $row->id));
        return '<a href="' . $link->out() . '">' . $row->username . '</a>';
    }

    /**
     * Render visual representation of the 'lastlogin' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_lastlogin($row) {
        return userdate($row->lastlogin);
    }

    /**
     * Render visual representation of the 'timemodified' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_timemodified($row) {
        return userdate($row->timemodified);
    }

    /**
     * Render visual representation of the 'action' column for use in the table
     *
     * @param \stdClass $row
     * @return string actions
     */
    public function col_suspendin($row) {
        return util::format_timespan($row->suspendin);
    }

    /**
     * Render visual representation of the 'action' column for use in the table
     *
     * @param \stdClass $row
     * @return string actions
     */
    public function col_deletein($row) {
        $diff = time() - $row->timemodified;
        $time = config::get('cleanup_deleteafter') - $diff;
        return util::format_timespan($time). '<br/>'.util::format_timespan($row->deletein);
    }

    /**
     * Render visual representation of the 'action' column for use in the table
     *
     * @param \stdClass $row
     * @return string actions
     */
    public function col_timedetect($row) {
        return userdate($row->timedetect);
    }

    /**
     * Render visual representation of the 'action' column for use in the table
     *
     * @param \stdClass $row
     * @return string actions
     */
    public function col_action($row) {
        $actions = array();
        if ($this->capuserupdate) {
            if ($row->suspended == 1 && ($this->displaytype == self::DELETE || $this->displaytype == self::SUSPENDED)) {
                $actions[] = $this->get_action($row, 'unsuspend');
            }
            if ($row->suspended == 0 && ($this->displaytype == self::DELETE || $this->displaytype == self::TOSUSPEND)) {
                $actions[] = $this->get_action($row, 'suspend');
            }
        }
        if ($row->deleted != 1) {
            $actions[] = $this->get_action($row, 'exclude');
        }
        return implode(' ', $actions);
    }

    /**
     * Return the image tag representing an action image
     *
     * @param string $action
     * @return string HTML image tag
     */
    protected function get_action_image($action) {
        global $OUTPUT;
        $actionstr = 'str' . $action;
        return '<img src="' . $OUTPUT->image_url($action, 'tool_usersuspension') .
                '" title="' . $this->{$actionstr} . '"/>';
    }

    /**
     * Return a string containing the link to an action
     *
     * @param \stdClass $row
     * @param string $action
     * @return string link representing the action with an image
     */
    protected function get_action($row, $action) {
        $actionstr = 'str' . $action;
        return '<a href="' . new \moodle_url($this->baseurl,
                array('action' => $action, 'id' => $row->id,
                    'sesskey' => sesskey(), 'type' => $this->displaytype)) .
                '" alt="' . $this->{$actionstr} .
                '">' . $this->get_action_image($action) . '</a>';
    }

    /**
     * Add user exclusion to the query.
     * This will, at the very least, exclude the site administrators and the guest account
     *
     * @param string $where
     * @param array $params
     */
    protected function add_exclude_users(&$where, &$params) {
        util::append_user_exclusion($where, $params, 'u.');
    }

}