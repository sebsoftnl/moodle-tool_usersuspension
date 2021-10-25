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
 * File         exclude.php
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

use tool_usersuspension\config;
use tool_usersuspension\processor\csv as csvprocessor;

/**
 * Test script message output callback.
 *
 * @param string $msg
 */
function trtrace($msg) {
    echo html_writer::div($msg, 'alert alert-info');
}
/**
 * Write action links for this script.
 * @param moodle_url|string $pageurl
 */
function write_actiontype_links($pageurl) {
    $actiontypes = ['suspend', 'unsuspend'];
    foreach ($actiontypes as $type) {
        echo html_writer::link(new moodle_url($pageurl, ['actiontype' => $type]),
                get_string('testfromfolder:' . $type, 'tool_usersuspension'), []);
        echo '<br/>';
    }
}

admin_externalpage_setup('toolusersuspension');
$context       = \context_system::instance();

$thispageurl = new moodle_url('/' . $CFG->admin . '/tool/usersuspension/view/testfromfolder.php', array());

require_capability('tool/usersuspension:administration', $context);

// Process exclusion?
$actiontype = optional_param('actiontype', null, PARAM_ALPHA);
$actiontypes = ['suspend', 'unsuspend'];
if (!in_array($actiontype, $actiontypes)) {
    echo $OUTPUT->header();
    echo '<div class="tool-usersuspension-container">';
    echo '<div>';
    \tool_usersuspension\util::print_view_tabs(array(), 'testfromfolder');
    echo '</div>';
    write_actiontype_links($thispageurl);
    echo '</div>';
    echo $OUTPUT->footer();
} else if ($actiontype === 'suspend') {
    echo $OUTPUT->header();
    echo '<div class="tool-usersuspension-container">';
    echo '<div>';
    \tool_usersuspension\util::print_view_tabs(array(), 'testfromfolder');
    echo '</div>';
    write_actiontype_links($thispageurl);

    echo $OUTPUT->heading(get_string('testing:suspendfromfolder', 'tool_usersuspension'), 4);

    if (!(bool)config::get('enabled')) {
        echo html_writer::div(get_string('config:tool:disabled', 'tool_usersuspension'), 'alert alert-danger');
    } else {
        echo html_writer::div(get_string('config:tool:enabled', 'tool_usersuspension'), 'alert alert-info');
    }

    if (!(bool)config::get('enablefromfolder')) {
        echo html_writer::div(get_string('config:fromfolder:disabled', 'tool_usersuspension'), 'alert alert-danger');
    } else {
        echo html_writer::div(get_string('config:fromfolder:enabled', 'tool_usersuspension'), 'alert alert-info');
    }

    if (is_dir(config::get('uploadfolder'))) {
        echo html_writer::div(get_string('config:uploadfolder:exists', 'tool_usersuspension',
                config::get('uploadfolder')), 'alert alert-info');
    } else {
        echo html_writer::div(get_string('config:uploadfolder:not-exists', 'tool_usersuspension',
                config::get('uploadfolder')), 'alert alert-danger');
    }

    $uploadedfile = config::get('uploadfolder') . '/' . config::get('uploadfilename');
    if (!file_exists($uploadedfile) || is_dir($uploadedfile)) {
        echo html_writer::div(get_string('config:uploadfile:not-exists', 'tool_usersuspension',
                $uploadedfile), 'alert alert-danger');
    } else {
        echo html_writer::div(get_string('config:uploadfile:exists', 'tool_usersuspension',
                $uploadedfile), 'alert alert-info');
        if (!is_readable($uploadedfile)) {
            echo html_writer::div(get_string('msg:file-not-readable', 'tool_usersuspension',
                    $uploadedfile), 'alert alert-danger');
        }

        $choices = \csv_import_reader::get_delimiter_list();
        // Process uploaded file.
        $proc = new csvprocessor();
        $proc->set_file($uploadedfile);
        $proc->set_delimiter($choices[config::get('csvdelimiter')]);
        $proc->set_enclosure('"');
        $proc->set_notifycallback('trtrace');
        $proc->set_mode(csvprocessor::MODE_SUSPEND);
        $proc->set_testmode();
        $proc->process();

        if (is_writable($uploadedfile)) {
            echo html_writer::div(get_string('msg:file-would-delete', 'tool_usersuspension',
                    $uploadedfile), 'alert alert-success');
        } else {
            echo html_writer::div(get_string('msg:file-not-writeable', 'tool_usersuspension',
                    $uploadedfile), 'alert alert-danger');
        }
    }
    echo '</div>';
    echo $OUTPUT->footer();
} else if ($actiontype === 'unsuspend') {
    echo $OUTPUT->header();
    echo '<div class="tool-usersuspension-container">';
    echo '<div>';
    \tool_usersuspension\util::print_view_tabs(array(), 'testfromfolder');
    echo '</div>';
    write_actiontype_links($thispageurl);

    echo $OUTPUT->heading(get_string('testing:unsuspendfromfolder', 'tool_usersuspension'), 4);

    if (!(bool)config::get('enabled')) {
        echo html_writer::div(get_string('config:tool:disabled', 'tool_usersuspension'), 'alert alert-danger');
    } else {
        echo html_writer::div(get_string('config:tool:enabled', 'tool_usersuspension'), 'alert alert-info');
    }

    if (!(bool)config::get('enableunsuspendfromfolder')) {
        echo html_writer::div(get_string('config:unsuspendfromfolder:disabled', 'tool_usersuspension'), 'alert alert-danger');
    } else {
        echo html_writer::div(get_string('config:unsuspendfromfolder:enabled', 'tool_usersuspension'), 'alert alert-info');
    }

    if (is_dir(config::get('uploadfolder'))) {
        echo html_writer::div(get_string('config:uploadfolder:exists', 'tool_usersuspension',
                config::get('uploadfolder')), 'alert alert-info');
    } else {
        echo html_writer::div(get_string('config:uploadfolder:not-exists', 'tool_usersuspension',
                config::get('uploadfolder')), 'alert alert-danger');
    }

    $uploadedfile = config::get('uploadfolder') . '/' . config::get('unsuspenduploadfilename');
    if (!file_exists($uploadedfile) || is_dir($uploadedfile)) {
        echo html_writer::div('CSV File "'.$uploadedfile.'" does not exist', 'alert alert-danger');
    } else {
        echo html_writer::div(get_string('config:uploadfile:exists', 'tool_usersuspension',
                $uploadedfile), 'alert alert-info');
        if (!is_readable($uploadedfile)) {
            echo html_writer::div(get_string('msg:file-not-readable', 'tool_usersuspension',
                    $uploadedfile), 'alert alert-danger');
        }

        $choices = \csv_import_reader::get_delimiter_list();
        // Process uploaded file.
        $proc = new csvprocessor();
        $proc->set_file($uploadedfile);
        $proc->set_delimiter($choices[config::get('csvdelimiter')]);
        $proc->set_enclosure('"');
        $proc->set_notifycallback('trtrace');
        $proc->set_mode(csvprocessor::MODE_UNSUSPEND);
        $proc->set_testmode();
        $proc->process();

        if (is_writable($uploadedfile)) {
            echo html_writer::div(get_string('msg:file-would-delete', 'tool_usersuspension',
                    $uploadedfile), 'alert alert-danger');
        } else {
            echo html_writer::div(get_string('msg:file-not-writeable', 'tool_usersuspension',
                    $uploadedfile), 'alert alert-danger');
        }
    }
    echo '</div>';
    echo $OUTPUT->footer();
}
