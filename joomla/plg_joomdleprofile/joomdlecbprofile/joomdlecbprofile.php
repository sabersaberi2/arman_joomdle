<?php
/**
* @version		1.1.0
* @package		Joomdle Community Builder Profile
* @copyright	Qontori Pte Ltd
* @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class  plgJoomdleprofileJoomdlecbprofile extends JPlugin
{
	private $additional_data_source = 'cb';

    function integration_enabled ()
    {
        // Don't run if not configured in Joomdle
        $params = JComponentHelper::getParams( 'com_joomdle' );
        $additional_data_source = $params->get( 'additional_data_source' );
        return  ($additional_data_source == $this->additional_data_source);
    }

    function is_secondary_data_source ()
    {
        // Don't run if not configured in Joomdle
        $is_secondary_data_source = $this->params->get( 'is_secondary_data_source' );
        return  ($is_secondary_data_source);
    }

    // Joomdle events
    public function onGetAdditionalDataSource ()
    {
        $option['cb'] = "Community Builder";

        return $option;
    }

	public function onJoomdleGetFields ()
	{
        if (!$this->integration_enabled ())
            return array ();

        $fields = array ();

		$db           = JFactory::getDBO();
        $query = "DESC ".
            ' #__comprofiler' ;

		$db->setQuery($query);
		$field_objects = $db->loadObjectList();

        $fields = array ();
        $i = 0;
        foreach ($field_objects as $fo)
        {
            $fields[$i] = new JObject ();
            $fields[$i]->name =  $fo->Field;
            $fields[$i]->id =  $fo->Field;
            $i++;
        }

        return $fields;
	}

	function onJoomdleGetFieldName ($field)
	{
        if (!$this->integration_enabled ())
            return false;

		return $field;
	}

	function onJoomdleGetUserInfo ($username)
    {
        // file_put_contents("joomdlecbprofile.php_onJoomdleGetUserInfo.txt",'#_____________________________#' . PHP_EOL . 
											// ($username) . PHP_EOL,FILE_APPEND);
        if ((!$this->integration_enabled ()) && (!$this->is_secondary_data_source ()))
            return array ();

        $db = JFactory::getDBO();

        $id = JUserHelper::getUserId($username);
        $user = JFactory::getUser($id);

        $user_info['firstname'] = JoomdleHelperMappings::get_firstname ($user->name);
        $user_info['lastname'] = JoomdleHelperMappings::get_lastname ($user->name);

        $mappings = JoomdleHelperMappings::get_app_mappings ('cb');

        /* User pic */
        $query = 'SELECT avatar' .
                ' FROM #__comprofiler' .
                " WHERE user_id = " . $db->quote ($id);
        $db->setQuery( $query );
        $user_row = $db->loadAssoc();

        if ($user_row['avatar'] != '')
            $user_info['pic_url'] =  'images/comprofiler/'.$user_row['avatar'];
        else
            $user_info['pic_url'] =   'components/com_comprofiler/plugin/templates/default/images/avatar/nophoto_n.png';

        foreach ($mappings as $mapping)
        {
            $value = $this->get_field_value ($mapping->joomla_field, $user->id);
			$user_info[$mapping->moodle_field] = $value;
        }
		$more_info['profile_url'] = 'index.php?option=com_comprofiler&task=userprofile&user='.$id;

        return $user_info;
    }

    function get_field_value ($field, $user_id)
    {
        // file_put_contents("joomdlecbprofile.php_get_field_value.txt",'#_____________________________#' . PHP_EOL . 
											// ($field) . PHP_EOL .
                                            // ($user_id) . PHP_EOL,FILE_APPEND);
		$db           = JFactory::getDBO();
        $query = "SELECT $field " .
            ' FROM #__comprofiler' .
			" WHERE  user_id = " . $db->Quote($user_id);
		$db->setQuery($query);
		$field_object = $db->loadObject();

        if (!$field_object)
            return "";

        return $field_object->$field;
    }

	function onJoomdleCreateAdditionalProfile ($user_info)
	{
        //file_put_contents("/home/lmskaran/domains/lmskaran.com/public_html/joomdlecbprofile.php_onJoomdleCreateAdditionalProfile.txt",'#_____________________________#' . PHP_EOL ,FILE_APPEND);
        if (!$this->integration_enabled ())
            return false;

        $username = $user_info['username'];
        $id = JUserHelper::getUserId($username);

        if (!$id)
            return; // user not found, should not happen
        $user_id = $id;

        $db = JFactory::getDBO();

        $query = 'SELECT user_id' .
                ' FROM #__comprofiler' .
				" WHERE  user_id = " . $db->Quote($id);
        $db->setQuery( $query );
        $id = $db->loadResult();

        if ($id)
            return; // user row found, nothing to do

        // Create row
        $fields->id = $user_id;
        $fields->user_id = $user_id;

        $db->insertObject ('#__comprofiler', $fields);
	}

	function onJoomdleSaveUserInfo ($user_info, $use_utf8_decode = true)
    {
        // file_put_contents("joomdlecbprofile.php_onJoomdleSaveUserInfo.txt",'#_____________________________#' . PHP_EOL . 
											// var_dump ($user_info) . PHP_EOL .
                                            // $user_info['username'] . PHP_EOL .
                                            // ($use_utf8_decode) . PHP_EOL,FILE_APPEND);
        
        if (!$this->integration_enabled ())
            return false;

        $db = JFactory::getDBO();

        $username = $user_info['username'];
        $id = JUserHelper::getUserId($username);
        $user = JFactory::getUser($id);

        $mappings = JoomdleHelperMappings::get_app_mappings ('cb');

        foreach ($mappings as $mapping)
        {
            $additional_info[$mapping->joomla_field] = $user_info[$mapping->moodle_field];
            // Custom moodle fields
            if (strncmp ($mapping->moodle_field, 'cf_', 3) == 0)
            {
                // $data = JoomdleHelperMappings::get_moodle_custom_field_value ($user_info, $mapping->moodle_field);
                // CUSTOM by مهدی آنیلی
				$data = JoomdleHelperMappings::get_moodle_custom_field_value ($user_info, $mapping->moodle_field, $use_utf8_decode);
                // CUSTOM by مهدی آنیلی
                $this->set_field_value ($mapping->joomla_field, $data, $id);
            }
            else
            {
                if ($use_utf8_decode)
                    $this->set_field_value ($mapping->joomla_field, utf8_decode ($user_info[$mapping->moodle_field]), $id);
                else
                    $this->set_field_value ($mapping->joomla_field,  $user_info[$mapping->moodle_field], $id);
            }
        }

        if (($user_info['picture']) && ($user_info['pic_url']))
        {
            $this->save_avatar ($id, $user_info['pic_url']);
        }
        else
            $this->delete_avatar ($id);

        return $additional_info;
    }

    function set_field_value ($field, $value, $user_id)
    {
        // file_put_contents("joomdlecbprofile.php_set_field_value.txt",'#_____________________________#' . PHP_EOL . 
											// ($field) . PHP_EOL .
                                            // ($value) . PHP_EOL .
                                            // ($user_id) . PHP_EOL,FILE_APPEND);
        
        $db           = JFactory::getDBO();

        $query =
            ' UPDATE #__comprofiler' .
            ' SET '. $db->quoteName ($field) .'='. $db->Quote($value) .
			" WHERE user_id = " . $db->Quote($user_id);

        $db->setQuery($query);
        $db->query();

        return true;
    }

    function save_avatar ($userid, $pic_url)
    {
        // file_put_contents("joomdlecbprofile.php_save_avatar.txt",'#_____________________________#' . PHP_EOL . 
											// ($userid) . PHP_EOL .
                                            // ($pic_url) . PHP_EOL,FILE_APPEND);
        
        $pic = JoomdleHelperContent::get_file ($pic_url);

        if (!$pic)
            return;

		$extension = 'png';  // Moodle stores PNG always in 2.0
		$type = 'image/png';

        $newFileName =  uniqid($userid."_") . '.' .$extension;

        file_put_contents (JPATH_SITE . '/images/comprofiler/' . $newFileName , $pic);

        $db = JFactory::getDBO();

		$db->setQuery("UPDATE #__comprofiler SET avatar=" . $db->Quote($newFileName) . ", avatarapproved=1, lastupdatedate=now()  WHERE id=" . (int) $userid);

        $db->query();
    }

    function delete_avatar ($userid)
    {
        $db = JFactory::getDBO();

        $query = 'SELECT avatar' .
                ' FROM #__comprofiler' .
				" WHERE user_id = " . $db->Quote($userid);
        $db->setQuery( $query );
        $avatar = $db->loadResult();

        if( ( strpos( $avatar, '/' ) === false ) && is_file(JPATH_SITE.'/images/comprofiler/'.$avatar))
        {
            @unlink(JPATH_SITE.'/images/comprofiler/'.$avatar);
            if(is_file(JPATH_SITE.'/images/comprofiler/tn'.$avatar))
                @unlink(JPATH_SITE.'/images/comprofiler/tn'.$avatar);
        }
        $db->setQuery("UPDATE #__comprofiler SET avatar=null, avatarapproved=1, lastupdatedate=now()  WHERE id=" . (int) $db->quote ($userid));
        $db->query();
    }


	// Admin profile URL
	function onJoomdleGetProfileUrl ($user_id)
	{
        //file_put_contents("/home/lmskaran/domains/lmskaran.com/public_html/joomdlecbprofile.php_onJoomdleGetProfileUrl.txt",'#_____________________________#' . PHP_EOL ,FILE_APPEND);
        
        if (!$this->integration_enabled ())
            return false;

		$url = 'index.php?option=com_users&view=user&task=edit&cid[]='.$user_id;

		return $url;
	}

	function getJoomdleLoginUrl ($return)
	{
        if (!$this->integration_enabled ())
            return false;

		$url = "index.php?option=com_comprofiler&task=login&return=$return";

		return $url;
	}

	// Front-end profile URL
    function onJoomdleGetFrontendProfileUrl ($user_id)
    {
        if (!$this->integration_enabled ())
            return false;

        $url =  "index.php?option=com_comprofiler&view=userprofile&user=" . $user_id;
        return $url;
    }


}
