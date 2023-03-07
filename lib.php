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

defined('MOODLE_INTERNAL') || die();

function test_extend_settings_navigation($settingsnav, $testnode = null) {
}

function mytutors_get_course($id) {
    global $DB;
    $course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
    return $course;
}

function mytutors_get_course_tutors_content($id) {
    global $DB, $CFG;
    
    $course = $DB->get_record('course', array('id' => $id), 'fullname', MUST_EXIST);
    $placeholder = $CFG->wwwroot.'/local/mytutors/pix/blank-profile-picture.png';
    $content = '';
    $content .= '<div class="container">';
    $content .= '<p><a href="'.$CFG->wwwroot.'/course/view.php?id='.$id.'"><<< Back to Course ('.$course->fullname.')</a></p>';

    // Teachers
    $teachers = mytutors_get_teachers($id);
    if ( !empty($teachers) ) {
        $content .= '<div class="card-deck" style="margin-bottom:20px;">';
        $content .= '<div class="card card-shadow bg-secondary border-0 mb-3" style="width: 18rem;box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%);padding: 20px;max-width: 250px;min-width: 250px;text-align: left!important;">';
        $content .= '<div class="card-block">';
        $content .= '<h2>Timetabled Staff</h2>';
        $content .= '<p class="lead">'.$course->fullname.'</p>';
        $content .= '<p>'.get_string("staff_desc","local_mytutors").'</p>';
        $content .= '</div>';
        $content .= '</div>';             
        foreach ($teachers as $teacher) {
            $teacher->fullname = $teacher->firstname.' '.$teacher->lastname;
            if($teacher->picture > 0) {
                $teachercontext = context_user::instance($teacher->id, MUST_EXIST);
                $imageurl = moodle_url::make_pluginfile_url($teachercontext->id, 'user', 'icon', NULL, '/', 'f3');
                $teacher->profileimageurl = $imageurl->out(false);
            }
            else $teacher->profileimageurl = $placeholder;
            $content .= '<div class="card card-shadow text-center border-0 mb-3" style="width: 18rem;box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%);padding: 20px;min-width: 200px;max-width:350px;">';
            $content .= '<div class="card-block">';
            $content .= '<a class="avatar avatar-lg" href="/user/view.php?id='.$teacher->id.'" title="View '.$teacher->firstname.'&#39;s Moodle profile" target="_blank">';
            $content .= '<img class="w-100" src="'.$teacher->profileimageurl.'" alt="'. $teacher->fullname .'">';
            $content .= '</a>';
            $content .= '<h4 class="profile-user" style="margin-top: 15px;">'. $teacher->fullname .'</h4>';
            $content .= '<p class="profile-job">'.$teacher->department.'</br>';
            $content .= $teacher->institution.'</p>';
            $content .= '<div id="profile small"><p>'.$teacher->description.'</p></div>';
            $content .= '<div class="profile small">';
            $content .= $teacher->email.'</br>';
            $content .= $teacher->phone1.'</br>';
            $content .= '</div>';
            $content .= '<div class="profile-social" style="margin-top:15px;">';
            $content .= '<a class="bi bi-microsoft-teams m-auto text-dark" title="Contact '.$teacher->firstname.' on Teams" target="_blank" href="https://teams.microsoft.com/l/chat/0/0?users='.$teacher->email.'">';
            // $content .= '<img src="/theme/image.php/remui_child/block_microsoft/1610017735/msteams" style="width:35px;margin-top: -8px;"></a>';
            $content .= '<a class="bi bi-envelope-at m-auto text-dark" title="Email '.$teacher->firstname.'" target="_blank" href="mailto:'.$teacher->email.'?subject=Hi '.$teacher->firstname.'!"></a>';
            $content .= '<a class="bi bi-chat-dots m-auto text-dark" title="Message '.$teacher->firstname.' in Moodle" target="_blank" href="/message/index.php?id='.$teacher->id.'"></a>';
            $content .= '</div>';
            $content .= '</div>';
            $content .= '</div>';
        }
        $content .= '</div>';
    } else {
    $content .= '<p class="alert alert-danger">'.get_string('noteachers', 'local_mytutors').'</p>';
    }

    // LDTs
    // $ldts = mytutors_get_ldts($id);
    // if ( !empty($ldts) ) {
    //     $content .= '<div class="card-deck" style="margin-bottom:20px;">';
    //     $content .= '<div class="card card-shadow text-white bg-secondary border-0 mb-3" style="width: 18rem;box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%);padding: 20px;max-width: 250px;min-width: 250px;text-align: left!important;">';
    //     $content .= '<div class="card-block">';
    //     $content .= '<h2>Learning Development Tutor</h2>';
    //     $content .= '</div>';
    //     $content .= '</div>';    
    //     foreach ($ldts as $ldt) {
    //         $ldt->fullname = $ldt->firstname.' '.$ldt->lastname;
    //         if($ldt->picture > 0) {
    //             $ldtcontext = context_user::instance($ldt->id, MUST_EXIST);
    //             $imageurl = moodle_url::make_pluginfile_url($ldtcontext->id, 'user', 'icon', NULL, '/', 'f3');
    //             $ldt->profileimageurl = $imageurl->out(false);
    //         }
    //         else $ldt->profileimageurl = $placeholder;
    //         $content .= '<div class="card card-shadow text-center border-0 mb-3" style="width: 18rem;box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%);padding: 20px;min-width: 200px;">';
    //         $content .= '<div class="card-block">';
    //         $content .= '<a class="avatar avatar-lg" href="javascript:void(0)">';
    //         $content .= '<img class="w-100" src="'.$ldt->profileimageurl.'" alt="'. $ldt->fullname .'">';
    //         $content .= '</a>';
    //         $content .= '<h4 class="profile-user">'. $ldt->fullname .'</h4>';
    //         $content .= '<p class="profile-job">'.$ldt->department.'</br>';
    //         $content .= $ldt->institution.'</p>';
    //         $content .= '<div id="profile small"><p>'.$ldt->description.'</p></div>';
    //         $content .= '<div class="profile small">';
    //         $content .= $ldt->email.'</br>';
    //         $content .= $ldt->phone1.'</br>';
    //         $content .= '</div>';
    //         $content .= '<div class="profile-social" style="margin-top:15px;">';
    //         $content .= '<a class="icon" title="Contact '.$ldt->firstname.' on Teams" target="_blank" href="https://teams.microsoft.com/l/chat/0/0?users='.$ldt->email.'">';
    //         $content .= '<img src="/theme/image.php/remui_child/block_microsoft/1610017735/msteams" style="width:35px;margin-top: -8px;"></a>';
    //         $content .= '<a class="icon fa fa-envelope-o" title="Email '.$ldt->firstname.'" target="_blank" href="mailto:'.$ldt->email.'?subject=Hi '.$ldt->firstname.'!"></a>';
    //         $content .= '<a class="icon fa fa-commenting-o" title="Message '.$ldt->firstname.' in Moodle" target="_blank" href="/message/index.php?id='.$ldt->id.'"></a>';
    //         $content .= '</div>';
    //         $content .= '</div>';
    //         $content .= '</div>';
    //     }
    //     $content .= '</div>';
    // } else {
    // $content .= '<p class="alert alert-danger">'.get_string('noldt', 'local_mytutors').'</p>';
    // }

    // Support Worker
    $support_workers = mytutors_get_support_workers();
    if ( !empty($support_workers) ) {
        $content .= '<div class="card-deck" style="margin-bottom:20px;">';
        $content .= '<div class="card card-shadow bg-success text-white border-0 mb-3" style="width: 18rem;box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%);padding: 20px;max-width: 250px;min-width: 250px;text-align: left!important;">';
        $content .= '<div class="card-block">';
        $content .= '<h2 style="color:#fff;">Learning Support</h2>';
        $content .= '<p>'.get_string("support_desc","local_mytutors").'</p>';
        $content .= '</div>';
        $content .= '</div>';             
        foreach ($support_workers as $support_worker) {
            $support_worker->fullname = $support_worker->firstname.' '.$support_worker->lastname;
            if($support_worker->picture > 0) {
                $support_workercontext = context_user::instance($support_worker->id, MUST_EXIST);
                $imageurl = moodle_url::make_pluginfile_url($support_workercontext->id, 'user', 'icon', NULL, '/', 'f3');
                $support_worker->profileimageurl = $imageurl->out(false);
            }
            else $support_worker->profileimageurl = $placeholder;
            $content .= '<div class="card card-shadow text-center border-0 mb-3" style="width: 18rem;box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%);padding: 20px;min-width: 200px;">';
            $content .= '<div class="card-block">';
            $content .= '<a class="avatar avatar-lg" href="/user/view.php?id='.$support_worker->id.'" title="View '.$support_worker->firstname.'&#39;s Moodle profile" target="_blank">';
            $content .= '<img class="w-100" src="'.$support_worker->profileimageurl.'" alt="'. $support_worker->fullname .'">';
            $content .= '</a>';
            $content .= '<h4 class="profile-user">'. $support_worker->fullname .'</h4>';
            $content .= '<p class="profile-job">'.$support_worker->department.'</br>';
            $content .= $support_worker->institution.'</p>';
            $content .= '<div id="profile small"><p>'.$support_worker->description.'</p></div>';
            $content .= '<div class="profile small">';
            $content .= $support_worker->email.'</br>';
            $content .= $support_worker->phone1.'</br>';
            $content .= '</div>';
            $content .= '<div class="profile-social" style="margin-top:15px;">';
            $content .= '<a class="icon" title="Contact '.$support_worker->firstname.' on Teams" target="_blank" href="https://teams.microsoft.com/l/chat/0/0?users='.$support_worker->email.'">';
            $content .= '<img src="/theme/image.php/remui_child/block_microsoft/1610017735/msteams" style="width:35px;margin-top: -8px;"></a>';
            $content .= '<a class="icon fa fa-envelope-o" title="Email '.$support_worker->firstname.'" target="_blank" href="mailto:'.$support_worker->email.'?subject=Hi '.$support_worker->firstname.'!"></a>';
            $content .= '<a class="icon fa fa-commenting-o" title="Message '.$support_worker->firstname.' in Moodle" target="_blank" href="/message/index.php?id='.$support_worker->id.'"></a>';
            $content .= '</div>';
            $content .= '</div>';
            $content .= '</div>';
        }
        $content .= '</div>';
    }

    $content .= '</div>';
    return $content;
}

