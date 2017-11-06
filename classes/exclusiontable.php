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
 * this file contains the status table class to display configured exclusions.
 *
 * File         exclusiontable.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_usersuspension;

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir . '/tablelib.php');

/**
 * tool_usersuspension\exclusiontable
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class exclusiontable extends \flexible_table {

    /**
     * Raw data array
     * @var array
     */
    protected $rawdata;

    /**
     * item deletion string
     * @var string
     */
    protected $strdelete;

    /**
     * table type string
     * @var string
     */
    protected $tabletype = 'all';

    /**
     * Create a new instance of the exclusiontable
     */
    public function __construct() {
        global $USER;
        parent::__construct(__CLASS__. '-' . $USER->id . '-' . $this->tabletype);
        $this->rawdata = array();
        $this->strdelete = get_string('action:delete-exclusion', 'tool_usersuspension');
        $this->no_sorting('action');
    }

    /**
     * Renders the table
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    public function render($pagesize, $useinitialsbar = true) {
        $this->define_columns(array('type', 'name', 'timecreated', 'action'));
        $this->define_headers(array(
            get_string('thead:type', 'tool_usersuspension'),
            get_string('thead:name', 'tool_usersuspension'),
            get_string('thead:timecreated', 'tool_usersuspension'),
            get_string('thead:action', 'tool_usersuspension'))
        );

        $this->setup();

        switch ($this->tabletype)
        {
            case 'user':
                $this->load_type_users($pagesize);
                break;
            case 'cohort':
                $this->load_type_cohorts($pagesize);
                break;
            default:
                $this->load_all($pagesize);
                break;
        }

        $this->build_table();
        $this->finish_output();
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
     *
     * Get any extra classes names to add to this row in the HTML.
     * @param \stdClass $row array the data for this row.
     * @return string added to the class="" attribute of the tr.
     */
    public function get_row_class($row) {
        return '';
    }

    /**
     * Return the SQL and parameters for the 'user' type part of the exclusion table
     * @return array
     */
    protected function get_type_users_sql() {
        global $DB;
        $fields = 'e.id,e.type,u.id as refid,' . $DB->sql_fullname('u.firstname', 'u.lastname') .
                ' AS name,e.timecreated,null AS action';
        $from = '{tool_usersuspension_excl} e JOIN {user} u ON e.refid=u.id';
        $where = 'type = ?';
        $sql = "SELECT $fields FROM $from WHERE $where";
        return array($sql, array('user'));
    }

    /**
     * Load the data for the 'user' type part of the exclusion table
     * @param int $pagesize
     */
    protected function load_type_users($pagesize) {
        global $DB;
        // Table size.
        $count = $DB->count_records('tool_usersuspension_excl', array('type' => 'user'));
        $this->pagesize($pagesize, $count);

        list($sql, $params) = $this->get_type_users_sql();
        $sort = $this->get_sql_sort();
        if ($sort) {
            $sql .= " ORDER BY $sort";
        }
        $records = $DB->get_records_sql($sql, $params,
                $this->get_page_start(), $this->get_page_size());
        $this->rawdata = array_merge($this->rawdata, $records);
    }

    /**
     * Return the SQL and parameters for the 'cohort' type part of the exclusion table
     * @return array
     */
    protected function get_type_cohorts_sql() {
        $fields = 'e.id,e.type,c.id as refid,c.name,e.timecreated,null AS action';
        $from = '{tool_usersuspension_excl} e JOIN {cohort} c ON e.refid=c.id';
        $where = 'type = ?';
        $sql = "SELECT $fields FROM $from WHERE $where";
        return array($sql, array('cohort'));
    }

    /**
     * Load the data for the 'cohort' type part of the exclusion table
     * @param int $pagesize
     */
    protected function load_type_cohorts($pagesize) {
        global $DB;
        // Table size.
        $count = $DB->count_records('tool_usersuspension_excl', array('type' => 'user'));
        $this->pagesize($pagesize, $count);

        list($sql, $params) = $this->get_type_cohorts_sql();
        $sort = $this->get_sql_sort();
        if ($sort) {
            $sql .= " ORDER BY $sort";
        }
        $records = $DB->get_records_sql($sql . $sort, $params,
                $this->get_page_start(), $this->get_page_size());
        $this->rawdata = array_merge($this->rawdata, $records);
    }

    /**
     * Load all data for the exclusion table
     * @param int $pagesize
     */
    protected function load_all($pagesize) {
        global $DB;
        // Table size.
        $count = $DB->count_records('tool_usersuspension_excl');
        $this->pagesize($pagesize, $count);

        list($sql, $params) = $this->get_type_users_sql();
        list($sql2, $params2) = $this->get_type_cohorts_sql();

        $params = array_merge($params, $params2);
        $sql .= ' UNION ' . $sql2;
        $sort = $this->get_sql_sort();
        if ($sort) {
            $sql .= " ORDER BY $sort";
        }
        $records = $DB->get_records_sql($sql, $params,
                $this->get_page_start(), $this->get_page_size());
        $this->rawdata = $records;
    }

    /**
     * Render visual representation of the 'username' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_name($row) {
        global $CFG;
        if ($row->type === 'user') {
            $link = new \moodle_url($CFG->wwwroot . '/user/profile.php', array('id' => $row->id));
            return '<a href="' . $link->out() . '">' . $row->name . '</a>';
        } else {
            $link = new \moodle_url($CFG->wwwroot . '/cohort/index.php');
            return '<a href="' . $link->out() . '">' . $row->name . '</a>';
        }
    }

    /**
     * Render visual representation of the 'timemodified' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_timecreated($row) {
        return userdate($row->timecreated);
    }

    /**
     * Render visual representation of the 'action' column for use in the table
     *
     * @param \stdClass $row
     * @return string actions
     */
    public function col_action($row) {
        $actions = array();
        $actions[] = $this->get_action($row, 'delete', true);
        return implode('', $actions);
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
     * @param bool $confirm whether or not to render a javascript confirm box
     * @return string link representing the action with an image
     */
    protected function get_action($row, $action, $confirm = false) {
        $actionstr = 'str' . $action;
        $onclick = '';
        if ($confirm) {
            $onclick = ' onclick="return confirm(\'' .
                    get_string('action:confirm-'.$action.'-exclusion', 'tool_usersuspension') .
                    '\');"';
        }
        return '<a ' . $onclick . 'href="' . new \moodle_url($this->baseurl,
                array('action' => $action, 'id' => $row->id, 'sesskey' => sesskey())) .
                '" alt="' . $this->{$actionstr} .
                '">' . $this->get_action_image($action) . '</a>';
    }

}