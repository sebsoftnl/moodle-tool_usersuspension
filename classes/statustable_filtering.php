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
 * File containing the filter for the statustable.
 *
 * File         statustable_filter.php
 * Encoding     UTF-8
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_usersuspension;

use user_filtering;
use user_add_filter_form;
use moodleform;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/tablelib.php');
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/user/filters/lib.php');

/**
 * tool_usersuspension\statustable_filter
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class statustable_filtering extends user_filtering {

    /**
     * @var string type ID for filter
     */
    protected $type;

    /**
     * Generate and return filter identifier to use on session.
     *
     * Because the filter is actually based on the default user_filter in Moodle,
     * and it uses a hardcoded identifier, we override this behaviour.
     * It is _unwanted_ behaviour to leave the original, which would have been easier,
     * but it would completely mess up (and corrupt) actual user filtering.
     * Tbh, i consider the dedicated Moodle user filtering rather flawed.
     * Alas, it was written specifically for the user overviews back when cavemen still roamed the planet.
     *
     * @return string
     */
    protected function get_filter_id() {
        return "user_filtering_{$this->type}";
    }

    /**
     * Contructor.
     *
     * This method is copied over from the parent (and we DO NOT call parent c'tor) and mainly kept the same
     * except for the fact we customize the session storage identifier.
     *
     * @param string $type filter type identifier. Needed because üser_filtering"session key is hardcoded in parent
     * @param array $fieldnames array of visible user fields
     * @param string $baseurl base url used for submission/return, null if the same of current page
     * @param array $extraparams extra page parameters
     */
    public function __construct($type, $fieldnames = null, $baseurl = null, $extraparams = null) {
        global $SESSION;
        $this->type = $type;

        $filterid = $this->get_filter_id();
        if (!isset($SESSION->{$filterid})) {
            $SESSION->{$filterid} = [];
        }

        if (empty($fieldnames)) {
            $fieldnames = ['realname' => 0, 'lastname' => 1, 'firstname' => 1,
                'username' => 1, 'email' => 1, 'city' => 1, 'country' => 1,
                'confirmed' => 1, 'suspended' => 1, 'profile' => 1, 'courserole' => 1,
                'anycourses' => 1, 'systemrole' => 1, 'cohort' => 1, 'firstaccess' => 1,
                'lastaccess' => 1, 'neveraccessed' => 1, 'timemodified' => 1,
                'nevermodified' => 1, 'auth' => 1, 'mnethostid' => 1, 'idnumber' => 1,
            ];
        }

        $this->_fields  = [];

        foreach ($fieldnames as $fieldname => $advanced) {
            if ($field = $this->get_field($fieldname, $advanced)) {
                $this->_fields[$fieldname] = $field;
            }
        }

        // Fist the new filter form.
        $this->_addform = new user_add_filter_form($baseurl, ['fields' => $this->_fields,
            'extraparams' => $extraparams]);
        if ($adddata = $this->_addform->get_data()) {
            foreach ($this->_fields as $fname => $field) {
                $data = $field->check_data($adddata);
                if ($data === false) {
                    continue; // Nothing new.
                }
                if (!array_key_exists($fname, $SESSION->{$filterid})) {
                    $SESSION->{$filterid}[$fname] = [];
                }
                $SESSION->{$filterid}[$fname][] = $data;
            }
            // Clear the form.
            $_POST = [];
            $this->_addform = new user_add_filter_form($baseurl, ['fields' => $this->_fields,
                'extraparams' => $extraparams]);
        }

        // Now the active filters.
        $this->_activeform = new active_filter_form($baseurl, ['fields' => $this->_fields,
            'extraparams' => $extraparams, 'filterid' => $filterid]);
        if ($adddata = $this->_activeform->get_data()) {
            if (!empty($adddata->removeall)) {
                $SESSION->{$filterid} = [];

            } else if (!empty($adddata->removeselected) && !empty($adddata->filter)) {
                foreach ($adddata->filter as $fname => $instances) {
                    foreach ($instances as $i => $val) {
                        if (empty($val)) {
                            continue;
                        }
                        unset($SESSION->{$filterid}[$fname][$i]);
                    }
                    if (empty($SESSION->{$filterid}[$fname])) {
                        unset($SESSION->{$filterid}[$fname]);
                    }
                }
            }
            // Clear+reload the form.
            $_POST = [];
            $this->_activeform = new active_filter_form($baseurl, ['fields' => $this->_fields,
                'extraparams' => $extraparams, 'filterid' => $filterid]);
        }
        // Now the active filters.
    }

    /**
     * Creates known user filter if present
     * @param string $fieldname
     * @param boolean $advanced
     * @return object filter
     */
    public function get_field($fieldname, $advanced) {
        global $DB;

        switch ($DB->get_dbfamily()) {
            case 'mssql':
                $sqlpartgreatest = 'IIF(u.lastaccess >= u.firstaccess, ' .
                    'IIF(u.timemodified >= u.lastaccess, u.timemodified, u.lastaccess), u.firstaccess)';
                break;
            default:
                $sqlpartgreatest = 'GREATEST(u.firstaccess, u.lastaccess, u.timemodified)';
                break;
        }

        switch ($fieldname) {
            case 'suspendon':
                // Mimic the field as SQL, because it's NOT a real field.
                $field = '(' . $sqlpartgreatest . ' + ' .
                    config::get('smartdetect_suspendafter') . ')';
                return new \user_filter_date('suspendon', get_string('suspendon', 'tool_usersuspension'),
                        $advanced, $field);
            case 'deleteon':
                // Mimic the field as SQL, because it's NOT a real field.
                $field = '(' . $sqlpartgreatest . ' + ' .
                    config::get('cleanup_deleteafter') . ')';
                return new \user_filter_date('deleteon', get_string('deleteon', 'tool_usersuspension'),
                        $advanced, $field);
            default:
                return parent::get_field($fieldname, $advanced);
        }
    }

    /**
     * Returns sql where statement based on active user filters
     *
     * This method is copied over from the parent and mainly kept the same
     * except for the fact we customize the session storage identifier.
     *
     * @param string $extra sql
     * @param array $params named params (recommended prefix ex)
     * @return array sql string and $params
     */
    public function get_sql_filter($extra='', array $params=null) {
        global $SESSION;
        $filterid = $this->get_filter_id();

        $sqls = [];
        if ($extra != '') {
            $sqls[] = $extra;
        }
        $params = (array)$params;

        if (!empty($SESSION->{$filterid})) {
            foreach ($SESSION->{$filterid} as $fname => $datas) {
                if (!array_key_exists($fname, $this->_fields)) {
                    continue; // Filter not used.
                }
                $field = $this->_fields[$fname];
                foreach ($datas as $data) {
                    list($s, $p) = $field->get_sql_filter($data);
                    $sqls[] = $s;
                    $params = $params + $p;
                }
            }
        }

        if (empty($sqls)) {
            return ['', []];
        } else {
            $sqls = implode(' AND ', $sqls);
            return [$sqls, $params];
        }
    }

}

