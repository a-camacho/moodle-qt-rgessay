<?php

/**
 * @package    moodlecore
 * @subpackage backup-moodle2
 * @copyright  2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * restore plugin class that provides the necessary information
 * needed to restore one essay qtype plugin
 *
 * @copyright  2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_qtype_rgessay_plugin extends restore_qtype_plugin {

    /**
     * Returns the paths to be handled by the plugin at question level
     */
    protected function define_question_plugin_structure() {
        return array(
            new restore_path_element('rgessay', $this->get_pathfor('/rgessay'))
        );
    }

    /**
     * Process the qtype/rgessay element
     */
    public function process_rgessay($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        if (!isset($data->responsetemplate)) {
            $data->responsetemplate = '';
        }
        if (!isset($data->responsetemplateformat)) {
            $data->responsetemplateformat = FORMAT_HTML;
        }
        if (!isset($data->responserequired)) {
            $data->responserequired = 1;
        }
        if (!isset($data->attachmentsrequired)) {
            $data->attachmentsrequired = 0;
        }

        // Detect if the question is created or mapped.
        $questioncreated = $this->get_mappingid('question_created',
                $this->get_old_parentid('question')) ? true : false;

        // If the question has been created by restore, we need to create its
        // qtype_essay too.
        if ($questioncreated) {
            $data->questionid = $this->get_new_parentid('question');
            $newitemid = $DB->insert_record('qtype_rgessay_options', $data);
            $this->set_mapping('qtype_rgessay', $oldid, $newitemid);
        }
    }

    /* TODO : Is that function needed as we don't use graderinfo field ? */
    /**
     * Return the contents of this qtype to be processed by the links decoder
     */
    public static function define_decode_contents() {
        return array(
            new restore_decode_content('qtype_ressay_options', 'graderinfo', 'qtype_rgessay'),
        );
    }

    /**
     * When restoring old data, that does not have the essay options information
     * in the XML, supply defaults.
     */
    protected function after_execute_question() {
        global $DB;

        $rgessayswithoutoptions = $DB->get_records_sql("
                    SELECT q.*
                      FROM {question} q
                      JOIN {backup_ids_temp} bi ON bi.newitemid = q.id
                 LEFT JOIN {qtype_rgessay_options} qeo ON qeo.questionid = q.id
                     WHERE q.qtype = ?
                       AND qeo.id IS NULL
                       AND bi.backupid = ?
                       AND bi.itemname = ?
                ", array('rgessay', $this->get_restoreid(), 'question_created'));

        foreach ($rgessayswithoutoptions as $q) {
            $defaultoptions = new stdClass();
            $defaultoptions->questionid = $q->id;
            $defaultoptions->responseformat = 'editor';
            $defaultoptions->responserequired = 1;
            $defaultoptions->responsefieldlines = 15;
            $defaultoptions->attachments = 0;
            $defaultoptions->attachmentsrequired = 0;
            $defaultoptions->responsetemplate = '';
            $defaultoptions->responsetemplateformat = FORMAT_HTML;
            $defaultoptions->rubricid = null;

            $DB->insert_record('qtype_rgessay_options', $defaultoptions);
        }
    }
}
