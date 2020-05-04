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
 * Privacy provider.
 *
 * File         provider.php
 * Encoding     UTF-8
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_usersuspension\privacy;

defined('MOODLE_INTERNAL') || die;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\transform;
use core_privacy\local\request\writer;
use core_privacy\local\request\userlist;
use core_privacy\local\request\approved_userlist;

/**
 * Privacy provider.
 *
 * @package     tool_usersuspension
 *
 * @copyright   Sebsoft.nl
 * @author      R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
        \core_privacy\local\metadata\provider,
        \core_privacy\local\request\plugin\provider,
        \core_privacy\local\request\core_userlist_provider {

    /**
     * Provides meta data that is stored about a user with tool_usersuspension
     *
     * @param  collection $collection A collection of meta data items to be added to.
     * @return  collection Returns the collection of metadata.
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_database_table(
            'tool_usersuspension_excl',
            [
                'type' => 'privacy:metadata:tool_usersuspension:type',
                'refid' => 'privacy:metadata:tool_usersuspension:userid',
                'timecreated' => 'privacy:metadata:tool_usersuspension:timecreated',
            ],
            'privacy:metadata:tool_usersuspension_excl'
        );
        $collection->add_database_table(
            'tool_usersuspension_status',
            [
                'userid' => 'privacy:metadata:tool_usersuspension:userid',
                'status' => 'privacy:metadata:tool_usersuspension:status',
                'mailsent' => 'privacy:metadata:tool_usersuspension:mailsent',
                'mailedto' => 'privacy:metadata:tool_usersuspension:mailedto',
                'timecreated' => 'privacy:metadata:tool_usersuspension:timecreated',
            ],
            'privacy:metadata:tool_usersuspension_status'
        );
        $collection->add_database_table(
            'tool_usersuspension_log',
            [
                'userid' => 'privacy:metadata:tool_usersuspension:userid',
                'status' => 'privacy:metadata:tool_usersuspension:status',
                'mailsent' => 'privacy:metadata:tool_usersuspension:mailsent',
                'mailedto' => 'privacy:metadata:tool_usersuspension:mailedto',
                'timecreated' => 'privacy:metadata:tool_usersuspension:timecreated',
            ],
            'privacy:metadata:tool_usersuspension_log'
        );
        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param   int           $userid       The user to search.
     * @return  contextlist   $contextlist  The list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        $contextlist = new \core_privacy\local\request\contextlist();
        // Since this system works on a global level (it hooks into the authentication system), the only context is CONTEXT_SYSTEM.
        $contextlist->add_system_context();
        return $contextlist;
    }

    /**
     * Export all user data for the specified user, in the specified contexts, using the supplied exporter instance.
     *
     * @param   approved_contextlist    $contextlist    The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;
        if (empty($contextlist->count())) {
            return;
        }
        $user = $contextlist->get_user();

        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel != CONTEXT_SYSTEM) {
                continue;
            }
            $contextid = $context->id;
            // Add suspension status records.
            $sql = "SELECT ss.* FROM {tool_usersuspension_status} ss WHERE ss.userid = :userid";
            $params = ['userid' => $user->id];
            $alldata = [];
            $statuses = $DB->get_recordset_sql($sql, $params);
            foreach ($statuses as $status) {
                $alldata[$contextid][] = (object)[
                        'userid' => $status->userid,
                        'restored' => transform::yesno($status->restored),
                        'mailsent' => transform::yesno($status->mailsent),
                        'mailedto' => $status->mailedto,
                        'timecreated' => transform::datetime($status->timecreated),
                    ];
            }
            $statuses->close();

            // The data is organised in: {? }/hammering.json.
            // where X is the attempt number.
            array_walk($alldata, function($statusdata, $contextid) {
                $context = \context::instance_by_id($contextid);
                writer::with_context($context)->export_related_data(
                    ['tool_usersuspension'],
                    'statuses',
                    (object)['status' => $statusdata]
                );
            });

            // Add suspension log records.
            $sql = "SELECT ul.* FROM {tool_usersuspension_log} ul WHERE ul.userid = :userid";
            $params = ['userid' => $user->id];
            $alldata = [];
            $statuslogs = $DB->get_recordset_sql($sql, $params);
            foreach ($statuslogs as $statuslog) {
                $alldata[$contextid][] = (object)[
                        'userid' => $statuslog->userid,
                        'mailedto' => $statuslog->mailedto,
                        'timecreated' => transform::datetime($statuslog->timecreated),
                    ];
            }
            $statuslogs->close();

            // The data is organised in: {?}/hammerlogs.json.
            // where X is the attempt number.
            array_walk($alldata, function($statuslog, $contextid) {
                $context = \context::instance_by_id($contextid);
                writer::with_context($context)->export_related_data(
                    ['tool_usersuspension'],
                    'statuslogs',
                    (object)['statuslog' => $statuslog]
                );
            });

            // Add suspension exception records.
            $sql = "SELECT ue.* FROM {tool_usersuspension_excl} ue WHERE ue.refid = :userid AND ue.type = :type";
            $params = ['userid' => $user->id, 'type' => 'user'];
            $alldata = [];
            $exclusions = $DB->get_recordset_sql($sql, $params);
            foreach ($exclusions as $exclusion) {
                $alldata[$contextid][] = (object)[
                        'userid' => $exclusion->refid,
                        'type' => $exclusion->type,
                        'timecreated' => transform::datetime($exclusion->timecreated),
                    ];
            }
            $exclusions->close();

            // The data is organised in: {?}/hammerlogs.json.
            // where X is the attempt number.
            array_walk($alldata, function($exclusion, $contextid) {
                $context = \context::instance_by_id($contextid);
                writer::with_context($context)->export_related_data(
                    ['tool_usersuspension'],
                    'exclusions',
                    (object)['exclusion' => $exclusion]
                );
            });
        }
    }

    /**
     * Delete all use data which matches the specified context.
     *
     * @param context $context The module context.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return;
        }

        // Delete exclusion records.
        $DB->delete_records('tool_usersuspension_excl');
        // Delete status records.
        $DB->delete_records('tool_usersuspension_status');
        // Delete log records.
        $DB->delete_records('tool_usersuspension_log');
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel != CONTEXT_SYSTEM) {
                continue;
            }

            $user = $contextlist->get_user();
            // Delete exclusion records.
            $DB->delete_records('tool_usersuspension_excl', ['type' => 'user', 'refid' => $user->id]);
            // Delete status records.
            $DB->delete_records('tool_usersuspension_status', ['userid' => $user->id]);
            // Delete log records.
            $DB->delete_records('tool_usersuspension_log', ['userid' => $user->id]);
        }
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        global $DB;
        $context = $userlist->get_context();
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return;
        }
        // I'm unsure if we should also include the course contexts.
        // I'm also unsure if we should include the cohort linked contexts.
        // If we should, we'll implement those too.
        // For now, include "all".
        $userids1 = $DB->get_fieldset_sql('SELECT DISTINCT refid FROM {tool_usersuspension_excl} WHERE type = ?', ['user']);
        $userids2 = $DB->get_fieldset_sql('SELECT DISTINCT userid FROM {tool_usersuspension_status}');
        $userids3 = $DB->get_fieldset_sql('SELECT DISTINCT userid FROM {tool_usersuspension_log}');
        $userids = array_unique(array_merge($userids1, $userids2, $userids3));
        $userlist->add_users($userids);
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param  approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;
        $context = $userlist->get_context();
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return;
        }

        foreach ($userlist->get_userids() as $userid) {
            $DB->delete_records('tool_usersuspension_excl', ['type' => 'user', 'refid' => $userid]);
            $DB->delete_records('tool_usersuspension_status', ['userid' => $userid]);
            $DB->delete_records('tool_usersuspension_log', ['userid' => $userid]);
        }
    }

}
