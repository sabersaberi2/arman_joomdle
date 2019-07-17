<?php

/**
* @version		1.0.1
* @package		Joomdle - Mod Display List of Moodle Teachers (grow)
* @copyright	Qontori Pte Ltd
* @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/mappings.php') ;

?>
    <ul class="joomdleteachers_grow<?php echo $moduleclass_sfx; ?>">
<?php
        if (is_array($teachers))
            foreach ($teachers as $id => $teacher)
            {
                if (in_array($teacher['username'], $teachers_excluded))
                    continue;

                if (!empty($teacher))
                    $teacher_user_info = JoomdleHelperMappings::get_user_info_for_joomla ($teacher['username']);
                    // if (!count ($teacher_user_info)) //not a Joomla user
                        // continue;
                else
                    $teacher_user_info['pic_url'] = JRoute::_(JURI::root ().'media/joomdle/grow/anonymous_user_avatar_100.jpg');

                // Use thumbs if available
                if ((array_key_exists ('thumb_url', $teacher_user_info)) && ($teacher_user_info['thumb_url'] != ''))
                    $teacher_user_info['pic_url'] = $teacher_user_info['thumb_url'];

                if (!(array_key_exists ('pic_url', $teacher_user_info)) || ($teacher_user_info['pic_url'] == ''))
                    $teacher_user_info['pic_url'] = JRoute::_(JURI::root ().'media/joomdle/grow/anonymous_user_avatar_100.jpg');

                $url = JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$joomdle_itemid");

                echo '<img src="'.$teacher_user_info['pic_url'].'">';
                echo "<li><a href=\"".$url."\">".$teacher['firstname']. " " . $teacher['lastname'] ."</a></li>";
            }

?>
    </ul>
