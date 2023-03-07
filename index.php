<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_mytutors
 * @copyright   2021 Tristan daCosta <tristan.dacosta@edinburghcollege.ac.uk>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
  */

require('../../config.php');
require_once('lib.php');
require_login();
global $PAGE, $USER;

$strname = get_string('pluginname', 'local_mytutors');
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/mytutors/index.php');
$PAGE->set_pagelayout('incourse');
$PAGE->navbar->add($strname);
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">';
echo $OUTPUT->header();

$id = optional_param('id', null, PARAM_INT);
if (empty($id)) {
	$ref = $_SERVER['HTTP_REFERER'];
	if (!empty($ref)) {
		parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $params);
		$id = $params['id'];
	}
}

// Course ID still unknown - no referrer or url param received
if (!empty($id)) {
	$course = mytutors_get_course($id);
	if (!empty($course))  {
		$context = context_course::instance($course->id);
		$PAGE->set_context($context);
		$PAGE->set_title($course->fullname.' - '.$strname);
		$PAGE->set_heading($course->fullname.' - '.$strname);
		echo mytutors_get_course_tutors_content($id);
	} else {
		echo '<p class="alert alert-danger">'.get_string('invalidcourse', 'local_mytutors').'</p></br>'.get_string('gohome', 'local_mytutors');
	}
} else {
	$PAGE->set_title($strname);
	$PAGE->set_heading($strname);
	echo '<p class="alert alert-danger">'.get_string('nocourse', 'local_mytutors').'</p></br>'.get_string('gohome', 'local_mytutors');
}

echo $OUTPUT->footer();
