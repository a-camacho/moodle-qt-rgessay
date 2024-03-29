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
 * Question type class for the rubric graded essay question type.
 *
 * @package    qtype
 * @subpackage rgessay
 * @copyright  2019 André Camacho
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/questionlib.php');

/**
 * The essay question type.
 */
class qtype_rgessay extends question_type {

    public function is_manual_graded() {
        return true;
    }

    public function response_file_areas() {
        return array('attachments', 'answer');
    }

    public function get_question_options($question) {
        global $DB;
        $question->options = $DB->get_record('qtype_rgessay_options',
            array('questionid' => $question->id), '*', MUST_EXIST);
        parent::get_question_options($question);
    }

    public function save_question_options($formdata) {
        global $DB;
        $context = $formdata->context;

        $options = $DB->get_record('qtype_rgessay_options', array('questionid' => $formdata->id));
        if (!$options) {
            $options = new stdClass();
            $options->questionid = $formdata->id;
            $options->id = $DB->insert_record('qtype_rgessay_options', $options);
        }

        $options->responseformat = $formdata->responseformat;
        $options->responserequired = $formdata->responserequired;
        $options->responsefieldlines = $formdata->responsefieldlines;
        $options->attachments = $formdata->attachments;
        $options->attachmentsrequired = $formdata->attachmentsrequired;
        if (!isset($formdata->filetypeslist)) {
            $options->filetypeslist = "";
        } else {
            $options->filetypeslist = $formdata->filetypeslist;
        }

        // Removed graderinfo as it as been removed from form

        /*
        $options->graderinfo = $this->import_or_save_files($formdata->graderinfo,
            $context, 'qtype_essay', 'graderinfo', $formdata->id);
        $options->graderinfoformat = $formdata->graderinfo['format'];
        */

        $options->responsetemplate = $formdata->responsetemplate['text'];
        $options->responsetemplateformat = $formdata->responsetemplate['format'];

        // Save rubric value in database
        $options->rubricid = $formdata->rubricid;

        $DB->update_record('qtype_rgessay_options', $options);
    }

    protected function initialise_question_instance(question_definition $question, $questiondata) {
        parent::initialise_question_instance($question, $questiondata);

        $question->responseformat = $questiondata->options->responseformat;
        $question->responserequired = $questiondata->options->responserequired;
        $question->responsefieldlines = $questiondata->options->responsefieldlines;
        $question->attachments = $questiondata->options->attachments;
        $question->attachmentsrequired = $questiondata->options->attachmentsrequired;

        $question->responsetemplate = $questiondata->options->responsetemplate;
        $question->responsetemplateformat = $questiondata->options->responsetemplateformat;
        $filetypesutil = new \core_form\filetypes_util();
        $question->filetypeslist = $filetypesutil->normalize_file_types($questiondata->options->filetypeslist);

        $question->rubricid = $questiondata->options->rubricid;
    }

    public function delete_question($questionid, $contextid) {
        global $DB;

        $DB->delete_records('qtype_rgessay_options', array('questionid' => $questionid));
        parent::delete_question($questionid, $contextid);
    }

    /**
     * Defines the table which extends the question table. This allows the base questiontype
     * to automatically save, backup and restore the extra fields.
     *
     * @return array with the table name (first) and then the column names (apart from id and questionid)
     */
    public function extra_question_fields() {
        return array('qtype_rgessay_options',
            'rubricid',        // ID of rubric definition to use to grade question
        );
    }

    /**
     * @return array the different response formats that the question type supports.
     * internal name => human-readable name.
     *
     * @throws coding_exception
     */
    public function response_formats() {
        return array(
            'editor' => get_string('formateditor', 'qtype_essay'),
            'editorfilepicker' => get_string('formateditorfilepicker', 'qtype_essay'),
            'plain' => get_string('formatplain', 'qtype_essay'),
            'monospaced' => get_string('formatmonospaced', 'qtype_essay'),
            'noinline' => get_string('formatnoinline', 'qtype_essay'),
        );
    }

    /**
     * @return array the choices that should be offered when asking if a response is required
     * @throws coding_exception
     */
    public function response_required_options() {
        return array(
            1 => get_string('responseisrequired', 'qtype_essay'),
            0 => get_string('responsenotrequired', 'qtype_essay'),
        );
    }

    /**
     * @return array the choices that should be offered for the input box size.
     * @throws coding_exception
     */
    public function response_sizes() {
        $choices = array();
        for ($lines = 5; $lines <= 40; $lines += 5) {
            $choices[$lines] = get_string('nlines', 'qtype_essay', $lines);
        }
        return $choices;
    }

    /**
     * @return array the choices that should be offered for the number of attachments.
     * @throws coding_exception
     */
    public function attachment_options() {
        return array(
            0 => get_string('no'),
            1 => '1',
            2 => '2',
            3 => '3',
            -1 => get_string('unlimited'),
        );
    }

    /**
     * @return array the choices that should be offered for the number of required attachments.
     * @throws coding_exception
     */
    public function attachments_required_options() {
        return array(
            0 => get_string('attachmentsoptional', 'qtype_essay'),
            1 => '1',
            2 => '2',
            3 => '3'
        );
    }

    /**
     * Create a question_answer, or an appropriate subclass for this question,
     * from a row loaded from the database.
     * @param object $answer the DB row from the question_answers table plus extra answer fields.
     * @return question_answer
     */
    protected function make_answer($answer) {
        return new qtype_rgessay_answer($answer->id, $answer->answer,
            $answer->fraction, $answer->feedback, $answer->feedbackformat, 2);
    }

    /**
     * Get an array of id's and names of rubrics created on this course
     * @return array
     * @throws dml_exception
     */
    public function get_rubrics_from_course() {
        global $DB, $USER;

        $definitions = $DB->get_records('grading_definitions', array( 'usercreated' => $USER->id, 'method' => 'rubric' ), '', 'id,name' );
        $array = array();

        // Add default value
        $array[0] = '';

        foreach ( $definitions as $definition ) {
            $array[$definition->id] = $definition->name;
        }

        return $array;
    }

}