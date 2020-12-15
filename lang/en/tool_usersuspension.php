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
 * Language file for tool_usersuspension, EN
 *
 * File         tool_usersuspension.php
 * Encoding     UTF-8
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'User Suspension';

$string['promo'] = 'usersuspension plugin for Moodle';
$string['promodesc'] = 'This plugin is written by Sebsoft Managed Hosting & Software Development
    (<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';
$string['link:upload'] = 'Upload suspension file';
$string['link:viewstatus'] = 'View statuslist';
$string['link:exclude:overview'] = 'Exclusions overview';
$string['link:log:overview'] = 'View status change logs';
$string['link:currentstatus:overview'] = 'View current status changes';

$string['suspensionsettings'] = 'User Suspension Settings';
$string['suspensionsettingsdesc'] = '';
$string['setting:enabled'] = 'Enable';
$string['setting:desc:enabled'] = 'Enables or disables the user suspension utility';
$string['setting:enablecleanlogs'] = 'Enable logcleaning';
$string['setting:desc:enablecleanlogs'] = 'Enables or disables automatic cleaning of the history log.';
$string['setting:cleanlogsafter'] = 'Clean logs frequency';
$string['setting:desc:cleanlogsafter'] = 'Configure the frequency after which logs are cleaned. Any logs older than this setting will physically be removed.';
$string['config:cleanlogs:disabled'] = 'Automatic logcleaning is disabled in global configuration';
$string['suspensionsettingsfolder'] = 'Suspend from folder';
$string['suspensionsettingsfolderdesc'] = 'Configure the \'suspend from folder\' folder settings below.<br/>
Using these settings you can automate suspending users by uploading a CSV file to a random location on the server
(for example a dedicated FTP folder). The CSV file will be processed conforming to the settings below.
Note: The uploaded CSV file will be deleted after processing!';
$string['setting:enablefromfolder'] = 'Automatic suspension using stored CSV file';
$string['setting:desc:enablefromfolder'] = 'Enables or disables the user suspension utility to automatically suspend users from an uploaded CSV file';
$string['setting:uploadfolder'] = 'Upload folder';
$string['setting:desc:uploadfolder'] = 'Set folder where files should be uploaded through e.g. FTP';
$string['setting:uploadfilename'] = 'Suspend upload filename';
$string['setting:desc:uploadfilename'] = 'Set filename of the uploaded suspension file';
$string['setting:uploaddetect_interval'] = 'Upload folder processing interval';
$string['setting:desc:uploaddetect_interval'] = 'Set interval at which upload folder is checked for files';
$string['suspensionsettingsupload'] = 'Suspend from upload';
$string['suspensionsettingsuploaddesc'] = 'Configure the \'suspend from upload\' settings below';
$string['setting:enablefromupload'] = 'Enable from upload';
$string['setting:desc:enablefromupload'] = 'Enables or disables the user suspension utility from a file upload';
$string['suspensionsettingssmartdetect'] = 'Smart detection';
$string['suspensionsettingssmartdetectdesc'] = 'Configure the smart detection settings below.<br/>
Smart detection effectively means user accounts that have been found \'inactive\' according to the settings below will
get automatically suspended. Running only at a configured interval, \'smart detection\' will determine whether or not a user account
is active according to the configured \'Inactivity suspension interval\' setting, and suspend all user accounts deemed inactive.';
$string['setting:enablesmartdetect'] = 'Enable Smart Detection';
$string['setting:desc:enablesmartdetect'] = 'Enables or disables the user suspension utility\'s smart detection.';
$string['setting:smartdetect_interval'] = 'Smart detect interval';
$string['setting:desc:smartdetect_interval'] = 'Sets interval at which smart detection runs';
$string['setting:smartdetect_suspendafter'] = 'Inactivity suspension interval';
$string['setting:desc:smartdetect_suspendafter'] = 'Sets interval at which users are suspended when inactive';
$string['suspensionsettingscleanup'] = 'Cleanup';
$string['suspensionsettingscleanupdesc'] = 'Configure the cleanup settings below.<br/>
The cleanup process is here to further automate cleaning up users, meaning suspended user accounts will get deleted
when this option is used. If user accounts should be automatically deleted after a certain time you should configure these settings.
If automatic deletion of user accounts is by no means desired, you should turn off this option.';
$string['setting:enablecleanup'] = 'Enable Cleanup';
$string['setting:desc:enablecleanup'] = 'Enables or disables user cleanup';
$string['setting:cleanup_interval'] = 'Cleanup interval';
$string['setting:desc:cleanup_interval'] = 'Sets interval at which cleanup is performed';
$string['setting:cleanup_deleteafter'] = 'Deletion interval';
$string['setting:desc:cleanup_deleteafter'] = 'Sets interval at which users are deleted after suspension';
$string['setting:sendsuspendemail'] = 'Send suspension email?';
$string['setting:desc:sendsuspendemail'] = 'Send out an e-mail informing the user after being suspended?';
$string['setting:senddeleteemail'] = 'Send delete email?';
$string['setting:desc:senddeleteemail'] = 'Send out an e-mail informing the user after being deleted?';
$string['csv:delimiter'] = 'Delimiter';
$string['csv:enclosure'] = 'Enclosure';
$string['csv:upload:continue'] = 'Continue';

$string['page:view:statuslist.php:introduction:status'] = '<p>This overview shows the actively monitored users.<br/>
Actively monitored users are users that are actually monitored (meaning they\'re not configured to be excluded from monitoring).<br/>
This overview thus differs from the main administrator\'s overview that it will not show <i>any</i> users that have been excluded
from suspension monitoring using this tool\'s capabilities to exclude users and cohorts.</p>';
$string['page:view:statuslist.php:introduction:delete'] = '<p>This overview shows the user accounts that will get deleted within
the configured timeframe of this tool\'s settings</p>';
$string['page:view:statuslist.php:introduction:suspended'] = '<p>This overview shows the user accounts that have been suspended.</p>';
$string['page:view:statuslist.php:introduction:tosuspend'] = '<p>This overview shows the user accounts that will get suspended within
the configured timeframe of this tool\'s settings</p>';
$string['page:view:log.php:introduction'] = 'The table below shows the logs of statusses that users have been given or went through as
a result of automated processing or usage of this tool. It will show, depending on your configuration, the suspension status or deleted status
of your moodle users and the moment at which certain actions were performed.';
$string['page:view:exclude.php:introduction'] = '<p>This page displays the configured exclusions.<br/>
Exclusions are either users or cohorts that are completely excluded from any automated processing.<br/>
When a cohort is excluded, this means every user that\'s a member of the cohort will not be processed.
Use the options on this page to add cohorts or users to the exclusion list.</p>';
$string['config:tool:disabled'] = 'User Suspension Utility is disabled in global tool configuration';
$string['config:smartdetect:disabled'] = 'User Suspension option \'smart detection\' is disabled in global tool configuration';
$string['config:fromfolder:disabled'] = 'User Suspension option \'suspend from upload\' is disabled in global tool configuration';
$string['config:cleanup:disabled'] = 'User Suspension option \'cleanup\' is disabled in global tool configuration';
$string['err:statustable:set_sql'] = 'set_sql() is disabled. This table defines it\'s own and is not customomizable';
$string['notify:load-exclude-list'] = 'Loading user exclusion list';
$string['notify:load-file'] = 'Opening file \'{$a}\'';
$string['notify:load-file-fail'] = 'Could not open file \'{$a}\' for reading';
$string['notify:suspend-excluded-user'] = 'user: {$a->username} (id={$a->id}) is in the exclusion list: not suspending';
$string['notify:suspend-user'] = 'suspending user: {$a->username} (id={$a->id})';
$string['notify:unknown-suspend-type'] = 'Unknown suspension type identifier \'{$a}\'';
$string['action:delete-exclusion'] = 'Delete item from exclusionlist';
$string['action:confirm-delete-exclusion'] = 'Are you sure you want to delete this item from the exclusionlist?';
$string['info:no-exclusion-cohorts'] = 'No more cohorts can be configured to be excluded anymore. All are already added to the exclusion list';
$string['button:continue'] = 'Continue';
$string['action:exclude:add:cohort'] = 'Add cohort exclusion';
$string['action:exclude:add:user'] = 'Add user exclusion';
$string['label:users:excluded'] = 'Excluded users';
$string['label:users:potential'] = 'Potential users';
$string['status:suspended'] = 'suspended';
$string['status:unsuspended'] = 'unsuspended';
$string['status:deleted'] = 'deleted';
$string['table:status:status'] = 'Actively monitored users';
$string['table:status:suspended'] = 'Suspended users';
$string['table:status:tosuspend'] = 'Users to suspend';
$string['table:status:delete'] = 'Users to delete';
$string['excludeuser'] = 'exclude user from processing';

$string['email:user:suspend:subject'] = 'Your account has been suspended';
$string['email:user:suspend:auto:body'] = '<p>Dear {$a->name}</p>
<p>Your account has been suspended after not having been active for {$a->timeinactive}</p>
<p>If you feel this is unintended or want to have your account activated again,
please contact {$a->contact}</p>
<p>Regards<br/>{$a->signature}</p>';
$string['email:user:suspend:manual:body'] = '<p>Dear {$a->name}</p>
<p>Your account has been suspended.</p>
<p>If you feel this is unintended or want to have your account activated again,
please contact {$a->contact}</p>
<p>Regards<br/>{$a->signature}</p>';
$string['email:user:unsuspend:subject'] = 'Your account has been reactivated';
$string['email:user:unsuspend:body'] = '<p>Dear {$a->name}</p>
<p>You account has been reactivated.</p>
<p>If you feel this is unintended or want to have your account suspended again,
please contact {$a->contact}</p>
<p>Regards<br/>{$a->signature}</p>';
$string['email:user:delete:subject'] = 'Your account has been removed';
$string['email:user:delete:body'] = '<p>Dear {$a->name}</p>
<p>You account has been removed after having been suspended for {$a->timesuspended}</p>
<p>Regards<br/>{$a->signature}</p>';
$string['form:static:uploadfile:desc'] = 'Upload your user suspension file here<br/>
The uploaded CSV file can be configured as follows:<br/>
<ol>
<li>\'simple\' file containing ONLY email addresses, one per line</li>
<li>\'smart\' file containing 2 columns, indicating the type and the value.<br/>
Possible values for the type column are
<ul><li>email: value column indicates user account\'s e-mail address</li>
<li>idnumber: value column indicates user account\'s idnumber</li>
<li>username: value column indicates user account\'s username</li>
</ul></ol>';
$string['msg:exclusion:cohort:none-selected'] = 'No cohorts were selected for exclusion';
$string['msg:exclusion:records:user:deleted'] = 'Exclusion entries for users successfully deleted';
$string['msg:exclusion:record:user:inserted'] = 'Exclusion entry for user \'{$a->fullname}\' successfully inserted';
$string['msg:exclusion:record:user:deleted'] = 'Exclusion entry for user \'{$a->fullname}\' successfully removed';
$string['msg:exclusion:records:cohort:deleted'] = 'Exclusion entries for cohorts successfully deleted';
$string['msg:exclusion:record:cohort:inserted'] = 'Exclusion entry for cohort \'{$a->name}\' successfully inserted';
$string['msg:exclusion:records:deleted'] = 'Exclusion entries successfully deleted';
$string['msg:exclusion:record:inserted'] = 'Exclusion entry successfully inserted';
$string['msg:exclusion:record:exists'] = 'Exclusion entry already exists (no record added)';
$string['msg:file:upload:fail'] = 'Uploaded file could not successfully be saved. Processing aborted.';
$string['msg:user:suspend:success'] = 'User \'{$a->username}\' successfully suspended';
$string['msg:user:suspend:failed'] = 'User \'{$a->username}\' could not be suspended';
$string['msg:user:suspend:nosuspendmode'] = 'User \'{$a->username}\' was not suspended (running in test mode)';
$string['msg:user:unsuspend:success'] = 'User \'{$a->username}\' successfully unsuspended';
$string['msg:user:unsuspend:failed'] = 'User \'{$a->username}\' could not be unsuspended';
$string['msg:user:unsuspend:nounsuspendmode'] = 'User \'{$a->username}\' was not unsuspended (running in test mode)';
$string['msg:user:not-found'] = 'user could not be found';
$string['msg:file-not-readable'] = 'Uploaded file \'{$a}\' is not readable';
$string['msg:file-not-writeable'] = 'Uploaded file \'{$a}\' is not writeable or can\'t be removed';
$string['button:backtocourse'] = 'Back to course';
$string['button:backtouploadform'] = 'Back to upload form';
$string['button:backtoexclusions'] = 'Back to exclusions overview';
$string['table:exclusions'] = 'Exclusions';
$string['table:logs'] = 'Logs';
$string['table:log:all'] = 'Historic suspensionlog';
$string['table:log:latest'] = 'Latest suspension logs';
$string['task:mark'] = 'Usersuspension task: automatic suspension of users';
$string['task:fromfolder'] = 'Usersuspension task: automatic user suspension from uploaded file';
$string['task:delete'] = 'Usersuspension task: automatic removal of suspended users';
$string['task:logclean'] = 'Clean logs for user suspension';
$string['thead:type'] = 'Type';
$string['thead:name'] = 'Name';
$string['thead:timecreated'] = 'Time created';
$string['thead:action'] = 'Action(s)';
$string['thead:userid'] = 'User ID';
$string['thead:status'] = 'Status';
$string['thead:mailsent'] = 'E-mail sent';
$string['thead:mailedto'] = 'E-mailed to';
$string['thead:username'] = 'Username';
$string['thead:lastlogin'] = 'Last login';
$string['thead:timemodified'] = 'Time modified';

$string['privacy:metadata:tool_usersuspension:type'] = 'Schorsings exclusion type (altijd \'user\').';
$string['privacy:metadata:tool_usersuspension:userid'] = 'The primary key of the Moodle user for which account has been restored.';
$string['privacy:metadata:tool_usersuspension:status'] = 'Suspensionstatus';
$string['privacy:metadata:tool_usersuspension:mailsent'] = 'Whether or not an email has been sent';
$string['privacy:metadata:tool_usersuspension:mailedto'] = 'E-mail address of the restored user';
$string['privacy:metadata:tool_usersuspension:timecreated'] = 'Time the record was created.';
$string['privacy:metadata:tool_usersuspension_excl'] = 'The usersuspension exclusions store users excluded from automated suspension';
$string['privacy:metadata:tool_usersuspension_status'] = 'The usersuspension status stores information about suspended users';
$string['privacy:metadata:tool_usersuspension_log'] = 'The usersuspension status stores historical/log information about suspended users';

$string['csvdelimiter'] = 'CSV delimiter';
$string['csvencoding'] = 'CSV encoding';
$string['task:unsuspendfromfolder'] = 'Usersuspension task: automatic user activation (unsuspend) from uploaded file';
$string['suspendmode'] = 'Processing mode';
$string['suspend'] = 'Suspend';
$string['unsuspend'] = 'Unsuspend';
$string['download-sample-csv'] = 'Download sample CSV file';
$string['config:unsuspendfromfolder:disabled'] = 'User Suspension option \'unsuspend from upload\' is disabled in global tool configuration';
$string['setting:enableunsuspendfromfolder'] = 'Automatic unsuspension using stored CSV file';
$string['setting:desc:enableunsuspendfromfolder'] = 'Enables or disables the user unsuspension utility to automatically unsuspend users from an uploaded CSV file';
$string['setting:unsuspenduploadfilename'] = 'Unsuspend upload filename';
$string['setting:desc:unsuspenduploadfilename'] = 'Set filename of the uploaded unsuspension file';
$string['page:view:notifications.php:introduction'] = 'This tab displays any detected potential problems with your user suspension configuration.';
$string['tab:notifications'] = 'Configuration check';
$string['notifications:allok'] = 'Your configuration looks correct. There seem to be no detected global configuration issues.';
