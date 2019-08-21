<?php
/**
* @version		1.0.0
* @package		Joomdle - Events Telegram
* @copyright	Joomla BALAD
* @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class  plgJoomdleeventJoomtel extends JPlugin
{
    public function onJoomdleRoleAssigned ($data)
    {
        $config = JFactory::getConfig();
        $tmp_path = $config->get('tmp_path');
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        $user = JFactory::getUser();
        $print_text = $data['username']." ".$data['name1'].  ' در درس \'' . $data['course_name'] . '\' با شناسه \'' . $data['course_id'] . '\' ثبت نام کرد.';
        // $client = new SoapClient("http://37.130.202.188/class/sms/wsdlservice/server.php?wsdl"); // sms
        /*
        $smsService_username = "mojtabamojtaba"; 
        $smsService_password = "13001200";
        $smsService_from = "+98100020400";
        $smsService_pattern_code = "147";
        $smsService_to = array($userMobileNumber);
        $smsService_input_data = array(
            "company" => "دانه، آموزش مجازی فعالان فرهنگی",
            "username" => $user_name,
            "password" => $newPassword,
            "site" => "lmskaran.com"
        ); 
        */
        // $smsService_response = $client->sendPatternSms($smsService_from,$smsService_to,$smsService_username,$smsService_password,$smsService_pattern_code,$smsService_input_data);
        // die();
        
        file_put_contents ($tmp_path . "/joomdle_events_test_role_assigned.txt",
                $data['firstname_lastname'] . " enroled to course with ID=" . $data['course_id'] .
                ' and name ' . $data['course_name']);
        
        $this->sendTelegramMessage ($print_text);
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

        $print_text = $data['firstname_lastname'] . ' در آزمون \'' . $data['quiz_name'] . '\' درس \'' . $data['course_name'] . '\' شرکت کرد.';

        file_put_contents ($tmp_path . "/joomdle_events_test_quiz_attempt_submitted.txt",
                $data['username'] . " submitted an attempt on quiz " . $data['quiz_name'] . " from course with ID=" . $data['course_id'] .
                ' and name ' . $data['course_name']);

        $this->sendTelegramMessage ($print_text);
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
    public function sendTelegramMessage($text)
    {
        $bot_token = $this->params->get('bot_token','');
        // $bot_token = '401577543:AAEyMny9ydfI1VySNkiYxwBCBG77hL-I5x4';
        $post = [
            'text' => $text,
            'chat_id' => $this->params->get('chat_id', ''),
            // 'chat_id' => '-1001075576478',
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'https://tapi.bale.ai/bot' . $bot_token . '/sendMessage');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);
        curl_close ($ch);
        
        return $server_output;
    }
}
