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

require_once($CFG->dirroot . '/question/type/essay/questiontype.php');

/**
 * The essay question type.
 */
class qtype_rgessay extends qtype_essay {

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
        $DB->update_record('qtype_essay_options', $options);
    }

}