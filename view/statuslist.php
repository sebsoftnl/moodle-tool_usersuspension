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
 * Processor file for displaying status tables/overviews
 *
 * File         statuslist.php
 * Encoding     UTF-8
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../../../config.php');

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/user/filters/lib.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

admin_externalpage_setup('toolusersuspension');
$context       = \context_system::instance();

$viewtype = optional_param('type', \tool_usersuspension\statustable::STATUS, PARAM_ALPHA);
$thispageurl = new moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/statuslist.php', array('type' => $viewtype));
require_capability('tool/usersuspension:viewstatus', $context);

// Process action?
$action = optional_param('action', null, PARAM_ALPHA);
if ($action === 'exclude') {
    require_sesskey();
    $id = required_param('id', PARAM_INT);
    $obj = array('type' => 'user', 'refid' => $id);
    if (!$DB->record_exists('tool_usersuspension_excl', $obj)) {
        $obj['timecreated'] = time();
        $DB->insert_record('tool_usersuspension_excl', (object)$obj);
        $message = get_string('msg:exclusion:record:inserted', 'tool_usersuspension');
    } else {
        $message = get_string('msg:exclusion:record:exists', 'tool_usersuspension');
    }
    redirect($thispageurl, $message, 5);
} else if ($action === 'suspend') {
    require_sesskey();
    require_capability('moodle/user:update', context_system::instance());
    $id = required_param('id', PARAM_INT);
    $user = $DB->get_record('user', array('id' => $id));
    $result = \tool_usersuspension\util::do_suspend_user($user, false);
    if ($result === true) {
        $message = get_string('msg:user:suspend:success', 'tool_usersuspension', $user);
    } else {
        $message = get_string('msg:user:suspend:failed', 'tool_usersuspension', $user);
    }
    redirect($thispageurl, $message, 5);
} else if ($action === 'unsuspend') {
    require_sesskey();
    require_capability('moodle/user:update', context_system::instance());
    $id = required_param('id', PARAM_INT);
    $user = $DB->get_record('user', array('id' => $id));
    $result = \tool_usersuspension\util::do_unsuspend_user($user);
    if ($result === true) {
        $message = get_string('msg:user:unsuspend:success', 'tool_usersuspension', $user);
    } else {
        $message = get_string('msg:user:unsuspend:failed', 'tool_usersuspension', $user);
    }
    redirect($thispageurl, $message, 5);
} else {
    // Prepare specific filter fields and detect whether or not the current view is applicable.
    $viewtypeenabled = true;
    $viewtypenotification = '';
    $fields = ['realname' => 0, 'username' => 0];
    switch ($viewtype) {
        case \tool_usersuspension\statustable::DELETE:
            $fields['deleteon'] = 0;
            $viewtypeenabled = (bool) \tool_usersuspension\config::get('enablecleanup');
            if (!$viewtypeenabled) {
                $viewtypenotification = get_string('config:cleanup:disabled', 'tool_usersuspension');
                $viewtypenotification .= '<br/>'. get_string('configoption:notactive', 'tool_usersuspension');
            }
            break;
        case \tool_usersuspension\statustable::TOSUSPEND:
            $fields['suspendon'] = 0;
            $viewtypeenabled = (bool) \tool_usersuspension\config::get('enable_smartdetect');
            if (!$viewtypeenabled) {
                $viewtypenotification = get_string('config:smartdetect:disabled', 'tool_usersuspension');
                $viewtypenotification .= '<br/>'. get_string('configoption:notactive', 'tool_usersuspension');
            }
            break;
    }

    $table = new \tool_usersuspension\statustable($viewtype);
    $table->baseurl = $thispageurl;
    $table->is_downloadable(true);

    $userfiltering = new \tool_usersuspension\statustable_filtering($viewtype, $fields, $table->baseurl);
    $table->set_filtering($userfiltering);

    $download = optional_param('download', null, PARAM_ALPHA);
    if ($table->is_downloading($download, 'usersuspension-statustable-' . $viewtype, $viewtype)) {
        $table->render(0);
        exit;
    }

    echo $OUTPUT->header();
    echo '<div class="tool-usersuspension-container">';
    echo '<div>';
    \tool_usersuspension\util::print_view_tabs(array(), $viewtype);
    echo '</div>';
    echo '<div>' . get_string('page:view:statuslist.php:introduction:' . $viewtype, 'tool_usersuspension') . '</div>';
    echo '<div>';
    if (!empty($viewtypenotification)) {
        echo '<div class="alert alert-info">';
        echo $viewtypenotification;
        echo '</div>';
    }
    $userfiltering->display_add();
    $userfiltering->display_active();
    $table->render(25);
    echo '</div>';
    echo '</div>';

    echo $OUTPUT->footer();
}
