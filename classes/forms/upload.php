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
 * this file contains the CSV upload form class
 *
 * File         upload.php
 * Encoding     UTF-8
 * @copyright   Sebsoft.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_usersuspension\forms;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

/**
 * tool_usersuspension\forms\upload
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class upload extends \moodleform {

    /**
     * form definition
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // Example CSV.
        $urldownloadcsv = new \moodle_url($CFG->wwwroot . '/admin/tool/usersuspension/assets/example.csv');
        $link = '<a href="' . $urldownloadcsv
                . '" target="_blank">' . get_string('download-sample-csv', 'tool_usersuspension') . '</a>';

        $mform->addElement('static', 'uploadfiledesc', '',
                get_string('form:static:uploadfile:desc', 'tool_usersuspension') . $link);
        $maxbytes = 1048576; // 1 MB.
        $mform->addElement('filepicker', 'userfile', get_string('file'), null,
                array('maxbytes' => $maxbytes, 'accepted_types' => array('.csv')));

        $delims = array(';' => ';', '|' => '|', ',' => ',');
        $select1 = $mform->addElement('select', 'delimiter', get_string('csv:delimiter', 'tool_usersuspension'), $delims);
        $mform->setType('delimiter', PARAM_TEXT);
        $select1->setSelected(';');

        $enclosures = array('"' => '"', "'" => "'");
        $select2 = $mform->addElement('select', 'enclosure', get_string('csv:enclosure', 'tool_usersuspension'), $enclosures);
        $mform->setType('enclosure', PARAM_TEXT);
        $select2->setSelected('"');

        $options = array(
            \tool_usersuspension\processor\csv::MODE_SUSPEND => get_string('suspend', 'tool_usersuspension'),
            \tool_usersuspension\processor\csv::MODE_UNSUSPEND => get_string('unsuspend', 'tool_usersuspension'),
        );
        $mform->addElement('select', 'suspendmode', get_string('suspendmode', 'tool_usersuspension'), $options);
        $mform->setType('suspendmode', PARAM_INT);
        $mform->setDefault('suspendmode', \tool_usersuspension\processor\csv::MODE_SUSPEND);

        $this->add_action_buttons(true, get_string('csv:upload:continue', 'tool_usersuspension'));
    }

    /**
     * Process the posted form
     *
     * @throws \moodle_exception
     */
    public function process() {
        global $CFG;
        $data = $this->get_data();
        if ($data === null) {
            return false;
        }

        $fn = $this->get_new_filename('userfile');
        $file = $CFG->tempdir . '/' . $fn;
        if (file_exists($file)) {
            @unlink($file);
        }
        $uploadedfile = $this->save_file('userfile', $file);
        if (!$uploadedfile) {
            \tool_usersuspension\util::print_notification(get_string('msg:file:upload:fail',
                    'tool_usersuspension'), 'error');
            return;
        }

        // Process upload.
        $proc = new \tool_usersuspension\processor\csv();
        $proc->set_file($file);
        $proc->set_delimiter($data->delimiter);
        $proc->set_enclosure($data->enclosure);
        $proc->set_notifycallback('mtrace');
        $proc->set_mode($data->suspendmode);
        echo "<pre>";
        $proc->process();
        echo "</pre>";
        // Delete uploaded file.
        unlink($file);
    }

}