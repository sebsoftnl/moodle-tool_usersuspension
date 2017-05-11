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
 * this file contains the user selection form to exclude users
 *
 * File         user.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_usersuspension\forms\exclude;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

/**
 * tool_usersuspension\forms\user
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user extends \moodleform {

    /**
     * User Selector for currently known users
     * @var \tool_usersuspension\exclude\user\selector\current
     */
    protected $currentuserselector;
    /**
     * User Selector for currently known users
     * @var \tool_usersuspension\exclude\user\selector\potential
     */
    protected $potentialuserselector;

    /**
     * form definition
     */
    public function definition() {
        global $OUTPUT;
        // Create the user selector objects.
        $options = array('accesscontext' => \context_system::instance());
        $this->currentuserselector = new \tool_usersuspension\exclude\user\selector\current('removeselect', $options);
        $this->potentialuserselector = new \tool_usersuspension\exclude\user\selector\potential('addselect', $options);
        $mform = $this->_form;
        // This element is only here so the form will actually get submitted.
        $mform->addElement('hidden', 'processor', 1);
        $mform->setType('processor', PARAM_INT);

        // Add user selection lists and submit controls.
        $html = '
          <table summary="" class="suspendexcludetable generaltable generalbox boxaligncenter" cellspacing="0">
            <tr><td id="existingcell"><p><label for="removeselect">' .
                get_string('label:users:excluded', 'tool_usersuspension') . '</label></p>';
        $html .= $this->currentuserselector->display(true);
        $html .= '</td><td id="buttonscell"><div id="controls">';
        $html .= '<input name="add" id="add" type="submit" value="' . $OUTPUT->larrow() .
                '&nbsp;' . get_string('add') . '" title="' . get_string('add') . '" /><br />
                <input name="remove" id="remove" type="submit" value="' . get_string('remove') .
                '&nbsp;' . $OUTPUT->rarrow() . '" title="' . get_string('remove') . '" />';
        $html .= '</div></td><td id="potentialcell"><p><label for="addselect">' .
                get_string('label:users:potential', 'tool_usersuspension') . '</label></p>';
        $html .= $this->potentialuserselector->display(true);
        $html .= '</td></tr></table>';
        $mform->addElement('html', $html);
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
        }

        $add = (bool)optional_param('add', false, PARAM_BOOL);
        $remove = (bool)optional_param('remove', false, PARAM_BOOL);

        if ($remove) {
            // Remove user(s).
            $userstoremove = $this->currentuserselector->get_selected_users();
            if (!empty($userstoremove)) {
                foreach ($userstoremove as $removeuser) {
                    $obj = array('type' => 'user', 'refid' => $removeuser->id);
                    if ($DB->record_exists('tool_usersuspension_excl', $obj)) {
                        $DB->delete_records('tool_usersuspension_excl', $obj);
                        $removeuser->fullname = fullname($removeuser);
                        \tool_usersuspension\util::print_notification(
                            get_string('msg:exclusion:record:user:deleted', 'tool_usersuspension', $removeuser), 'success');
                    }
                }
            }
        } else if ($add) {
            // Add user(s).
            $userstoexclude = $this->potentialuserselector->get_selected_users();
            if (!empty($userstoexclude)) {
                foreach ($userstoexclude as $adduser) {
                    $obj = array('type' => 'user', 'refid' => $adduser->id);
                    if (!$DB->record_exists('tool_usersuspension_excl', $obj)) {
                        $obj['timecreated'] = time();
                        $DB->insert_record('tool_usersuspension_excl', (object)$obj);
                        $adduser->fullname = fullname($adduser);
                        \tool_usersuspension\util::print_notification(get_string('msg:exclusion:record:user:inserted',
                            'tool_usersuspension', $adduser), 'success');
                    }
                }
            }
        }
        $this->potentialuserselector->invalidate_selected_users();
        $this->currentuserselector->invalidate_selected_users();
    }

}