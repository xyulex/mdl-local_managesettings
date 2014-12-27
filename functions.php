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
require_once($CFG->libdir.'/csvlib.class.php');

class managesettings {
    public function import() {
       $csv = array_map('str_getcsv', file('yule.csv'));  //TODO
    }

    public function export() {
        global $DB;

        $export = new csv_export_writer;
        $export->filename = "yule.csv";
        $sql = "SELECT * FROM {config_plugins} WHERE plugin like 'local_%'";
        $settings = array_values($DB->get_records_sql($sql));
        
        foreach($settings as $setting) {
            $export->add_data(array($setting->plugin, $setting->name, $setting->value));
        }
        
        $export->download_file();

    }
}