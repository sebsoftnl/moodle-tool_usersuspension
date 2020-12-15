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
 * Processor file for user exclusion overview and configuration
 *
 * File         notifications.php
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

$thispageurl = new moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/notifications.php', array());

require_capability('tool/usersuspension:administration', $context);

echo $OUTPUT->header();
echo '<div class="tool-usersuspension-container">';
echo '<div>';
\tool_usersuspension\util::print_view_tabs(array(), 'notifications');
echo '</div>';
echo '<div>' . get_string('page:view:notifications.php:introduction', 'tool_usersuspension') . '</div>';
echo \tool_usersuspension\util::generate_notifications();
echo '</div>';
echo $OUTPUT->footer();