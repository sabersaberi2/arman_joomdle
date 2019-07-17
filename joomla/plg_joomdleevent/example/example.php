<?php
/**
* @version		1.0.0
* @package		Joomdle - Events example
* @copyright	Qontori Pte Ltd
* @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class  plgJoomdleeventExample extends JPlugin
{
    public function onJoomdleRoleAssigned ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_role_assigned.txt",
                $data['username'] . " enroled to course with ID=" . $data['course_id'] .
                ' and name ' . $data['course_name']);
    }

    public function onJoomdleRoleUnassigned ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_role_unassigned.txt",
                $data['username'] . " unenroled from course with ID=" . $data['course_id'] .
                ' and name ' . $data['course_name']);
    }

    public function onJoomdleUserCreated ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_user_created.txt",
                $data['username'] . " was created in Moodle with this data: " . serialize ($data));
    }

    public function onJoomdleUserUpdated ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_user_updated.txt",
                $data['username'] . " was updated in Moodle with this data: " . serialize ($data));
    }

    public function onJoomdleUserDeleted ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_user_deleted.txt",
                $data['username'] . " was deleted in Moodle");
    }

    public function onJoomdleCourseCreated ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_course_created.txt",
                $data['course_name'] . " was created in Moodle with this data: " . serialize ($data));
    }

    public function onJoomdleCourseUpdated ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_course_updated.txt",
                $data['course_name'] . " was updated in Moodle with this data: " . serialize ($data));
    }

    public function onJoomdleCourseDeleted ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_course_deleted.txt",
                $data['course_name'] . " was deleted in Moodle");
    }

    public function onJoomdleCourseModuleCreated ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_course_module_created.txt",
                 "A new course module was created in Moodle with this data: " . serialize ($data));
    }

    public function onJoomdleCourseModuleUpdated ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_course_module_updated.txt",
                 "A course module was updated in Moodle with this data: " . serialize ($data));
    }

    public function onJoomdleCourseModuleDeleted ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_course_module_deleted.txt",
                 "A course module was deleted in Moodle with this data: " . serialize ($data));
    }

    public function onJoomdleQuizAttemptSubmitted ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_quiz_attempt_submitted.txt",
                $data['username'] . " submitted an attempt on quiz " . $data['quiz_name'] . " from course with ID=" . $data['course_id'] .
                ' and name ' . $data['course_name']);
    }

    public function onJoomdleCourseCompleted ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_course_completed.txt",
                $data['username'] . " completed course with ID=" . $data['course_id'] .
                ' and name ' . $data['course_name']);
    }

    public function onJoomdleUserPasswordUpdated ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');

        file_put_contents ($tmp_path . "/joomdle_events_test_user_password_updated.txt",
                $data['username'] . " updated his password in Moodle with this data: " . serialize ($data));
    }
}