function mytutors_get_teachers($id) {
    global $DB;
    $roles_to_display = get_config('local_mytutors', 'courseroles');
    $teachers = array();
    $facultyquery = "SELECT * FROM {user}
                    WHERE id in (SELECT userid
                                FROM {context} c
                                JOIN {role_assignments} ra ON c.id = ra.contextid
                                WHERE c.contextlevel = 50
                                AND c.instanceid= $id AND ra.roleid IN ($roles_to_display) )";
    $teachers = $DB->get_recordset_sql($facultyquery);
    return $teachers;
}

function mytutors_get_ldts($id) {
    global $DB;
    $ldts = array();
    $facultyquery = "SELECT * FROM {user}
        WHERE id in (SELECT userid
                    FROM {context} c
                    JOIN {role_assignments} ra ON c.id = ra.contextid
                    JOIN {role} r ON r.id = ra.roleid
                    WHERE c.contextlevel = 50
                    AND c.instanceid=" . $id . " AND r.shortname = 'ldt' )";
    $ldts = $DB->get_recordset_sql($facultyquery);
    return $ldts;
}

function mytutors_get_support_workers() {
    global $USER, $DB;
    $support_workers = array();
    $facultyquery = "SELECT * FROM {user}
        WHERE id in (SELECT userid
                    FROM {context} c
                    JOIN {role_assignments} ra ON c.id = ra.contextid
                    JOIN {role} r ON r.id = ra.roleid
                    WHERE c.contextlevel = 30
                    AND c.instanceid='" . $USER->id . "' AND r.shortname = 'supportworker' )";
    $support_workers = $DB->get_recordset_sql($facultyquery);
    $sws = $support_workers;
    foreach ($sws as $sw) {
        if (empty($sw)) {
            $support_workers = array();
            continue;
        } else {
            return $support_workers;
        }
    }
    return;
}