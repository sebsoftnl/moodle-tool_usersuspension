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
 * file contains the general utility class for this tool
 *
 * File         util.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_usersuspension;

defined('MOODLE_INTERNAL') || die;
use tool_usersuspension\statustable;

/**
 * tool_usersuspension\util
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class util {

    /**
     * __construct() DO NOT SHOW / ALLOW TO BE CALLED: Open source version
     */
    private function __construct() {
        // Open source version.
    }

    /**
     * Return a more humanly readable timespan string from a timespan
     *
     * @param float $size
     * @return string
     */
    final public static function format_timespan($size) {
        $neg = ($size < 0);
        $size = (float) abs($size);
        if ($size > 7 * 86400) {
            return ($neg ? '-' : '') . sprintf('%d %s', floor($size / (7 * 86400)), get_string('weeks'));
        } else if ($size > 86400) {
            return ($neg ? '-' : '') . sprintf('%d %s', floor($size / 86400), get_string('days'));
        } else if ($size > 3600) {
            return ($neg ? '-' : '') . sprintf('%d %s', floor($size / 3600), get_string('hours'));
        } else if ($size > 60) {
            return ($neg ? '-' : '') . sprintf('%d %s', floor($size / 60), get_string('minutes'));
        } else {
            return ($neg ? '-' : '') . sprintf('%d %s', $size, get_string('seconds'));
        }
    }

    /**
     * Count the number of activly monitored users.
     * Do note this method will not count users configured to be excluded.
     *
     * @return int number of actively monitored users
     */
    public static function count_monitored_users() {
        global $DB;
        $where = 'deleted = ?';
        $params = array(0);
        static::append_user_exclusion($where, $params, 'u.');
        return $DB->count_records_sql('SELECT COUNT(*) FROM {user} u WHERE ' . $where, $params);
    }

    /**
     * Count the number of suspended users.
     * Do note this method will not count users configured to be excluded.
     *
     * @return int number of suspended users
     */
    public static function count_suspended_users() {
        global $DB;
        $where = 'suspended = ? AND deleted = ?';
        $params = array(1, 0);
        static::append_user_exclusion($where, $params, 'u.');
        return $DB->count_records_sql('SELECT COUNT(*) FROM {user} u WHERE ' . $where, $params);
    }

    /**
     * Count the number of users that are to be suspended.
     * Do note this method will not count users configured to be excluded.
     *
     * @return int number of suspendable users
     */
    public static function count_users_to_suspend() {
        global $DB;
        list($where, $params) = static::get_suspension_query(false);
        list($where2, $params2) = static::get_suspension_query(true);
        $sql = 'SELECT COUNT(*) FROM {user} u WHERE ' . "({$where}) OR ({$where2})";
        return $DB->count_records_sql($sql, array_merge($params, $params2));

    }

    /**
     * Count the number of users that are to be deleted.
     * Do note this method will not count users configured to be excluded.
     *
     * @return int number of deleteable users
     */
    public static function count_users_to_delete() {
        global $DB;
        list($where, $params) = static::get_deletion_query(false);
        list($where2, $params2) = static::get_deletion_query(true);
        $sql = 'SELECT COUNT(*) FROM {user} u WHERE ' . "({$where}) OR ({$where2})";
        return $DB->count_records_sql($sql, array_merge($params, $params2));
    }

    /**
     * Marks inactive users as suspended according to configuration settings.
     *
     * @return boolean
     */
    static final public function mark_users_to_suspend() {
        global $DB;
        if (!(bool)config::get('enabled')) {
            return false;
        }
        if (!(bool)config::get('enablesmartdetect')) {
            return false;
        }
        $lastrun = static::get_lastrun_config('smartdetect', 0, true);
        $deltatime = time() - $lastrun;
        if ($deltatime < config::get('smartdetect_interval')) {
            return false;
        }
        list($where, $params) = static::get_suspension_query(true);
        $sql = "SELECT * FROM {user} u WHERE $where";
        $users = $DB->get_records_sql($sql, $params);
        foreach ($users as $user) {
            // Suspend user here.
            static::do_suspend_user($user);
        }
    }

    /**
     * Deletes suspended users according to configuration settings.
     *
     * @return boolean
     */
    static final public function delete_suspended_users() {
        global $DB;
        if (!(bool)config::get('enabled')) {
            return false;
        }
        if (!(bool)config::get('enablecleanup')) {
            return false;
        }
        $lastrun = static::get_lastrun_config('cleanup', 0, true);
        $deltatime = time() - $lastrun;
        if ($deltatime < config::get('cleanup_interval')) {
            return false;
        }
        list($where, $params) = static::get_deletion_query(true);
        $sql = "SELECT * FROM {user} u WHERE $where";
        $users = $DB->get_records_sql($sql, $params);
        foreach ($users as $user) {
            // Delete user here.
            static::do_delete_user($user);
        }
    }

    /**
     * Gets last run configuration for a specific type
     *
     * @param string $type
     * @param mixed $default default value to return if this config is not set
     * @param bool $autosetnew if true, automatically insert current time for the last run configuration
     */
    static final protected function get_lastrun_config($type, $default = null, $autosetnew = true) {
        $value = get_config('tool_usersuspension', $type . '_lastrun');
        if ($autosetnew) {
            static::set_lastrun_config($type);
        }
        return (($value === false) ? $default : $value);
    }

    /**
     * Sets last run configuration for a specific type
     *
     * @param string $type
     */
    static final protected function set_lastrun_config($type) {
        set_config(time(), $type . '_lastrun', 'tool_usersuspension');
    }

    /**
     * Performs the actual user suspension by updating the users table
     *
     * @param \stdClass $user
     * @param bool $automated true if a result of automated suspension, false if suspending
     *              is a result of a manual action
     */
    static public final function do_suspend_user($user, $automated = true) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');
        // Piece of code taken from /admin/user.php so we dance just like moodle does.
        if (!is_siteadmin($user) and $USER->id != $user->id and $user->suspended != 1) {
            $user->suspended = 1;
            // Force logout.
            \core\session\manager::kill_user_sessions($user->id);
            user_update_user($user, false, true);
            // Process email if applicable.
            $user->suspended = 0; // This is to prevent mail from not sending.
            $emailsent = (static::process_user_suspended_email($user, $automated) === true);
            // Create status record.
            static::process_status_record($user, 'suspended', $emailsent);
            // Trigger event.
            $event = event\user_suspended::create(
                    array(
                        'objectid' => $user->id,
                        'relateduserid' => $user->id,
                        'context' => \context_user::instance($user->id),
                        'other' => array()
                        )
                    );
            $event->trigger();
            return true;
        }
        return false;
    }

    /**
     * Performs the actual user unsuspension by updating the users table
     *
     * @param \stdClass $user
     */
    static public final function do_unsuspend_user($user) {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/user/lib.php');
        // Piece of code taken from /admin/user.php so we dance just like moodle does.
        if ($user = $DB->get_record('user', array('id' => $user->id,
                'mnethostid' => $CFG->mnet_localhost_id, 'deleted' => 0))) {
            if ($user->suspended != 0) {
                $user->suspended = 0;
                user_update_user($user, false, true);
                // Process email id applicable.
                $emailsent = (static::process_user_unsuspended_email($user) === true);
                // Trigger event.
                $event = event\user_unsuspended::create(
                        array(
                            'objectid' => $user->id,
                            'relateduserid' => $user->id,
                            'context' => \context_user::instance($user->id),
                            'other' => array()
                            )
                        );
                $event->trigger();
                // Create status record.
                static::process_status_record($user, 'unsuspended', $emailsent);
                return true;
            }
        }
        return false;
    }

    /**
     * Performs the actual user deletion
     *
     * @param \stdClass $user
     * @return bool true if successful, false otherwise
     */
    static public final function do_delete_user($user) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');
        // Piece of code taken from /admin/user.php so we dance just like moodle does.
        if (!is_siteadmin($user) and $USER->id != $user->id and $user->deleted != 1) {
            // Force logout.
            \core\session\manager::kill_user_sessions($user->id);
            user_delete_user($user);
            // Process email id applicable.
            $user->suspended = 0; // This is to prevent mail from not sending.
            $emailsent = (static::process_user_deleted_email($user) === true);
            // Create status record.
            static::process_status_record($user, 'deleted', $emailsent);
            return true;
        }
        return false;
    }

    /**
     * Process a status record.
     * This will insert a new status record and move all existing status records for the given user to the logs.
     *
     * @param \stdClass $user user record
     * @param string $status status string
     * @param bool $emailsent whether or not the email was sent
     */
    static public final function process_status_record($user, $status, $emailsent) {
        global $DB;
        // Move existing record to log.
        $recordstolog = $DB->get_records('tool_usersuspension_status', array('userid' => $user->id));
        foreach ($recordstolog as $record) {
            unset($record->id);
            $DB->insert_record('tool_usersuspension_log', $record);
        }
        $DB->delete_records('tool_usersuspension_status', array('userid' => $user->id));
        // Insert new record.
        $statusrecord = (object) array(
            'userid' => $user->id,
            'status' => $status,
            'mailsent' => ($emailsent ? 1 : 0),
            'mailedto' => $user->email,
            'timecreated' => time()
        );
        $DB->insert_record('tool_usersuspension_status', $statusrecord);
    }

    /**
     * Add user exclusion to the query.
     * This will, at the very least, exclude the site administrators and the guest account
     *
     * @param string $where
     * @param array $params
     * @param string $useraliasprefix alias prefix for users (e.g. 'u.' to indicate u.id)
     */
    static public function append_user_exclusion(&$where, &$params, $useraliasprefix = '') {
        global $CFG, $DB;
        // Set standard exclusions.
        $excludeids = array(1, $CFG->siteguest); // Guest account.
        $excludeids = array_merge($excludeids, array_keys(get_admins()));
        // Now append configured exclusions.
        $excludeids = array_merge($excludeids, static::get_user_exclusion_list());
        $excludeids = array_unique($excludeids);

        list($notinsql, $uparams) = $DB->get_in_or_equal($excludeids, SQL_PARAMS_QM, 'param', false);
        $where .= ' AND ' . $useraliasprefix . 'id '.$notinsql;
        $params = array_merge($params, $uparams);
    }

    /**
     * Get a list of userids to exclude.
     * This will load all relevant userids from the tool's exclusion table
     *
     * @return array list of user ids
     */
    static public function get_user_exclusion_list() {
        global $DB;
        // First load users.
        $userids = $DB->get_fieldset_select('tool_usersuspension_excl', 'refid',
                'type = ?', array('user'));
        $cohortids = $DB->get_fieldset_select('tool_usersuspension_excl', 'refid',
                'type = ?', array('cohort'));
        foreach ($cohortids as $cohortid) {
            $cohortuserids = $DB->get_fieldset_select('cohort_members', 'userid',
                    'cohortid = ?', array($cohortid));
            $userids = array_merge($userids, $cohortuserids);
        }

        return array_unique($userids);
    }

    /**
     * Return the query to load users applicable for suspension.
     *
     * @param bool $pastsuspensiondate if true, this return the query for users
     *          that are past their date of suspension (i.e. should be suspended).
     *          If false, this returns the query for users that are not past their
     *          date of suspension yet. The latter can be used for statistics on
     *          when users would get suspended.
     * @return array A list containing the constructed where part of the sql and an array of parameters.
     */
    public static function get_suspension_query($pastsuspensiondate = true) {
        global $CFG;
        $detectoperator = $pastsuspensiondate ? '<' : '>';
        $timecheck = time() - (config::get('smartdetect_suspendafter'));
        $where = "u.confirmed = 1 AND u.suspended = 0 AND u.deleted = 0 AND u.mnethostid = ? ";
        $where .= "AND (";
        $where .= "(u.lastaccess = 0 AND u.firstaccess > 0 AND u.firstaccess $detectoperator ?)";
        $where .= " OR (u.lastaccess > 0 AND u.lastaccess $detectoperator ?)";
        $where .= " OR (u.auth = 'manual' AND u.firstaccess = 0 AND u.lastaccess = 0 ";
        $where .= "     AND u.timemodified > 0 AND u.timemodified $detectoperator ?)";
        $where .= ")";
        $params = array($CFG->mnet_localhost_id, $timecheck, $timecheck, $timecheck);
        // Append user exclusion.
        static::append_user_exclusion($where, $params, 'u.');
        return array($where, $params);
    }

    /**
     * Return the query to load users applicable for deletion.
     *
     * @param bool $pastdeletiondate if true, this return the query for users
     *          that are past their date of deletion (i.e. should be deleted).
     *          If false, this returns the query for users that are not past their
     *          date of deletion yet. The latter can be used for statistics on
     *          when users would get deleted.
     * @return array A list containing the constructed where part of the sql and an array of parameters.
     */
    public static function get_deletion_query($pastdeletiondate = true) {
        global $CFG;
        $detectoperator = $pastdeletiondate ? '<' : '>';
        $params = array($CFG->mnet_localhost_id,
            time() - (int)config::get('cleanup_deleteafter'));
        $where = "u.suspended = 1 AND u.confirmed = 1 AND u.deleted = 0 "
                . "AND u.mnethostid = ? AND u.timemodified $detectoperator ?";
        static::append_user_exclusion($where, $params, 'u.');
        return array($where, $params);
    }

    /**
     * Process the view for cohort exclusion.
     * This will display or process the exclusion form for cohort exclusion.
     *
     * @param \moodle_url $url
     */
    public static function view_process_cohort_exclusion($url) {
        global $CFG, $OUTPUT;
        require_once($CFG->dirroot . '/' . $CFG->admin . '/tool/usersuspension/classes/forms/exclude/cohort.php');
        $formurl = clone $url;
        $formurl->param('action', 'add');
        $formurl->param('addtype', 'cohort');
        $mform = new forms\exclude\cohort($formurl);
        if ($mform->is_cancelled()) {
            redirect($url);
        } else if ($data = $mform->get_data()) {
            echo $OUTPUT->header();
            echo '<div id="tool-usersuspension-form-container">';
            $mform->process();
            echo '<br/>';
            echo static::continue_button($url, get_string('button:backtoexclusions', 'tool_usersuspension'));
            echo '</div>';
            echo $OUTPUT->footer();
        } else {
            echo $OUTPUT->header();
            echo '<div id="tool-usersuspension-form-container">';
            echo '<div>';
            static::print_view_tabs($url->params(), 'exclusions');
            echo '</div>';
            echo $mform->display();
            echo '</div>';
            echo $OUTPUT->footer();
        }
    }

    /**
     * Process the view for user exclusion.
     * This will display or process the exclusion form for user exclusion.
     *
     * @param \moodle_url $url
     */
    public static function view_process_user_exclusion($url) {
        global $CFG, $OUTPUT;
        require_once($CFG->dirroot . '/' . $CFG->admin . '/tool/usersuspension/classes/forms/exclude/user.php');
        $formurl = clone $url;
        $formurl->param('action', 'add');
        $formurl->param('addtype', 'user');
        $mform = new forms\exclude\user($formurl);
        if ($mform->is_cancelled()) {
            redirect($url);
        } else if ($data = $mform->get_data()) {
            echo $OUTPUT->header();
            echo '<div id="tool-usersuspension-form-container">';
            $mform->process();
            echo '<br/>';
            echo static::continue_button($url, get_string('button:backtoexclusions', 'tool_usersuspension'));
            echo '</div>';
            echo $OUTPUT->footer();
        } else {
            echo $OUTPUT->header();
            echo '<div id="tool-usersuspension-form-container">';
            echo '<div>';
            static::print_view_tabs($url->params(), 'exclusions');
            echo '</div>';
            echo $mform->display();
            echo '</div>';
            echo $OUTPUT->footer();
        }
    }

    /**
     * Send an e-mail due to a user being suspended
     *
     * @param \stdClass $user
     * @param bool $automated true if a result of automated suspension, false if suspending
     *              is a result of a manual action
     * @return void
     */
    public static function process_user_suspended_email($user, $automated = true) {
        if (!(bool)config::get('send_suspend_email')) {
            return false;
        }
        // Prepare and send email.
        $from = \core_user::get_support_user();
        $a = new \stdClass();
        $a->name = fullname($user);
        $a->timeinactive = static::format_timespan(config::get('smartdetect_suspendafter'));
        $a->contact = $from->email;
        $a->signature = fullname($from);
        $subject = get_string_manager()->get_string('email:user:suspend:subject',
                'tool_usersuspension', $a, $user->lang);
        if ($automated) {
            $messagehtml = get_string_manager()->get_string('email:user:suspend:auto:body',
                    'tool_usersuspension', $a, $user->lang);
        } else {
            $messagehtml = get_string_manager()->get_string('email:user:suspend:manual:body',
                    'tool_usersuspension', $a, $user->lang);
        }
        $messagetext = format_text_email($messagehtml, FORMAT_HTML);
        return email_to_user($user, $from, $subject, $messagetext, $messagehtml);
    }

    /**
     * Send an e-mail due to a user being unsuspended
     *
     * @param \stdClass $user
     * @return void
     */
    public static function process_user_unsuspended_email($user) {
        if (!(bool)config::get('send_suspend_email')) {
            return false;
        }
        // Prepare and send email.
        $from = \core_user::get_support_user();
        $a = new \stdClass();
        $a->name = fullname($user);
        $a->contact = $from->email;
        $a->signature = fullname($from);
        $subject = get_string_manager()->get_string('email:user:unsuspend:subject',
                'tool_usersuspension', $a, $user->lang);
        $messagehtml = get_string_manager()->get_string('email:user:unsuspend:body',
                'tool_usersuspension', $a, $user->lang);
        $messagetext = format_text_email($messagehtml, FORMAT_HTML);
        return email_to_user($user, $from, $subject, $messagetext, $messagehtml);
    }

    /**
     * Send an e-mail due to a user being deleted
     *
     * @param \stdClass $user
     * @return bool true if sent, false if disabled or error
     */
    public static function process_user_deleted_email($user) {
        if (!(bool)config::get('send_delete_email')) {
            return false;
        }
        // Prepare and send email.
        $from = \core_user::get_support_user();
        $a = new \stdClass();
        $a->name = fullname($user);
        $a->timesuspended = static::format_timespan(config::get('cleanup_deleteafter'));
        $a->contact = $from->email;
        $a->signature = fullname($from);
        $subject = get_string_manager()->get_string('email:user:delete:subject',
                'tool_usersuspension', $a, $user->lang);
        $messagehtml = get_string_manager()->get_string('email:user:delete:body',
                'tool_usersuspension', $a, $user->lang);
        $messagetext = format_text_email($messagehtml, FORMAT_HTML);
        return email_to_user($user, $from, $subject, $messagetext, $messagehtml);
    }

    /**
     * Clean history logs (if enabled in global config) older than the configured duration.
     *
     * @return boolean
     */
    static public function clean_logs() {
        global $DB;
        if (!(bool)config::get('enablecleanlogs')) {
            return false;
        }
        $DB->delete_records_select('tool_usersuspension_log', 'timecreated < ?',
                array(time() - (int)config::get('cleanlogsafter')));
        return true;
    }

    /**
     * Print a notification message.
     *
     * @param string $msg the notification message to display
     * @param string $class class or type of message. Please use either 'success' or 'error'
     * @return void
     */
    public static function print_notification($msg, $class = 'success') {
        global $OUTPUT;
        $pix = '<img src="' . $OUTPUT->image_url('msg_' . $class, 'tool_usersuspension') . '"/>';
        echo '<div class="tool-usersuspension-notification-' . $class . '">' . $pix . ' ' . $msg . '</div>';
    }

    /**
     * Returns HTML to display a continue button that goes to a particular URL.
     *
     * @param string|moodle_url $url The url the button goes to.
     * @param string $buttontext the text to show on the button.
     * @return string the HTML to output.
     */
    public static function continue_button($url, $buttontext) {
        global $OUTPUT;
        if (!($url instanceof \moodle_url)) {
            $url = new \moodle_url($url);
        }
        $button = new \single_button($url, $buttontext, 'get');
        $button->class = 'continuebutton';

        return $OUTPUT->render($button);
    }

    /**
     * Create a tab object with a nice image view, instead of just a regular tabobject
     *
     * @param string $id unique id of the tab in this tree, it is used to find selected and/or inactive tabs
     * @param string $pix image name
     * @param string $component component where the image will be looked for
     * @param string|moodle_url $link
     * @param string $text text on the tab
     * @param string $title title under the link, by defaul equals to text
     * @param bool $linkedwhenselected whether to display a link under the tab name when it's selected
     * @return \tabobject
     */
    public static function pictabobject($id, $pix = null, $component = 'tool_usersuspension', $link = null,
            $text = '', $title = '', $linkedwhenselected = false) {
        global $OUTPUT;
        $img = '';
        if ($pix !== null) {
            $img = '<img src="' . $OUTPUT->image_url($pix, $component) . '"> ';
        }
        return new \tabobject($id, $link, $img . $text, empty($title) ? $text : $title, $linkedwhenselected);
    }

    /**
     * print the tabs for the overview pages.
     *
     * @param array $params basic url parameters
     * @param string $selected id of the selected tab
     */
    static public function print_view_tabs($params, $selected) {
        global $CFG, $OUTPUT;
        $tabs = array();
        // Add exclusions.
        $exclusions = static::pictabobject('exclusions', 'exclusions', 'tool_usersuspension',
            new \moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/exclude.php', $params),
                get_string('table:exclusions', 'tool_usersuspension'));
        $exclusions->subtree[] = static::pictabobject('excludeaddcohort', null, 'tool_usersuspension',
            new \moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/exclude.php',
                    $params + array('action' => 'add', 'addtype' => 'cohort', 'sesskey' => sesskey())),
                get_string('action:exclude:add:cohort', 'tool_usersuspension'));
        $exclusions->subtree[] = static::pictabobject('excludeadduser', null, 'tool_usersuspension',
            new \moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/exclude.php',
                    $params + array('action' => 'add', 'addtype' => 'user', 'sesskey' => sesskey())),
                get_string('action:exclude:add:user', 'tool_usersuspension'));
        $tabs[] = $exclusions;
        // Add statuslist tabs.
        foreach (statustable::get_viewtypes() as $type) {
            $url = new \moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/statuslist.php', $params);
            $url->param('type', $type);
            $counter = '';
            switch ($type) {
                case statustable::DELETE:
                    $counter = ' (' . static::count_users_to_delete() . ')';
                    break;
                case statustable::SUSPENDED:
                    $counter = ' (' . static::count_suspended_users() . ')';
                    break;
                case statustable::TOSUSPEND:
                    $counter = ' (' . static::count_users_to_suspend() . ')';
                    break;
                case statustable::STATUS:
                    $counter = ' (' . static::count_monitored_users() . ')';
                    break;
            }
            $tabs[] = static::pictabobject($type, 'status_' . $type, 'tool_usersuspension',
                    $url, get_string('table:status:' . $type, 'tool_usersuspension') . $counter);
        }
        // Add upload tab.
        if ((bool)config::get('enablefromupload')) {
            $upload = static::pictabobject('upload', 'upload', 'tool_usersuspension',
                new \moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/upload.php', $params),
                    get_string('link:upload', 'tool_usersuspension'));
            $tabs[] = $upload;
        }
        // Add logs tabs.
        $logs = static::pictabobject('logs', 'logs', 'tool_usersuspension',
            new \moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/log.php', $params + array('history' => 0)),
                get_string('table:logs', 'tool_usersuspension'));
        $logs->subtree[] = static::pictabobject('log_latest', null, 'tool_usersuspension',
            new \moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/log.php', $params + array('history' => 0)),
                get_string('table:log:latest', 'tool_usersuspension'));
        $logs->subtree[] = static::pictabobject('log_all', null, 'tool_usersuspension',
            new \moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/log.php', $params + array('history' => 1)),
                get_string('table:log:all', 'tool_usersuspension'));
        $tabs[] = $logs;

        // Add notifications tabs.
        $notifications = static::pictabobject('notifications', null, 'tool_usersuspension',
            new \moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/notifications.php', $params),
                get_string('tab:notifications', 'tool_usersuspension'));
        $tabs[] = $notifications;

        echo $OUTPUT->tabtree($tabs, $selected);
    }

    /**
     * Generate messages (using core notifications) for (every) frontpage in this tool.
     */
    public static function generate_notifications() {
        $messages = [];
        // Main plugin enabled.
        if (!(bool)config::get('enabled')) {
            $messages[] = get_string('config:tool:disabled', 'tool_usersuspension');
        }
        // Task(s).
        if (!(bool)config::get('enableunsuspendfromfolder')) {
            $messages[] = get_string('config:unsuspendfromfolder:disabled', 'tool_usersuspension');
        }
        if (!(bool)config::get('enablefromfolder')) {
            $messages[] = get_string('config:fromfolder:disabled', 'tool_usersuspension');
        }
        // Folder(s).
        $uploadedfolder = config::get('uploadfolder');
        if (!file_exists($uploadedfolder) || !is_dir($uploadedfolder)) {
            $messages[] = 'CSV upload folder "'.$uploadedfolder.'" does not exist';
        }
        if (!is_readable($uploadedfolder) || !is_dir($uploadedfolder)) {
            $messages[] = 'CSV upload folder "'.$uploadedfolder.'" is not readable';
        }
        if (!empty($messages)) {
            return \html_writer::div(implode('<br/>', $messages), 'alert alert-warning');
        } else {
            return \html_writer::div(get_string('notifications:allok', 'tool_usersuspension'), 'alert alert-success');
        }
    }

}
