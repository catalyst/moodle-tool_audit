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
 * Functions used by the audit tool.
 *
 * @package     tool_audit
 * @author      Marcus Boon<marcus@catalyst-au.net>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * On install or update of the plugin, make sure we include
 * the config defined webservice user.
 *
 * @return bool
 */
function tool_audit_upsert_webservice_user() {
    global $CFG, $DB;

    // Check to see if we've set up the audit user details in config.
    if (!property_exists($CFG->audituser) && empty($CFG->audituser)) {

        return false;
    }

    if (!property_exists($CFG->audittoken) && empty($CFG->audittoken)) {

        return false;
    }

    // Check to see that we've enabled webservices.
    if (!$CFG->enablewebservices) {

        return false;
    }

    // See if user exists, or create the user.
    $user = $DB->get_record('user', array('username' => $CFG->audituser));

    if (empty($user)) {

        $usernew = new stdClass();
        $usernew->auth      = 'manual';
        $usernew->confirmed = 1;
        $usernew->deleted   = 0;
        $usernew->timezone  = '99';
        $usernew->firstname = 'Audit Tool';
        $usernew->lastname  = 'Webservice User';
        $usernew->email     = 'audit_tool@invalid';

        user_create_user($usernew, false, false);
    } else {

        $user->firstname = 'Audit Tool';
        $user->lastname  = 'Webservice User';
        $user->email     = 'audit_tool@invalid';

        user_update_user($user, false, false);
    }

    return true;
}
