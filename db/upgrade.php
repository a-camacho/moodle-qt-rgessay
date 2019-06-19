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

    if ($oldversion < 2019061901) {

        if (!$dbman->table_exists('qtype_rgessay_rub_fillings')) {

            // Define table qtype_rgessay_rub_fillings to be created.
            $table = new xmldb_table('qtype_rgessay_rub_fillings');

            // Adding fields to table qtype_rgessay_rub_fillings.
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('instanceid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $table->add_field('criterionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $table->add_field('levelid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
            $table->add_field('remark', XMLDB_TYPE_TEXT, null, null, null, null, null);
            $table->add_field('remarkformat', XMLDB_TYPE_INTEGER, '2', null, null, null, null);

            // Adding keys to table qtype_rgessay_rub_fillings.
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $table->add_key('fk_instanceid', XMLDB_KEY_FOREIGN, ['instanceid'], 'grading_instances', ['id']);
            $table->add_key('fk_criterionid', XMLDB_KEY_FOREIGN, ['criterionid'], 'gradingform_rubric_criteria', ['id']);
            $table->add_key('uq_instance_criterion', XMLDB_KEY_UNIQUE, ['instanceid', 'criterionid']);

            // Adding indexes to table qtype_rgessay_rub_fillings.
            $table->add_index('ix_levelid', XMLDB_INDEX_NOTUNIQUE, ['levelid']);

            $dbman->create_table($table);
        }
        // Essay savepoint reached.
        upgrade_plugin_savepoint(true, 2019061901, 'qtype', 'rgessay');

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