/**
 * Class user_active_filter_form
 *
 * This is extended an intentionally kept internal here.
 * The original implementation, again, refers to a hardcoded filter id.
 * We use a dynamic one, so that's why we have a more dedicated internal class.
 *
 * @package     tool_usersuspension
 * @category    admin
 *
 * @copyright   Sebsoft.nl
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class active_filter_form extends moodleform {

    /**
     * Form definition.
     */
    public function definition() {
        global $SESSION;

        $mform       =& $this->_form;
        $fields      = $this->_customdata['fields'];
        $extraparams = $this->_customdata['extraparams'];
        $filterid = $this->_customdata['filterid'];

        if (!empty($SESSION->{$filterid})) {
            // Add controls for each active filter in the active filters group.
            $mform->addElement('header', 'actfilterhdr', get_string('actfilterhdr', 'filters'));

            foreach ($SESSION->{$filterid} as $fname => $datas) {
                if (!array_key_exists($fname, $fields)) {
                    continue; // Filter not used.
                }
                $field = $fields[$fname];
                foreach ($datas as $i => $data) {
                    $description = $field->get_label($data);
                    $mform->addElement('checkbox', 'filter['.$fname.']['.$i.']', null, $description);
                }
            }

            if ($extraparams) {
                foreach ($extraparams as $key => $value) {
                    $mform->addElement('hidden', $key, $value);
                    $mform->setType($key, PARAM_RAW);
                }
            }

            $objs = [];
            $objs[] = &$mform->createElement('submit', 'removeselected', get_string('removeselected', 'filters'));
            $objs[] = &$mform->createElement('submit', 'removeall', get_string('removeall', 'filters'));
            $mform->addElement('group', 'actfiltergrp', '', $objs, ' ', false);
        }
    }

}
