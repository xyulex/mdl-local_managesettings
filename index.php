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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.
/**
* ManageSettings - Import / Export your settings from a Moodle instance into another
*
* @package local
* @subpackage managesettings
* @author Raúl Martínez <http://github.com/xyulex>
* @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
require_once('../../config.php');
global $CFG, $DB;
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/tablelib.php');
require_once('functions.php');
require_once('managesettings_form.php');

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);
$PAGE->set_context($context);
$PAGE->set_heading(get_string('pluginname', 'local_managesettings'));
$PAGE->set_url('/local/managesettings/index.php');
$PAGE->set_pagelayout('incourse');
$strheading = get_string('pluginname', 'local_managesettings');
$PAGE->navbar->add($strheading);;
$mform = new managesettings_form(null, array());
if ($mform->is_cancelled()) {
redirect($CFG->wwwroot);
} else if ($data = $mform->get_data()) {
    if ($data->action) {
        $action = $data->action;
    }
    $managesettings = new managesettings();
    $managesettings->export();    
}

$plugins = array();
foreach (core_component::get_plugin_list('local') as $plugin => $plugindir) {
    if (get_string_manager()->string_exists('pluginname', 'local_' . $plugin)) {
        $strpluginname = get_string('pluginname', 'local_' . $plugin);
    } else {
        $strpluginname = $plugin;
    }
    $plugins[$plugin] = $strpluginname;
  
     $version = get_config('local_' . $plugin);
    if (!empty($version->version)) {
        $version = $version->version;
    } else {
        $version = '?';
    }

    $datatable[] = array($strpluginname, $version);
}
core_collator::asort($plugins);

echo $OUTPUT->header();
$table = new html_table();
$table->head = array(get_string('localpluginname', 'local_managesettings'), get_string('version', 'local_managesettings'));
$table->size = array('80%','20%');
$table->align = array('left', 'center');
$table->width = '50%';
$table->data = $datatable;
echo html_writer::table($table);
$mform->display();
echo $OUTPUT->footer();