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
 * Callback point for tool usersuspension
 *
 * @package     tool_usersuspension
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/

defined('MOODLE_INTERNAL') || die('moodle_internal not defined');

/**
 * Execute/inject code before sending HTTP headers.
 */
function tool_usersuspension_before_http_headers() {
    global $SESSION;

    if (!isloggedin() || isguestuser()) {
        return;
    }

    if (!empty($SESSION->warncheck)) {
        return;
    }

    if (get_user_preferences('tool_usersuspension_warned', false)) {
        unset_user_preference('tool_usersuspension_warned');
    }

    $SESSION->warncheck = true;
}
