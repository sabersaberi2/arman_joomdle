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
require_once( JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/shop.php' );
require_once( JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/system.php' );



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
			$course_info = JoomdleHelperContent::getCourseInfo($id, $username);
		$teachers = JoomdleHelperContent::getCourseTeachers($id);
		//dump($course_info,"course information");
		//dump($teachers,"teachers");
            echo '<div class="jf_col jf_anim_done grid_4 last-column" style="padding:5px;height:300px;float:right;">';
            echo '<div class="jf_card">';
            echo '<div class="card-main" style="padding-top:15px;>';
            if ($curso['summary']) {
                if (count ($curso['summary_files']))
                { 
                    foreach ($curso['summary_files'] as $file) {
                        echo '<div class="card-img" >';
                        echo '<a style="width:100%" data-toggle="modal" data-target="'.'#modal'.$count.'" href="#" >';
                        echo '
						<div class="ovimg'.$curso['cat_id'].' ovimg">
						<img class="img" hspace="0" vspace="5" align="center" src="'.$file['url'].'" data-src="'.$file['url'].'" ><p style="margin: 0px;padding: 10px 0;text-align: center;    text-align: center;
    position: absolute;
    margin: 0 auto;
    vertical-align: middle;
    z-index: 222;
    top: 46%;
    left: 50%;
    color: aliceblue;
    transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    font-size: 17px;
    font-weight: 900;
    width: 100%;">';
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
                        echo '</p>';
						echo '</div>';

?>






<div class="profcircle jf_col grid_3 last-column" style="color: white;
                        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                        text-align: center;display: inline-block;
    
    right: 50%;
    position: absolute;
    transform: translate(50%, -50%);
    -ms-transform: translate(50%, -50%);
    border-radius: 50%;">

                        <!--moster photo-->

                        <?php
                        //$itemid = JoomdleHelperContent::getMenuItem();
                        ?>
                            <?php
                            if (is_array ($teachers))
                                foreach ($teachers as  $teacher) : ?>
                                    <?php
                                    $user_info = JoomdleHelperMappings::get_user_info_for_joomla ($teacher['username']);
                                    if (!count ($user_info)) //not a Joomla user
                                        continue;
                                    ?>

                                    <?php
                                    // Use thumbs if available
                                    if ((array_key_exists ('thumb_url', $user_info)) && ($user_info['thumb_url'] != ''))
                                        $user_info['pic_url'] = $user_info['thumb_url'];
                                    ?>

                                    <a class="profimg" ><img src="<?php echo $user_info['pic_url']; ?>"></a></div>
									 
									<div class="profnme<?php echo $course_info['cat_id']; ?> jf_linkhover jf_linkhover2 jf_col_fluid profnme" >
                            <a href="<?php if($teacher['lastname']){ echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid");} ?>"><?php if($teacher['lastname']){ echo " استاد".$teacher['lastname']; }?></a>
                        </div> 
						<div class="jf_col_fluid joomdle_course_topicsnumber topicnumber<?php echo $course_info['cat_id']; ?>">
                            <b><?php echo $course_info['numsections']." جلسه"; ?></b>
                        </div>

                                <?php endforeach; ?>
                        

                    




























<?php
	
						echo '</a>';
                        echo '</div>';
                    }
                }
               echo '<div style="padding:10px" id="'.'modal'.$count.'" class="modal fade" tabindex="-1">';
				?>
                <div class="modal-dialog waves-effect" style="cursor: crosshair;">
               <div class="modal-content" style="border-radius: 16px;padding-bottom: 20px;">
                <div class="modal-header" style="position:absolute;z-index:110;"><button class="close" type="button" data-dismiss="modal">×</button>
             <!--   //echo '<h4 id="myModalLabel-1-demo" class="modal-title">شرح دوره</h4>';-->
                </div>
				<?php
				echo '<div class="modalovimg'.$curso['cat_id'].' modalovimg">';
				echo '<img class="modalimg" style="display: block;" hspace="0" vspace="5" align="center" src="'.$file['url'].'" data-src="'.$file['url'].'" >';
				echo '</div>';
				
				$teachers2 = JoomdleHelperContent::getCourseTeachers($id);
				 
				if($teachers2)  {
					
					?>
					
					<div class="modalprofnme modalprofnme<?php echo $course_info['cat_id']?>  jf_linkhover jf_linkhover2 jf_col_fluid " >
                          <b><a href="<?php if($teacher['lastname']){ echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid");} ?>"><?php if($teacher['lastname']){ echo " استاد".$teacher['lastname']; }?></a> </b>
                        </div> 
						<div class="modalnumsec modalnumsec<?php echo $course_info['cat_id']; ?>  jf_col_fluid ">
                            <b><?php echo $course_info['numsections']." جلسه"; ?></b>
                        </div>
					
					
					
				<div class="modalprofimg ";
					<?php
				echo '<a class="profimg" ><img src="'.$user_info['pic_url'].'"></a>';
				echo '</div><div style="margin:20% 0px"></div>'; 
				}
		//dump($curso,"courso"); 
                echo '<div class="modal-body modal-body'.$curso['cat_id'].'" style="padding:0px 32px; text-align-all: center">';
                echo '<div>'.JoomdleHelperSystem::fix_text_format($curso['summary']).'</div>';
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

                    $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$curso['cat_id'].":"."&course_id=".$curso['remoteid'].':'."&Itemid=$itemid");
                    //	$url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$curso['cat_id'].":".JFilterOutput::stringURLSafe($curso['cat_name'])."&course_id=".$curso['remoteid'].':'.JFilterOutput::stringURLSafe($curso['fullname']));
                    
                }
                  
                echo '</div>';
				echo '<div class="inlineForm center" style="padding:10px" >';
				echo JoomdleHelperSystem::actionbutton ( $curso );
				echo "<a style=\"direction:rtl\" "."href=\"".$url."\">اطلاعات بیشتر...</a><br>";
				echo '</div>';
                 
                echo '</div>';
                

        }
           echo '</div>'; 
            echo '</div>'; 
            echo '</div>'; 
         echo '</div>'; 
            $count++;
            }
?>
</div> 


