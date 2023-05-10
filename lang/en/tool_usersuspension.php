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

$string['promo'] = 'User suspension plugin for Moodle';
$string['promodesc'] = 'This plugin is written by Sebsoft Managed Hosting & Software Development
    (<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://sebsoft.nl</a>).<br /><br />
    {$a}<br /><br />';
$string['link:upload'] = 'Upload file with user suspensions';
$string['link:viewstatus'] = 'View list of statuses';
$string['link:exclude:overview'] = 'Exclusions overview';
$string['link:log:overview'] = 'View status change logs';
$string['link:currentstatus:overview'] = 'View current status changes';

$string['suspensionsettings'] = 'User Suspension Settings';
$string['suspensionsettingsdesc'] = '';
$string['setting:enabled'] = 'Enable';
$string['setting:desc:enabled'] = 'Enables or disables the suspending of users';
$string['setting:enablecleanlogs'] = 'Enable log cleaning';
$string['setting:desc:enablecleanlogs'] = 'Enables or disables automatic cleaning of the history log.';
$string['setting:cleanlogsafter'] = 'Frequency of log cleaning';
$string['setting:desc:cleanlogsafter'] = 'Configure how often the logs should be cleaned. Any logs older than this setting will be removed.';
$string['config:cleanlogs:disabled'] = 'Automatic log cleaning is disabled in the global configuration';
$string['suspensionsettingsfolder'] = 'Suspend using files from upload folder';
$string['suspensionsettingsfolderdesc'] = 'Configure the settings for the \'suspend using files from upload folder\' folder below.<br/>
Using these settings you can automate suspending users by uploading a CSV file to a random location on the server
(for example a dedicated FTP folder). The CSV file will be processed according to the settings below.
Note: The uploaded CSV file will be deleted after processing!';
$string['setting:enablefromfolder'] = 'Automatic suspension using stored CSV file';
$string['setting:desc:enablefromfolder'] = 'Enables or disables the automatic suspension of users from an uploaded CSV file stored in the upload folder';
$string['setting:uploadfolder'] = 'Location of file upload folder';
$string['setting:desc:uploadfolder'] = 'Set folder where files should be uploaded through e.g. FTP';
$string['setting:uploadfilename'] = 'Filename of uploaded suspension file';
$string['setting:desc:uploadfilename'] = 'Set filename of the uploaded file, containing user suspensions';
$string['setting:uploaddetect_interval'] = 'File upload folder processing interval';
$string['setting:desc:uploaddetect_interval'] = 'Set interval at which the file upload folder is checked for files';
$string['suspensionsettingsupload'] = 'Suspend from uploaded file';
$string['suspensionsettingsuploaddesc'] = 'Configure the \'suspend from uploaded file\' settings below';
$string['setting:enablefromupload'] = 'Enable suspension of users from uploaded file';
$string['setting:desc:enablefromupload'] = 'Enables or disables user suspension from an uploaded file';
$string['suspensionsettingssmartdetect'] = 'Smart detection';
$string['suspensionsettingssmartdetectdesc'] = 'Configure the smart detection settings below.<br/>
Smart detection will automatically suspend user accounts that have been found to be \'inactive\' according to the settings below. 
Running only at a configured interval, \'smart detection\' will determine whether or not a user account
is active according to the configured \'Inactivity suspension time threshold\' setting, and will suspend all user accounts found to be inactive.';
$string['setting:enablesmartdetect'] = 'Enable Smart Detection';
$string['setting:desc:enablesmartdetect'] = 'Enables or disables the smart detection functionality.';
$string['setting:smartdetect_interval'] = 'Smart detect interval';
$string['setting:desc:smartdetect_interval'] = 'Sets time between smart detection runs';
$string['setting:smartdetect_suspendafter'] = 'Inactivity suspension time threshold';
$string['setting:desc:smartdetect_suspendafter'] = 'Sets inactivity time threshold at which inactive users are suspended';
$string['setting:enablesmartdetectwarning'] = 'Send warning e-mails about impending suspension?';
$string['setting:desc:enablesmartdetectwarning'] = 'When enabled, this will warn users about their impending suspension by sending them an e-mail.';
$string['setting:smartdetect_warninginterval'] = 'Warning period';
$string['setting:desc:smartdetect_warninginterval'] = 'Sets how long before suspension a user should be warned about the impending suspension with a warning message .';
$string['suspensionsettingscleanup'] = 'Cleanup';
$string['suspensionsettingscleanupdesc'] = 'Configure the cleanup settings below.<br/>
The cleanup process further automates user cleanup, meaning suspended user accounts will get deleted
when this option is used. If user accounts should be automatically deleted after a certain time you should configure these settings.
If automatic deletion of user accounts should not happen, this option should be turned off.';
$string['setting:enablecleanup'] = 'Enable Cleanup';
$string['setting:desc:enablecleanup'] = 'Enables or disables cleanup of users';
$string['setting:cleanup_interval'] = 'Cleanup interval';
$string['setting:desc:cleanup_interval'] = 'Sets interval at which cleanup is performed';
$string['setting:cleanup_deleteafter'] = 'Deletion interval';
$string['setting:desc:cleanup_deleteafter'] = 'Sets how long after their suspension users should automatically get deleted';
$string['setting:sendsuspendemail'] = 'Send suspension email?';
$string['setting:desc:sendsuspendemail'] = 'Send out an e-mail informing the user about their account being suspended?';
$string['setting:senddeleteemail'] = 'Send delete email?';
$string['setting:desc:senddeleteemail'] = 'Send out an e-mail informing the user about their account being deleted?';
$string['csv:delimiter'] = 'Delimiter';
$string['csv:enclosure'] = 'Enclosure';
$string['csv:upload:continue'] = 'Continue';

$string['page:view:statuslist.php:introduction:status'] = '<p>This overview shows users that are actively monitored.<br/>
Actively monitored users are users, that are not configured to be excluded from monitoring.<br/>
This overview differs from the main administrator\'s overview in that it will <i>not show</i> users that have been excluded
from suspension monitoring using this tool\'s exclusion functionality.</p>';
$string['page:view:statuslist.php:introduction:delete'] = '<p>This overview shows user accounts that will get deleted within
the configured timeframe in this tool\'s settings</p>';
$string['page:view:statuslist.php:introduction:suspended'] = '<p>This overview shows the user accounts that have been suspended.</p>';
$string['page:view:statuslist.php:introduction:tosuspend'] = '<p>This overview shows the user accounts that will get suspended within
the configured timeframe of this tool\'s settings</p>';
$string['page:view:log.php:introduction'] = 'The table below shows the logs of statuses that users had assigned, either by automated processing or usage of 
this tool. The table will show, depending on your configuration, the suspension status or deleted status of your users and 
the timestamp at which certain actions were performed.';
$string['page:view:exclude.php:introduction'] = '<p>This page displays the configured exclusions.<br/>
Exclusions are either users or cohorts that are completely excluded from any automated processing.<br/>
When a cohort is excluded, this means every user that\'s a member of the cohort will be excluded.
Use the options on this page to add cohorts or users to the exclusion list.</p>';
$string['config:tool:disabled'] = 'Plugin functionality is disabled in global tool configuration';
$string['config:smartdetect:disabled'] = 'Plugin option \'smart detection\' is disabled in global tool configuration';
$string['config:fromfolder:disabled'] = 'Plugin option \'suspend from upload folder\' is disabled in global tool configuration';
$string['config:cleanup:disabled'] = 'Plugin option \'cleanup\' is disabled in global tool configuration';
$string['configoption:notactive'] = 'Despite the overview below, the settings dictate the actual process is <i>not</i> enforced.';
$string['err:statustable:set_sql'] = 'set_sql() is disabled. This table defines it\'s own and is not customomizable';
$string['notify:load-exclude-list'] = 'Loading user exclusion list';
$string['notify:load-file'] = 'Opening file \'{$a}\'';
$string['notify:load-file-fail'] = 'Could not open file \'{$a}\' for reading';
$string['notify:suspend-excluded-user'] = 'user: {$a->username} (id={$a->id}) is in the exclusion list: not suspending';
$string['notify:suspend-user'] = 'suspending user: {$a->username} (id={$a->id})';
$string['notify:unknown-suspend-type'] = 'Unknown suspension type identifier \'{$a}\'';
$string['action:delete-exclusion'] = 'Delete item from exclusionlist';
$string['action:confirm-delete-exclusion'] = 'Are you sure you want to delete this item from the exclusion list?';
$string['info:no-exclusion-cohorts'] = 'All cohorts have already been added to the exclusion list. No more can be added.';
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
<p>Your account has been suspended after {$a->timeinactive} of inactivity.</p>
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
<p>You account has been removed after being suspended for {$a->timesuspended}</p>
<p>Regards<br/>{$a->signature}</p>';
$string['email:user:warning:subject'] = 'Your account will soon be suspended';
$string['email:user:warning:body'] = '<p>Dear {$a->name}</p>
<p>Your account will be suspended in {$a->warningperiod} due to inactivity on the platform.</p>
<p>You must login within the next {$a->warningperiod} to keep your account active.
To prevent this from occuring in the future, please ensure you log into the system at least once every {$a->suspendinterval}.</p>
<p>Regards<br/>{$a->signature}</p>';
$string['form:static:uploadfile:desc'] = 'Upload your file with user suspensions here<br/>
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
$string['msg:user:not-found'] = 'User could not be found';
$string['msg:file-not-readable'] = 'Uploaded file \'{$a}\' is not readable';
$string['msg:file-not-writeable'] = 'Uploaded file \'{$a}\' is not writeable or can\'t be removed';
$string['button:backtocourse'] = 'Back to course';
$string['button:backtouploadform'] = 'Back to upload form';
$string['button:backtoexclusions'] = 'Back to exclusions overview';
$string['table:exclusions'] = 'Exclusions';
$string['table:logs'] = 'Logs';
$string['table:log:all'] = 'Historic suspension log';
$string['table:log:latest'] = 'Latest suspension logs';
$string['task:mark'] = 'Usersuspension task: automatic suspension of users';
$string['task:fromfolder'] = 'Usersuspension task: automatic suspension of users from uploaded file stored in the upload folder';
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
$string['thead:deletein'] = 'Delete in';
$string['thead:suspendin'] = 'Suspend in';
$string['thead:timedetect'] = 'Detection based on';
$string['deleteon'] = 'Delete on';
$string['suspendon'] = 'Suspend on';

$string['privacy:metadata:tool_usersuspension:type'] = 'Schorsings exclusion type (altijd \'user\').';
$string['privacy:metadata:tool_usersuspension:userid'] = 'The primary key of the Moodle user for which account has been restored.';
$string['privacy:metadata:tool_usersuspension:status'] = 'Suspension status';
$string['privacy:metadata:tool_usersuspension:mailsent'] = 'Whether or not an email has been sent';
$string['privacy:metadata:tool_usersuspension:mailedto'] = 'E-mail address of the restored user';
$string['privacy:metadata:tool_usersuspension:timecreated'] = 'Time the record was created.';
$string['privacy:metadata:tool_usersuspension_excl'] = 'The usersuspension exclusions store users excluded from automated suspension';
$string['privacy:metadata:tool_usersuspension_status'] = 'The usersuspension status stores information about suspended users';
$string['privacy:metadata:tool_usersuspension_log'] = 'The usersuspension status stores historical/log information about suspended users';

$string['csvdelimiter'] = 'CSV delimiter';
$string['csvencoding'] = 'CSV encoding';
$string['task:unsuspendfromfolder'] = 'Usersuspension task: automatic user activation (unsuspend) from uploaded file stored in the upload folder';
$string['suspendmode'] = 'Processing mode';
$string['suspend'] = 'Suspend';
$string['unsuspend'] = 'Unsuspend';
$string['download-sample-csv'] = 'Download sample CSV file';
$string['config:unsuspendfromfolder:disabled'] = 'Plugin option \'unsuspend from upload folder\' is disabled in global tool configuration';
$string['setting:enableunsuspendfromfolder'] = 'Automatic unsuspension of users using stored CSV file';
$string['setting:desc:enableunsuspendfromfolder'] = 'Enables or disables the automatic reactivation of users from an uploaded CSV file';
$string['setting:unsuspenduploadfilename'] = 'Filename of the uploaded unsuspension file';
$string['setting:desc:unsuspenduploadfilename'] = 'Set filename of the uploaded unsuspension file';
$string['page:view:notifications.php:introduction'] = 'This tab displays any detected potential problems with your user suspension configuration.';
$string['tab:notifications'] = 'Configuration check';
$string['notifications:allok'] = 'Your configuration looks correct. There seem to be no detected global configuration issues.';
$string['testfromfolder'] = 'Test unattended processing';
$string['testfromfolder:suspend'] = 'Test unattended suspending of users (from folder)';
$string['testfromfolder:unsuspend'] = 'Test unattended unsuspending of users (from folder)';
$string['config:tool:enabled'] = 'Plugin functionality is enabled in global tool configuration';
$string['config:fromfolder:enabled'] = 'Plugin option \'suspend from upload folder\' is enabled in global tool configuration';
$string['config:unsuspendfromfolder:enabled'] = 'Plugin option \'unsuspend from upload folder\' is enabled in global tool configuration';
$string['config:uploadfolder:not-exists'] = 'Upload folder "{$a}" does not exist';
$string['config:uploadfolder:exists'] = 'Upload folder "{$a}" exists';
$string['config:uploadfile:not-exists'] = 'Upload file "{$a}" does not exist';
$string['config:uploadfile:exists'] = 'Upload file "{$a}" exists';
$string['msg:file-would-delete'] = 'Uploaded file would now be deleted (if this weren\'t a configuration validation)';
$string['testing:suspendfromfolder'] = 'Testing configuration for "suspend from folder"';
$string['testing:unsuspendfromfolder'] = 'Testing configuration for "unsuspend from folder"';

// Access strings.
$string['usersuspension:administration'] = 'User suspension administration';
$string['usersuspension:viewstatus'] = 'View user suspension status';

// Upload detection is deprecated; this is the new description.
$string['setting:dep:uploaddetect_interval'] = 'Upload folder processing interval';
$string['setting:dep:desc:uploaddetect_interval'] = 'To change the interval at which the upload folder is checked and processed,
 please adjust the interval at which the dedicated scheduled task runs to facilitate this proces <a href="{$a}/admin/tool/task/scheduledtasks.php">here</a>
 (look for tasks "\tool_usersuspension\task\suspend\fromfolder" and "\tool_usersuspension\task\unsuspend\fromfolder").';
$string['event:user:suspended'] = 'User suspended.';
