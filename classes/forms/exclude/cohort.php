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
 * this file contains the cohort selection form to exclude cohorts
 *
 * File         cohort.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_usersuspension\forms\exclude;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

/**
 * tool_usersuspension\forms\cohort
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cohort extends \moodleform {

    /**
     * form definition
     */
    public function definition() {
        global $DB;
        $mform = $this->_form;

        $excludedcohorts = $DB->get_fieldset_select('tool_usersuspension_excl', 'refid', 'type = ?', array('cohort'));
        list($sqlin, $params) = $DB->get_in_or_equal($excludedcohorts, SQL_PARAMS_QM, 'param', false, true);
        $cohorts = $DB->get_records_sql_menu('SELECT id,name FROM {cohort} WHERE id '. $sqlin, $params);
        if (count($cohorts) == 0) {
            $mform->addElement('static', 'xstat1', '', get_string('info:no-exclusion-cohorts', 'tool_usersuspension'));
        } else {
            $size = min(10, max(0, count($cohorts)));
            $select1 = $mform->addElement('select', 'cohort', get_string('cohort', 'cohort'), $cohorts, array('size' => $size));
            $select1->setMultiple(true);
            $mform->addRule('cohort', get_string('required'), 'required', null, 'client');
        }

        $this->add_action_buttons(true, get_string('button:continue', 'tool_usersuspension'));
    }

    /**
     * Process the posted form
     *
     * @throws \moodle_exception
     */
    public function process() {
        global $DB;
        $data = $this->get_data();
        if ($data === null) {
            return false;
            \tool_usersuspension\util::print_notification(get_string('msg:exclusion:cohort:none-selected',
                'tool_usersuspension'), 'success');
        }

        if (!empty($data->cohort)) {
            foreach ($data->cohort as $cohortid) {
                $cohort = $DB->get_record('cohort', array('id' => $cohortid));
                $record = (object) array('type' => 'cohort',
                    'refid' => $cohortid, 'timecreated' => time());
                $DB->insert_record('tool_usersuspension_excl', $record);
                \tool_usersuspension\util::print_notification(get_string('msg:exclusion:record:cohort:inserted',
                            'tool_usersuspension', $cohort), 'success');
            }
        }
    }

}