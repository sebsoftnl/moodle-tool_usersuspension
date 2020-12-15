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
 * this file contains CSV file suspension processor.
 *
 * File         csv.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_usersuspension\processor;

defined('MOODLE_INTERNAL') || die;

/**
 * Description of csv
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class csv {

    /**
     * @var int SUSPEND MODE
     */
    const MODE_SUSPEND = 1;
    /**
     * @var int UNSUSPEND MODE
     */
    const MODE_UNSUSPEND = 2;

    /**
     * CSV Filename
     * @var string
     */
    protected $file;
    /**
     * CSV read delimiter
     * @var string
     */
    protected $delimiter = ';';
    /**
     * CSV read enclosure
     * @var string
     */
    protected $enclosure = '"';
    /**
     * CSV read escape character
     * @var string
     */
    protected $escape = '\\';
    /**
     * CSV file handle
     * @var string
     */
    protected $fh;
    /**
     * Notification handler.
     * Should be a callable that takes a single string argument
     * @var \callable
     */
    protected $notifycallback;
    /**
     * User exclusion list.
     * This is a list of userids that are excluded from processing.
     * @var array list of userids
     */
    protected $exclusionlist;
    /**
     * Set test run.
     * @var bool
     */
    protected $testmode = false;
    /**
     * Set run mode.
     * @var bool
     */
    protected $mode = 1;

    /**
     * Return CSV filename
     *
     * @return string
     */
    public function get_file() {
        return $this->file;
    }

    /**
     * Return CSV read delimiter
     *
     * @return string
     */
    public function get_delimiter() {
        return $this->delimiter;
    }

    /**
     * Return CSV read enclosure
     *
     * @return string
     */
    public function get_enclosure() {
        return $this->enclosure;
    }

    /**
     * Return CSV read escape character
     *
     * @return string
     */
    public function get_escape() {
        return $this->escape;
    }

    /**
     * Set CSV filename
     *
     * @param string $file filename
     * @return \tool_usersuspension\processor\csv
     */
    public function set_file($file) {
        $this->file = $file;
        return $this;
    }

    /**
     * Set CSV column delimiter
     *
     * @param string $delimiter CSV column delimiter
     * @return \tool_usersuspension\processor\csv
     */
    public function set_delimiter($delimiter) {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * Set CSV enclosure
     *
     * @param string $enclosure CSV enclosure
     * @return \tool_usersuspension\processor\csv
     */
    public function set_enclosure($enclosure) {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * Set CSV escape character
     *
     * @param string $escape CSV escape character
     * @return \tool_usersuspension\processor\csv
     */
    public function set_escape($escape) {
        $this->escape = $escape;
        return $this;
    }

    /**
     * Set callback function for messages, e.g. 'mtrace'.
     * Should take a single string argument denoting the message.
     *
     * @param \callable $notifycallback
     * @return \tool_usersuspension\processor\csv
     */
    public function set_notifycallback($notifycallback) {
        if (is_callable($notifycallback)) {
            $this->notifycallback = $notifycallback;
        }
        return $this;
    }

    /**
     * Do we suspend at all (have a test run)?
     * @return bool
     */
    public function get_testmode() {
        return $this->testmode;
    }

    /**
     * Set no-suspend mode (test mode)
     *
     * @param bool $testmode
     * @return $this
     */
    public function set_testmode($testmode = true) {
        $this->testmode = $testmode;
        return $this;
    }

    /**
     * Get run mode
     * @return int
     */
    public function get_mode() {
        return $this->mode;
    }

    /**
     * Get run mode
     * @param int $mode
     * @return $this
     */
    public function set_mode($mode) {
        $this->mode = $mode;
        return $this;
    }

    /**
     * Create a new instance of a CSV processor
     */
    public function __construct() {
        $this->fh = null;
    }

    /**
     * Pass on a message to the notifycallback, if applicable
     *
     * @param string $msg
     */
    protected function notify($msg) {
        if (isset($this->notifycallback) && $this->notifycallback !== null) {
            call_user_func($this->notifycallback, $msg);
        }
    }

    /**
     * Process the CSV file
     *
     * @return void
     */
    public function process() {
        // Load exclusions.
        $this->notify(get_string('notify:load-exclude-list', 'tool_usersuspension'));
        $this->exclusionlist = \tool_usersuspension\util::get_user_exclusion_list();
        $this->notify(get_string('notify:load-file', 'tool_usersuspension', $this->file));
        $this->fh = fopen($this->file, 'r+b');
        if ($this->fh === false) {
            $this->notify(get_string('notify:load-file-fail', 'tool_usersuspension', $this->file));
            return;
        }
        while (true && !feof($this->fh)) {
            $line = fgetcsv($this->fh, 0, $this->delimiter, $this->enclosure, $this->escape);
            if ($line !== null && $line !== false) {
                if ($line[0] != 'type') {
                    $this->_process_line($line);
                }
            }
        }
        fclose($this->fh);
    }

    /**
     * Process a single CSV line
     *
     * @param array $line array representing the read CSV line
     * @return bool true if successfully processed, false otherwise
     */
    protected function _process_line($line) {
        if (count($line) === 1) {
            // Assumes email.
            $line = array('email', $line[0]);
        }
        // Continue normal processing. Note that we CLEAN the params.
        $rs = false;
        $type = clean_param(trim($line[0]), PARAM_ALPHA);
        switch ($type) {
            case 'email':
                $email = clean_param(trim($line[1]), PARAM_EMAIL);
                if ($this->mode == static::MODE_SUSPEND) {
                    $rs = $this->_suspend_user(array('email' => $email));
                } else {
                    $rs = $this->_unsuspend_user(array('email' => $email));
                }
                break;
            case 'idnumber':
                $idnumber = clean_param(trim($line[1]), PARAM_NOTAGS);
                if ($this->mode == static::MODE_SUSPEND) {
                    $rs = $this->_suspend_user(array('idnumber' => $idnumber));
                } else {
                    $rs = $this->_unsuspend_user(array('idnumber' => $idnumber));
                }
                break;
            case 'username':
                $username = clean_param(trim($line[1]), PARAM_USERNAME);
                if ($this->mode == static::MODE_SUSPEND) {
                    $rs = $this->_suspend_user(array('username' => $username));
                } else {
                    $rs = $this->_unsuspend_user(array('username' => $username));
                }
                break;
            default:
                $this->notify(get_string('notify:unknown-suspend-type', 'tool_usersuspension', $type));
                break;
        }
        return $rs;
    }

    /**
     * Performs the user suspension.
     * The user, when found, is checked against the exclusion list to determine
     * if he/she shouldn't be suspended.
     *
     * @param array $params general parameters to use in the query to lookup a record from the database
     * @return bool true if successfully suspended, false otherwise
     */
    protected function _suspend_user($params) {
        global $CFG, $DB;
        $this->notify(__METHOD__ . ": " . key($params) . ' = ' . current($params));
        $params['mnethostid'] = $CFG->mnet_localhost_id;
        $params['deleted'] = 0;
        $params['suspended'] = 0;
        $user = $DB->get_record('user', $params);
        if (!empty($user)) {
            if (in_array($user->id, $this->exclusionlist)) {
                $this->notify(get_string('notify:suspend-excluded-user', 'tool_usersuspension', $user));
                return;
            }
            $this->notify(get_string('notify:suspend-user', 'tool_usersuspension', $user));
            // Suspend this user.
            if ($this->testmode) {
                $result = true;
                $this->notify(get_string('msg:user:suspend:testmodemode', 'tool_usersuspension', $user));
                return $result;
            }
            $result = \tool_usersuspension\util::do_suspend_user($user);
            if ($result === true) {
                $this->notify(get_string('msg:user:suspend:success', 'tool_usersuspension', $user));
            } else {
                $this->notify(get_string('msg:user:suspend:failed', 'tool_usersuspension', $user));
            }
            return $result;
        }
        $this->notify("\t" . get_string('msg:user:not-found', 'tool_usersuspension'));
        return false;
    }

    /**
     * Performs the user suspension.
     * The user, when found, is checked against the exclusion list to determine
     * if he/she shouldn't be suspended.
     *
     * @param array $params general parameters to use in the query to lookup a record from the database
     * @return bool true if successfully suspended, false otherwise
     */
    protected function _unsuspend_user($params) {
        global $CFG, $DB;
        $this->notify(__METHOD__ . ": " . key($params) . ' = ' . current($params));
        $params['mnethostid'] = $CFG->mnet_localhost_id;
        $params['deleted'] = 0;
        $params['suspended'] = 1;
        $user = $DB->get_record('user', $params);
        if (!empty($user)) {
            if (in_array($user->id, $this->exclusionlist)) {
                $this->notify(get_string('notify:suspend-excluded-user', 'tool_usersuspension', $user));
                return;
            }
            $this->notify(get_string('notify:suspend-user', 'tool_usersuspension', $user));
            // Suspend this user.
            if ($this->testmode) {
                $result = true;
                $this->notify(get_string('msg:user:suspend:testmodemode', 'tool_usersuspension', $user));
                return $result;
            }
            $result = \tool_usersuspension\util::do_unsuspend_user($user);
            if ($result === true) {
                $this->notify(get_string('msg:user:unsuspend:success', 'tool_usersuspension', $user));
            } else {
                $this->notify(get_string('msg:user:unsuspend:failed', 'tool_usersuspension', $user));
            }
            return $result;
        }
        $this->notify("\t" . get_string('msg:user:not-found', 'tool_usersuspension'));
        return false;
    }

}