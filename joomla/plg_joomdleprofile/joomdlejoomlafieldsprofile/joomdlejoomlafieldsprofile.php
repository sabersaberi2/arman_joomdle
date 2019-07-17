<?php
/**
* @version        1.0.0
* @package        Joomdle Joomla Fields Profile
* @copyright    Qontori Pte Ltd
* @license        http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class  plgJoomdleprofileJoomdlejoomlafieldsprofile extends JPlugin
{
    private $additional_data_source = 'joomlafields';

    function integration_enabled ()
    {
        // Don't run if not configured in Joomdle
        $params = JComponentHelper::getParams( 'com_joomdle' );
        $additional_data_source = $params->get( 'additional_data_source' );
        return  ($additional_data_source == $this->additional_data_source);
    }

    // Joomdle events
    public function onGetAdditionalDataSource ()
    {
        $option['joomlafields'] = "Joomla Fields";

        return $option;
    }

    public function onJoomdleGetFields ()
    {
        if (!$this->integration_enabled ())
            return array ();

        $fields = array ();

        $db           = JFactory::getDBO();
        $query = "SELECT * ".
            ' FROM #__fields' .
            " WHERE context='com_users.user'";

        $db->setQuery($query);
        $field_objects = $db->loadObjectList();

        $fields = array ();
        $i = 0;
        foreach ($field_objects as $fo)
        {
            $fields[$i] = new JObject ();
            $fields[$i]->name =  $fo->name;
            $fields[$i]->id =  $fo->id;
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
        if (!$this->integration_enabled ())
            return array ();

        $db = JFactory::getDBO();

        $id = JUserHelper::getUserId($username);
        $user = JFactory::getUser($id);

        $user_info['firstname'] = JoomdleHelperMappings::get_firstname ($user->name);
        $user_info['lastname'] = JoomdleHelperMappings::get_lastname ($user->name);

        $mappings = JoomdleHelperMappings::get_app_mappings ('joomlafields');

        if (is_array ($mappings))
        foreach ($mappings as $mapping)
        {
            $value = $this->get_field_value ($mapping->joomla_field, $user->id);
            if ($value) //  Only overwrite if there is something
                $user_info[$mapping->moodle_field] = $value;
        }

        return $user_info;
    }

    function get_field_value ($field, $user_id)
    {
        $db           = JFactory::getDBO();
        $query = "SELECT id " .
            ' FROM #__fields' .
          " WHERE  name = " . $db->Quote($field);
        $db->setQuery($query);
        $field_id = $db->loadResult();

        $query = "SELECT value " .
            ' FROM #__fields_values' .
            " WHERE  field_id = " . $db->Quote($field_id).
            " AND item_id =" . $db->Quote($user_id);
        $db->setQuery($query);
        $value = $db->loadResult();

        return $value;
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

        $db = JFactory::getDBO();

        $username = $user_info['username'];
        $id = JUserHelper::getUserId($username);
        $user = JFactory::getUser($id);

        $mappings = JoomdleHelperMappings::get_app_mappings ('joomlafields');

        foreach ($mappings as $mapping)
        {
            $additional_info[$mapping->joomla_field] = $user_info[$mapping->moodle_field];
            // Custom moodle fields
            if (strncmp ($mapping->moodle_field, 'cf_', 3) == 0)
            {
                $data = JoomdleHelperMappings::get_moodle_custom_field_value ($user_info, $mapping->moodle_field);
                $this->set_field_value ($mapping->joomla_field, $data, $id);
            }
            else
            {
                if ($use_utf8_decode)
                    $this->set_field_value ($mapping->joomla_field, utf8_decode ($user_info[$mapping->moodle_field]), $id);
                else
                    $this->set_field_value ($mapping->joomla_field,  ($user_info[$mapping->moodle_field]), $id);
            }
        }


        return $additional_info;
    }

    function set_field_value ($field, $value, $user_id)
    {
        $db           = JFactory::getDBO();
        $query = "SELECT id " .
            ' FROM #__fields' .
          " WHERE  name = " . $db->Quote($field);
        $db->setQuery($query);
        $field_id = $db->loadResult();

        $query = "SELECT * " .
            ' FROM #__fields_values' .
            " WHERE  field_id = " . $db->Quote($field_id).
            " AND item_id =" . $db->Quote($user_id);
        $db->setQuery($query);
        $vals = $db->loadAssocList();

        if (count ($vals) > 0)
        {
            // Update entry
            $query = "UPDATE " .
                ' #__fields_values' .
                ' SET value = ' . $db->Quote($value) .
                " WHERE  field_id = " . $db->Quote($field_id).
                " AND item_id =" . $db->Quote($user_id);
            $db->setQuery($query);
            $value = $db->Query();
        }
        else
        {
            // Add new entry
            $f = new JObjet ();
            $f->field_id = $field_id;
            $f->item_id = $user_id;
            $f->value = $value;

            $db->insertObject ('#___fields_values', $f);
        }

        return true;
    }

    // Admin profile URL
    function onJoomdleGetProfileUrl ($user_id)
    {
        if (!$this->integration_enabled ())
            return false;

        $url = "index.php?option=com_users&view=profile";

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
