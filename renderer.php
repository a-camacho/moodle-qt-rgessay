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
 * RG Essay question renderer class.
 *
 * @package    qtype
 * @subpackage rgessay
 * @copyright  2019 André Camacho
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/type/essay/renderer.php');

/**
 * Generates the output for essay questions.
 */
class qtype_rgessay_renderer extends qtype_essay_renderer {

}

/**
 * A base class to abstract out the differences between different type of
 * response format.
 */
abstract class qtype_rgessay_format_renderer_base extends qtype_essay_format_renderer_base {

}

/**
 * An essay format renderer for essays where the student should not enter
 * any inline response.
 */
class qtype_rgessay_format_noinline_renderer extends qtype_essay_format_noinline_renderer {

}

/**
 * An essay format renderer for essays where the student should use the HTML
 * editor without the file picker.
 */
class qtype_rgessay_format_editor_renderer extends qtype_essay_format_editor_renderer {

}


/**
 * An essay format renderer for essays where the student should use the HTML
 * editor with the file picker.
 */
class qtype_rgessay_format_editorfilepicker_renderer extends qtype_essay_format_editorfilepicker_renderer {

}


/**
 * An essay format renderer for essays where the student should use a plain
 * input box, but with a normal, proportional font.
 */
class qtype_rgessay_format_plain_renderer extends qtype_essay_format_plain_renderer {

}


/**
 * An essay format renderer for essays where the student should use a plain
 * input box with a monospaced font. You might use this, for example, for a
 * question where the students should type computer code.
 */
class qtype_rgessay_format_monospaced_renderer extends qtype_essay_format_monospaced_renderer {

}
