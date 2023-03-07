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
 *
 *
 * @package    local_mytutors
 * @author     Tristan daCosta
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $DB;

if ($hassiteconfig) {
    if ($ADMIN->fulltree) {

        $settings = new admin_settingpage(
            'mytutors',
            new lang_string('pluginname', 'local_mytutors')
        );

        $name = 'local_mytutors/roles';
        $title = get_string('roles', 'local_mytutors');
        $description = get_string('roles_desc', 'local_mytutors');
        $getallroles = $DB->get_records_sql('SELECT r.id,r.shortname,r.name FROM {role} r JOIN {role_context_levels} rcl ON rcl.roleid = r.id WHERE rcl.contextlevel = 50 ORDER BY id');
        $allroles = role_fix_names($getallroles, null, ROLENAME_ORIGINALANDSHORT, true);
        $setting = new admin_setting_configmultiselect('local_mytutors/courseroles', get_string('roles', 'local_mytutors'), get_string('roles_desc', 'local_mytutors'), array('3', '4'), $allroles);
        $settings->add($setting);

        /** @var admin_root $ADMIN */
        $ADMIN->add('localplugins', $settings);

    }
}
