<?php
/**
* @version        1.1.0
* @package        Joomdle Joomla Fields Profile
* @copyright    Qontori Pte Ltd
* @license        http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class  plgJoomdleprofileJoomlaprofile extends JPlugin
{
    private $additional_data_source = 'joomla16';

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
        $option['joomla16'] = "Joomla User Profiles";

        return $option;
    }

    public function onJoomdleGetFields ()
    {
        if (!$this->integration_enabled ())
            return array ();

        require_once (JPATH_SITE . '/plugins/joomdleprofile/joomlaprofile/j16profiles.php');
        $j16profiles = new PluginsModelJ16profiles ();
        $form = $j16profiles->getForm ();
        $form_fields =  $form->getFieldset();

        $j16_profile_plugin = $this->params->get( 'j16_profile_plugin' );

        if (!$j16_profile_plugin)
            $j16_profile_plugin = 'profile';

        $fields = array ();
        foreach ($form_fields as $field)
        {
            $name = $field->__get('name');

            preg_match_all("^\[(.*?)\]^",$name,$matches, PREG_PATTERN_ORDER);
            $field_name =  $matches[1][0];

            $f = new JObject ();
            $f->name = $field_name;
            $f->id = $j16_profile_plugin . '.' . $f->name;
            $fields[] = $f;
        }

        return $fields;

    }

    function onJoomdleGetFieldName ($field)
    {
        if (!$this->integration_enabled ())
            return false;

        return substr ($field, 8); //remove "profile."
    }

    function onJoomdleGetUserInfo ($username)
    {
        if ((!$this->integration_enabled ()) && (!$this->is_secondary_data_source ()))
            return array ();

        $db = JFactory::getDBO();

        $id = JUserHelper::getUserId($username);
        $user = JFactory::getUser($id);

        $user_info['firstname'] = JoomdleHelperMappings::get_firstname ($user->name);
        $user_info['lastname'] = JoomdleHelperMappings::get_lastname ($user->name);

        $mappings = JoomdleHelperMappings::get_app_mappings ('joomla16');

        if (is_array ($mappings))
        foreach ($mappings as $mapping)
        {
            $value = $this->get_field_value ($mapping->joomla_field, $user->id);
            if ($value)
            {
                // Value is stored in DB in unicode
                $user_info[$mapping->moodle_field] =  json_decode ($value);
            }
        }

        return $user_info;
    }

    function get_field_value ($field, $user_id)
    {
        $db           = JFactory::getDBO();
        $query = 'SELECT profile_value ' .
            ' FROM #__user_profiles' .
            " WHERE profile_key = " . $db->Quote($field) . " AND user_id = " . $db->Quote($user_id);
        $db->setQuery($query);
        $field_obj = $db->loadObject();

        if (!$field_obj)
            return "";

        if ($field == 'profile.dob')
            $field_obj->profile_value = strtotime(json_decode ($field_obj->profile_value));

        return $field_obj->profile_value;
    }


    function onJoomdleCreateAdditionalProfile ($user_info)
    {
        if (!$this->integration_enabled ())
            return false;

        // Nothing to do
        return true;
    }

    function onJoomdleSaveUserInfo ($user_info, $use_utf8_decode = true)
    {
        if (!$this->integration_enabled ())
            return false;

        $username = $user_info['username'];
        $id = JUserHelper::getUserId($username);
        $user = JFactory::getUser($id);
    }

    function set_field_value ($field, $value, $user_id)
    {
        $db           = JFactory::getDBO();

        $query = 
            ' SELECT count(*) from  #__user_profiles' . 
                              " WHERE profile_key = " . $db->Quote($field) . " AND user_id = " . $db->Quote($user_id);

        $db->setQuery($query);
        $exists = $db->loadResult();

        // Encode value in format used by Joomla
        $value = json_encode ($value);

        if ($exists)  
            $query =  ' UPDATE #__user_profiles' .
                    ' SET profile_value='. $db->Quote($value) .
                    " WHERE profile_key = " . $db->Quote($field) . " AND user_id = " . $db->Quote($user_id);
        else
            $query = ' INSERT INTO #__user_profiles' .
                    ' (profile_key, user_id, profile_value) VALUES ('. $db->Quote($field) . ','.
                    $db->Quote($user_id) . ',' . $db->Quote($value) . ')';

        $db->setQuery($query);
        $db->query();

        return true;
    }

    // Admin profile URL
    function onJoomdleGetProfileUrl ($user_id)
    {
        if (!$this->integration_enabled ())
            return false;

		$url = 'index.php?option=com_users&task=user.edit&id='.$user_id;

        return $url;
    }

    function getJoomdleLoginUrl ($return)
    {
        if (!$this->integration_enabled ())
            return false;

        $url = "index.php?option=com_users&view=login&return=$return";

        return $url;
    }

}
