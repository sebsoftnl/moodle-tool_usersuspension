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
 * Processor file for user exclusion by file uploads
 *
 * File         upload.php
 * Encoding     UTF-8
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('toolusersuspension');
$context       = \context_system::instance();

require_capability('tool/usersuspension:administration', $context);
require_capability('moodle/user:update', context_system::instance());

$overviewurl = new moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/statuslist.php');
$thispageurl = new moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/upload.php');
if (!(bool)\tool_usersuspension\config::get('enablefromupload')) {
    echo $OUTPUT->header();
    echo '<div id="tool-usersuspension-container">';
    echo '<div>';
    \tool_usersuspension\util::print_view_tabs(array(), 'upload');
    echo '</div>';
    echo '<div class="tool-usersuspension-warning">' . get_string('config:tool:disabled', 'tool_usersuspension') . '</div>';
    echo '</div>';
    echo $OUTPUT->footer();
} else {
    $mform = new \tool_usersuspension\forms\upload($thispageurl);
    if ($mform->is_cancelled()) {
        redirect($overviewurl);
    } else if ($data = $mform->get_data()) {
        echo $OUTPUT->header();
        echo '<div id="tool-usersuspension-form-container">';
        $mform->process();
        echo '<br/>';
        echo \tool_usersuspension\util::continue_button($thispageurl, get_string('button:backtouploadform', 'tool_usersuspension'));
        echo \tool_usersuspension\util::continue_button($overviewurl, get_string('button:backtoexclusions', 'tool_usersuspension'));
        echo '</div>';
        echo $OUTPUT->footer();
    } else {
        echo $OUTPUT->header();
        echo '<div id="tool-usersuspension-form-container">';
        echo '<div>';
        \tool_usersuspension\util::print_view_tabs(array(), 'upload');
        echo '</div>';
        echo $mform->display();
        echo '</div>';
        echo $OUTPUT->footer();
    }
}