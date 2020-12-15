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
 * general global tool settings
 *
 * File         settings.php
 * Encoding     UTF-8
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * */
defined('MOODLE_INTERNAL') || die('moodle_internal not defined');

if ($hassiteconfig) {
    $temp = new admin_settingpage('suspensionsettings', new lang_string('suspensionsettings', 'tool_usersuspension'));
    // Header.
    $image = '<a href="http://www.sebsoft.nl" target="_new"><img src="' .
            $OUTPUT->image_url('logo', 'tool_usersuspension') . '" /></a>&nbsp;&nbsp;&nbsp;';
    $donate = '<a href="https://customerpanel.sebsoft.nl/sebsoft/donate/intro.php" target="_new"><img src="' .
            $OUTPUT->image_url('donate', 'tool_usersuspension') . '" /></a>';
    $header = '<div class="tool-usersuspension-logopromo">' . $image . $donate . '</div>';
    $temp->add(new admin_setting_heading('tool_usersuspension_logopromo',
            get_string('promo', 'tool_usersuspension'),
            get_string('promodesc', 'tool_usersuspension', $header)));

    // Default settings.
    $temp->add(new admin_setting_heading('tool_usersuspension_suspensionsettings',
            get_string('suspensionsettings', 'tool_usersuspension'),
            get_string('suspensionsettingsdesc', 'tool_usersuspension')));
    $temp->add(new admin_setting_configcheckbox('tool_usersuspension/enabled',
            get_string('setting:enabled', 'tool_usersuspension'),
            get_string('setting:desc:enabled', 'tool_usersuspension'),
            '0', '1', '0'));

    $temp->add(new admin_setting_configcheckbox('tool_usersuspension/enablecleanlogs',
            get_string('setting:enablecleanlogs', 'tool_usersuspension'),
            get_string('setting:desc:enablecleanlogs', 'tool_usersuspension'),
            '1', '1', '0'));
    $temp->add(new admin_setting_configduration('tool_usersuspension/cleanlogsafter',
            get_string('setting:cleanlogsafter', 'tool_usersuspension'),
            get_string('setting:desc:cleanlogsafter', 'tool_usersuspension'),
            70 * 86400, 86400));

    $temp->add(new admin_setting_configcheckbox('tool_usersuspension/send_suspend_email',
            get_string('setting:sendsuspendemail', 'tool_usersuspension'),
            get_string('setting:desc:sendsuspendemail', 'tool_usersuspension'),
            '0', '1', '0'));

    // FTP settings.
    $temp->add(new admin_setting_heading('tool_usersuspension_suspensionsettingsfolder',
            get_string('suspensionsettingsfolder', 'tool_usersuspension'),
            get_string('suspensionsettingsfolderdesc', 'tool_usersuspension')));
    $temp->add(new admin_setting_configcheckbox('tool_usersuspension/enablefromfolder',
            get_string('setting:enablefromfolder', 'tool_usersuspension'),
            get_string('setting:desc:enablefromfolder', 'tool_usersuspension'),
            '0', '1', '0'));
    $temp->add(new admin_setting_configcheckbox('tool_usersuspension/enableunsuspendfromfolder',
            get_string('setting:enableunsuspendfromfolder', 'tool_usersuspension'),
            get_string('setting:desc:enableunsuspendfromfolder', 'tool_usersuspension'),
            '0', '1', '0'));
    // Upload folder.
    $temp->add(new admin_setting_configdirectory('tool_usersuspension/uploadfolder',
            get_string('setting:uploadfolder', 'tool_usersuspension'),
            get_string('setting:desc:uploadfolder', 'tool_usersuspension')
                . '<br/>' . get_string('form:static:uploadfile:desc', 'tool_usersuspension'),
            ''));
    $temp->add(new admin_setting_configtext('tool_usersuspension/uploadfilename',
            get_string('setting:uploadfilename', 'tool_usersuspension'),
            get_string('setting:desc:uploadfilename', 'tool_usersuspension'),
            ''));
    $temp->add(new admin_setting_configtext('tool_usersuspension/unsuspenduploadfilename',
            get_string('setting:unsuspenduploadfilename', 'tool_usersuspension'),
            get_string('setting:desc:unsuspenduploadfilename', 'tool_usersuspension'),
            ''));
    $temp->add(new admin_setting_configduration('tool_usersuspension/uploaddetect_interval',
            get_string('setting:uploaddetect_interval', 'tool_usersuspension'),
            get_string('setting:desc:uploaddetect_interval', 'tool_usersuspension'),
            3600, 3600));

    require_once($CFG->libdir . '/csvlib.class.php');
    $choices = csv_import_reader::get_delimiter_list();
    if (array_key_exists('cfg', $choices)) {
        $default = 'cfg';
    } else if (get_string('listsep', 'langconfig') == ';') {
        $default = 'semicolon';
    } else {
        $default = 'comma';
    }
    $temp->add(new admin_setting_configselect('tool_usersuspension/csvdelimiter',
            get_string('csvdelimiter', 'tool_usersuspension'),
            '', ';', $choices));

    // Example CSV.
    $urldownloadcsv = new \moodle_url($CFG->wwwroot . '/admin/tool/usersuspension/assets/example.csv');
    $temp->add(new admin_setting_description('tool_usersuspension/csvdelimiter',
            get_string('download-sample-csv', 'tool_usersuspension'),
            '<a href="' . $urldownloadcsv . '" target="_blank">' .
            get_string('download-sample-csv', 'tool_usersuspension') . '</a>'));

    // Upload settings.
    $temp->add(new admin_setting_heading('tool_usersuspension_suspensionsettingsupload',
            get_string('suspensionsettingsupload', 'tool_usersuspension'),
            get_string('suspensionsettingsuploaddesc', 'tool_usersuspension')));
    $temp->add(new admin_setting_configcheckbox('tool_usersuspension/enablefromupload',
            get_string('setting:enablefromupload', 'tool_usersuspension'),
            get_string('setting:desc:enablefromupload', 'tool_usersuspension'),
            '0', '1', '0'));

    // Smart-detect settings.
    $temp->add(new admin_setting_heading('tool_usersuspension_suspensionsettingssmartdetect',
            get_string('suspensionsettingssmartdetect', 'tool_usersuspension'),
            get_string('suspensionsettingssmartdetectdesc', 'tool_usersuspension')));
    $temp->add(new admin_setting_configcheckbox('tool_usersuspension/enablesmartdetect',
            get_string('setting:enablesmartdetect', 'tool_usersuspension'),
            get_string('setting:desc:enablesmartdetect', 'tool_usersuspension'),
            '1', '1', '0'));
    // Checking interval.
    $temp->add(new admin_setting_configduration('tool_usersuspension/smartdetect_interval',
            get_string('setting:smartdetect_interval', 'tool_usersuspension'),
            get_string('setting:desc:smartdetect_interval', 'tool_usersuspension'),
            43200, 3600));
    // Set suspended-after-inactive for XXX.
    $temp->add(new admin_setting_configduration('tool_usersuspension/smartdetect_suspendafter',
            get_string('setting:smartdetect_suspendafter', 'tool_usersuspension'),
            get_string('setting:desc:smartdetect_suspendafter', 'tool_usersuspension'),
            90 * 86400, 86400));

    // Cleanup settings.
    $temp->add(new admin_setting_heading('tool_usersuspension_suspensionsettingscleanup',
            get_string('suspensionsettingscleanup', 'tool_usersuspension'),
            get_string('suspensionsettingscleanupdesc', 'tool_usersuspension')));
    $temp->add(new admin_setting_configcheckbox('tool_usersuspension/enablecleanup',
            get_string('setting:enablecleanup', 'tool_usersuspension'),
            get_string('setting:desc:enablecleanup', 'tool_usersuspension'),
            '1', '1', '0'));
    $temp->add(new admin_setting_configcheckbox('tool_usersuspension/send_delete_email',
            get_string('setting:senddeleteemail', 'tool_usersuspension'),
            get_string('setting:desc:senddeleteemail', 'tool_usersuspension'),
            '0', '1', '0'));
    // Clean suspended users after XXX.
    $temp->add(new admin_setting_configduration('tool_usersuspension/cleanup_interval',
            get_string('setting:cleanup_interval', 'tool_usersuspension'),
            get_string('setting:desc:cleanup_interval', 'tool_usersuspension'),
            43200, 3600));
    $temp->add(new admin_setting_configduration('tool_usersuspension/cleanup_deleteafter',
            get_string('setting:cleanup_deleteafter', 'tool_usersuspension'),
            get_string('setting:desc:cleanup_deleteafter', 'tool_usersuspension'),
            60 * 86400, 86400));

    $ADMIN->add('tools', $temp);
}

$ADMIN->add('accounts', new admin_externalpage('toolusersuspension', get_string('pluginname', 'tool_usersuspension'),
    "{$CFG->wwwroot}/{$CFG->admin}/tool/usersuspension/view/exclude.php", 'moodle/user:update'
));