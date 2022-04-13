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

//SELECT MIN(x.CombinedDate) AS least, MAX(x.CombinedDate) AS greatest
//FROM   dbo.Users AS u
//CROSS APPLY ( VALUES ( u.CreationDate ), ( u.LastAccessDate )) AS x ( CombinedDate );
require_once(dirname(__FILE__) . '/../../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('toolusersuspension');
$context       = \context_system::instance();

$history = optional_param('history', 0, PARAM_INT);
$thispageurl = new moodle_url('/' . $CFG->admin . '/tool/usersuspension/test/sqlserver.php', array('history' => $history));

require_capability('tool/usersuspension:viewstatus', $context);


echo $OUTPUT->header();
echo '<pre>';
print_r($DB->get_records('user', []));
$r = $DB->get_records_sql('SELECT u.id, MAX(x.CombinedDate) AS greatest
FROM {user} u
CROSS APPLY ( VALUES (u.firstaccess), (u.lastaccess), (u.timemodified)) AS x ( CombinedDate ) GROUP BY u.id');
print_r($r);

$r = $DB->get_records_sql('SELECT u.id, u.firstaccess, u.lastaccess, u.timemodified, IIF(u.lastaccess >= u.firstaccess, IIF(u.timemodified >= u.lastaccess, u.timemodified, u.lastaccess), u.firstaccess) AS greatest
FROM {user} u');
print_r($r);
print_r($DB);
echo '</pre>';
echo $OUTPUT->footer();
