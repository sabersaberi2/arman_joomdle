<?php 
/**
  * @package      Joomdle
  * @copyright    Qontori Pte Ltd
  * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
  */

// CUSTOM : Entire File

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ADMINISTRATOR.'/'.'components'.'/'.'com_joomdle'.'/'.'helpers'.'/'.'system.php');

//document object
$jdoc = JFactory::getDocument();
//add the stylesheet
$jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_courses_grow/assets/css/mod_joomdle.css');

$itemid = JoomdleHelperContent::getMenuItem();


if ($linkstarget == "new")
	$target = " target='_blank'";
else $target = "";

if ($linkstarget == 'wrapper')
	$open_in_wrapper = 1;
else
	$open_in_wrapper = 0;
?>
<div class="joomdlecourses<?php echo $moduleclass_sfx; ?>">
<?php
	$i = 0;
	if (is_array($cursos))
	foreach ($cursos as $id => $curso) {
		$id = $curso['remoteid'];
//		$curso['fullname'] = utf8_decode ($curso['fullname']);
        echo '<div class="joomdle_course_list_item span3 carousel-element">';
            echo '<div class="joomdle_item_title joomdle_course_list_item_title">';
                if ($linkto == 'moodle')
                {
                    if ($default_itemid)
                        $itemid = $default_itemid;

                    if ($username)
                    {
                        echo "<a"." class=titrcourse "."$target href=\"".$moodle_auth_land_url."?username=$username&token=$token&mtype=course&id=$id&use_wrapper=$open_in_wrapper&create_user=1&Itemid=$itemid\">".$curso['fullname']."</a><br>";
                    }
                    else
                        if ($open_in_wrapper)
                            echo "<a"." class=titrcourse "."$target href=\"".$moodle_auth_land_url."?username=guest&mtype=course&id=$id&use_wrapper=$open_in_wrapper&Itemid=$itemid\">".$curso['fullname']."</a><br>";
                        else
                            echo "<a"." class=titrcourse "."$target href=\"".$moodle_url."/course/view.php?id=$id\">".$curso['fullname']."</a><br>";
                } 
                else
                {
                    if ($joomdle_itemid)
                        $itemid = $joomdle_itemid;

                    $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$curso['cat_id'].":".JFilterOutput::stringURLSafe($curso['cat_name'])."&course_id=".$curso['remoteid'].':'.JFilterOutput::stringURLSafe($curso['fullname'])."&Itemid=$itemid"); 
                //	$url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$curso['cat_id'].":".JFilterOutput::stringURLSafe($curso['cat_name'])."&course_id=".$curso['remoteid'].':'.JFilterOutput::stringURLSafe($curso['fullname'])); 
                    echo "<a"." class=titrcourse "."href=\"".$url."\">".$curso['fullname']."</a><br>";
                }
            echo '</div>';
            if ($curso['summary'])
            {
                echo '<div class="joomdle_item_content joomdle_course_list_item_description" style="min-height: 510px;">';
                    echo '<div class="joomdle_course_description">';
                        if (count ($curso['summary_files']))
                        {
                            foreach ($curso['summary_files'] as $file) { 
                                echo '<div class="imgcontainer">';
                                    echo '<a class="jf_venobox vbox-item" data-gall="gallery_1" href="'.$file['url'].'"> <img class="courseMainPage " hspace="0" vspace="5" align="left" src="'.$file['url'].'" data-src="'.$file['url'].'" ></a>';
                                echo '</div>';
                            }
                        }
                        echo JoomdleHelperSystem::fix_text_format($curso['summary']);
                    echo '</div>';
                    echo '<div class="joomdle_course_buttons_module">';
                        echo JoomdleHelperSystem::actionbutton ( $curso );
                    echo '</div>';
                echo '</div>';
            }
        echo '</div>';
        
		$i++;
		if ($i >= $limit) // Show only this number of latest courses
			break; 
	}
?>
</div>