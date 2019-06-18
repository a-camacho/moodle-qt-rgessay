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
 * RG Essay question type upgrade code.
 *
 * @package    qtype
 * @subpackage rgessay
 * @copyright  2019 André Camacho
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade code for the essay question type.
 *
 * @param int $oldversion the version we are upgrading from.
 * @return bool
 *
 * @throws ddl_exception
 * @throws ddl_table_missing_exception
 * @throws downgrade_exception
 * @throws upgrade_exception
 */
function xmldb_qtype_rgessay_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    // Automatically generated Moodle v3.3.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v3.4.0 release upgrade line.
    // Put any upgrade step following this.

    if ($oldversion < 2019061801) {

        // Add "qtype_rgessay_rubric_fillings" table.
        $table = new xmldb_table('qtype_rgessay_rub_fillings');

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $fields = array();

        $field_id = new xmldb_field('id', XMLDB_TYPE_INTEGER, null, null, null, null, null, null);
        array_push($fields, $field_id);

        $field_instanceid = new xmldb_field('instanceid', XMLDB_TYPE_INTEGER, null, null, null, null, null, 'id');
        array_push($fields, $field_instanceid);

        $field_criterionid = new xmldb_field('criterionid', XMLDB_TYPE_INTEGER, null, null, null, null, null, 'instanceid');
        array_push($fields, $field_criterionid);

        $field_levelid = new xmldb_field('levelid', XMLDB_TYPE_INTEGER, null, null, null, null, null, 'criterionid');
        array_push($fields, $field_levelid);

        $field_remark = new xmldb_field('remark', XMLDB_TYPE_TEXT, null, null, null, null, null, 'levelid');
        array_push($fields, $field_remark);

        $field_remarkformat = new xmldb_field('remarkformat', XMLDB_TYPE_INTEGER, null, null, null, null, null, 'remark');
        array_push($fields, $field_remarkformat);

        // Conditionally launch add field for all fields.
        foreach ( $fields as $field ) {

            if (!$dbman->field_exists($table, $field)) {
                $dbman->add_field($table, $field);
            }

        }

        // Essay savepoint reached.
        upgrade_plugin_savepoint(true, 2019061801, 'qtype', 'rgessay');

    }

    if ($oldversion < 2019060304) {

        // Add "filetypeslist" column to the question type options to save the allowed file types.
        $table = new xmldb_table('qtype_rgessay_options');
        $field = new xmldb_field('rubricid', XMLDB_TYPE_INTEGER, 10, null, null, null, null, 'filetypeslist');

        // Conditionally launch add field filetypeslist.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Essay savepoint reached.
        upgrade_plugin_savepoint(true, 2019060304, 'qtype', 'rgessay');
    }

    // Automatically generated Moodle v3.5.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v3.6.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v3.7.0 release upgrade line.
    // Put any upgrade step following this.

    return true;
}
