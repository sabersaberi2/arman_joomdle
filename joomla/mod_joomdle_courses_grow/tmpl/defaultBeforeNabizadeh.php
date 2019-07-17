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
<div class="joomdlecourses<?php echo $moduleclass_sfx; ?>" style="display: block; margin: 0 auto;">
<?php
	$i = 0;
	if (is_array($cursos))
        $count=0;
        foreach ($cursos as $id => $curso) {
            $id = $curso['remoteid'];
            echo '<div class="jf_col grid_4 last-column" style="padding:5px;height:300px;float:right;">';
            echo '<div class="jf_card">';
            echo '<div class="card-main" style="padding-top:15px;>';
            if ($curso['summary']) {
                if (count ($curso['summary_files']))
                {
                    foreach ($curso['summary_files'] as $file) {
                        echo '<div class="card-img" >
						<a style="width:100%" data-toggle="modal" data-target="'.'#modal'.$count.'" href="#" >
						<img class="img" style="height:150px;width:100%" hspace="0" vspace="5" align="center" src="'.$file['url'].'" data-src="'.$file['url'].'" ><p style="margin: 0px;padding: 10px 0;text-align: center">';
                        if ($linkto == 'moodle')
                        {
                            if ($default_itemid)
                                $itemid = $default_itemid;

                            if ($username)
                            {
                                echo $curso['fullname'];
                            }
                            else
                                if ($open_in_wrapper)
                                    echo $curso['fullname'];
                                else
                                    echo $curso['fullname'];
                        }
                        else
                        {
                            if ($joomdle_itemid)
                                $itemid = $joomdle_itemid;

                            $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$curso['cat_id']."&course_id=".$curso['remoteid']."&Itemid=$itemid");
                            echo $curso['fullname'];
                        }
                        echo '</p></a></div>';
                    }
                }
                echo '<div style="padding:10px" id="'.'modal'.$count.'" class="modal fade" tabindex="-1">
                 <div class="modal-dialog waves-effect" style="cursor: crosshair;">
                 <div class="modal-content">
                 <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                 <h4 id="myModalLabel-1-demo" class="modal-title">شرح دوره</h4>
                 </div>
				 <div style="padding:10px" >';
				echo JoomdleHelperSystem::actionbutton ( $curso );
				echo "<a style=\"margin-top:-37px; float:left\" "."href=\"".$url."\">اطلاعات بیشتر...</a><br>";
				echo '</div>
				<img class="modalimg" style="max-height: 250px;margin: 0 auto;display: block;" hspace="0" vspace="5" align="center" src="'.$file['url'].'" data-src="'.$file['url'].'" ><p style="margin: 0px;padding: 10px 0;text-align: center">
				<div class="modal-body" style="text-align-all: center">
				<div>'.JoomdleHelperSystem::fix_text_format($curso['summary']).'</div>';
                if ($linkto == 'moodle')
                {
                    if ($default_itemid)
                        $itemid = $default_itemid;

                    if ($username)
                    {
                        echo "<a "."$target href=\"".$moodle_auth_land_url."?username=$username&token=$token&mtype=course&id=$id&use_wrapper=$open_in_wrapper&create_user=1&Itemid=$itemid\">اطلاعات بیشتر ...</a><br>";
                    }
                    else
                        if ($open_in_wrapper)
                            echo "<a "."$target href=\"".$moodle_auth_land_url."?username=guest&mtype=course&id=$id&use_wrapper=$open_in_wrapper&Itemid=$itemid\">اطلاعات بیشتر ...</a><br>";
                        else
                            echo "<a "."$target href=\"".$moodle_url."/course/view.php?id=$id\">اطلاعات بیشتر...</a><br>";
                }
                else
                {
                    if ($joomdle_itemid)
                        $itemid = $joomdle_itemid;

                    $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$curso['cat_id'].":".JFilterOutput::stringURLSafe($curso['cat_name'])."&course_id=".$curso['remoteid'].':'.JFilterOutput::stringURLSafe($curso['fullname'])."&Itemid=$itemid");
                    //	$url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$curso['cat_id'].":".JFilterOutput::stringURLSafe($curso['cat_name'])."&course_id=".$curso['remoteid'].':'.JFilterOutput::stringURLSafe($curso['fullname']));
                    
                }
                echo '<br>
                
                </div>
                </div>
                <div class="modal-footer"><button class="btn btn-default jf_waves_dark_20" type="button" data-dismiss="modal">بستن</button></div>';
            }

            echo '</div>
            </div>
            </div>
           </div>';
            $count++;
        }
?>
