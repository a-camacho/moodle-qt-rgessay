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
 * Defines the editing form for the essay question type.
 *
 * @package    qtype
 * @subpackage rgessay
 * @copyright  2019 André Camacho
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/type/essay/edit_essay_form.php');


/**
 * RG Essay question type editing form.
 */
class qtype_rgessay_edit_form extends qtype_essay_edit_form {

    protected function definition_inner($mform) {
        $qtype = question_bank::get_qtype('rgessay');

        $mform->addElement('header', 'responseoptions', get_string('responseoptions', 'qtype_essay'));
        $mform->setExpanded('responseoptions');

        $mform->addElement('select', 'responseformat',
            get_string('responseformat', 'qtype_essay'), $qtype->response_formats());
        $mform->setDefault('responseformat', 'editor');

        $mform->addElement('select', 'responserequired',
            get_string('responserequired', 'qtype_essay'), $qtype->response_required_options());
        $mform->setDefault('responserequired', 1);
        $mform->disabledIf('responserequired', 'responseformat', 'eq', 'noinline');

        $mform->addElement('select', 'responsefieldlines',
            get_string('responsefieldlines', 'qtype_essay'), $qtype->response_sizes());
        $mform->setDefault('responsefieldlines', 15);
        $mform->disabledIf('responsefieldlines', 'responseformat', 'eq', 'noinline');

        $mform->addElement('select', 'attachments',
            get_string('allowattachments', 'qtype_essay'), $qtype->attachment_options());
        $mform->setDefault('attachments', 0);

        $mform->addElement('select', 'attachmentsrequired',
            get_string('attachmentsrequired', 'qtype_essay'), $qtype->attachments_required_options());
        $mform->setDefault('attachmentsrequired', 0);
        $mform->addHelpButton('attachmentsrequired', 'attachmentsrequired', 'qtype_essay');
        $mform->disabledIf('attachmentsrequired', 'attachments', 'eq', 0);

        $mform->addElement('filetypes', 'filetypeslist', get_string('acceptedfiletypes', 'qtype_essay'));
        $mform->addHelpButton('filetypeslist', 'acceptedfiletypes', 'qtype_essay');
        $mform->disabledIf('filetypeslist', 'attachments', 'eq', 0);

        $mform->addElement('header', 'responsetemplateheader', get_string('responsetemplateheader', 'qtype_essay'));
        $mform->addElement('editor', 'responsetemplate', get_string('responsetemplate', 'qtype_essay'),
            array('rows' => 10),  array_merge($this->editoroptions, array('maxfiles' => 0)));
        $mform->addHelpButton('responsetemplate', 'responsetemplate', 'qtype_essay');

        /* Removed grader information as we will replace it with rubrics */

        $mform->addElement('header', 'rubricsheader', get_string('rubricsheader', 'qtype_rgessay'));
        $mform->setExpanded('rubricsheader');

        $mform->addElement('text', 'chooserubric', get_string('chooserubric', 'qtype_rgessay'), array('size'=>'20') );
        $mform->setType('chooserubric', PARAM_INT);

    }

}