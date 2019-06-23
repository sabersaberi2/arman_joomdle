<?php

	// CUSTOM : Entire File

/**
  * @package      Joomdle
  * @copyright    Qontori Pte Ltd
  * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
  */

defined('_JEXEC') or die('Restricted access'); ?>

<?php

$course_info         = $this->course_info;
$course_id           = $this->course_info['remoteid'];                            // custom : course view variable
$show_topics_numbers = $this->params->get( 'course_show_numbers');                // custom : course view variable
$itemid              = JoomdleHelperContent::getMenuItem();
$jump_url            =  JoomdleHelperContent::getJumpURL ();                      // custom : course view variable

$show_contents_link       = $this->params->get( 'show_contents_link' );
$show_topics_link         = $this->params->get( 'show_topìcs_link' );
$show_grading_system_link = $this->params->get( 'show_grading_system_link' );
$show_teachers_link       = $this->params->get( 'show_teachers_link' );
$show_category            = $this->params->get( 'show_detail_category', 1 );
$show_summary             = $this->params->get( 'show_detail_summary', 1 );
$show_summary_course_view = $this->params->get( 'course_show_summary');           // custom : course view variable
$show_language            = $this->params->get( 'show_detail_language', 0 );
$show_startdate           = $this->params->get( 'show_detail_startdate', 1 );
$show_enroldates          = $this->params->get( 'show_detail_enroldates', 0 );
$show_enrolperiod         = $this->params->get( 'show_detail_enrolperiod', 1 );
$show_topicsnumber        = $this->params->get( 'show_detail_topicsnumber', 1 );
$show_cost                = $this->params->get( 'show_detail_cost', 1 );
$show_motivation          = $this->params->get( 'show_detail_application_motivation', 'no' );
$show_experience          = $this->params->get( 'show_detail_application_experience', 'no' );
$free_courses_button      = $this->params->get( 'free_courses_button' );
$paid_courses_button      = $this->params->get( 'paid_courses_button' );

$user        = JFactory::getUser();                                               // detail & course view variable
$user_logged = $user->id;
$username    = $user->username;                                                   // custom : course view variable
$session     = JFactory::getSession();                                            // custom : course view variable
$token       = md5 ($session->getId());                                           // custom : course view variable
$direct_link = 1;                                                                 // custom : course view variable


$jdoc = JFactory::getDocument();                                                                                   // custom : document object
$jdoc->addStyleSheet(JURI::root ().'components/com_joomdle/views/detail/assets/css/com_joomdle_views_detail.css'); // custom : add the stylesheet
    $jdoc->addStyleSheet(JURI::root ().'/components/com_joomdle/views/teacher/assets/css/ud.css');				//to add candle and cake and other to teacher profile-added by MJT

$jdoc->addStyleSheet(JURI::root ().'templates/g5_hydrogen/custom/bootstrap.min.css');         // custom : add the stylesheet
$jdoc->addStyleSheet(JURI::root ().'templates/g5_hydrogen/custom/bootstrap-theme.min.css');   // custom : add the stylesheet
$jdoc->addScript(JURI::root ().'templates/g5_hydrogen/custom/bootstrap.min.js');              // custom : add the script

// Add custom stylesheet
$custom_style  = '.joomdle_accordion_v'.$this->pageclass_sfx.' {display: flex; background: #f9f9f9; border: solid 1px #E8E9EB; height: auto; margin-top: 3px; padding: 10px 30px 10px 22px;} ';
$custom_style .= 'li.list-group-item {display: flex; padding: 12px 30px 12px 43px; background-color: #FFF; border: 1px solid #E8E9EB; border-top-color: rgb(232, 233, 235); border-top-style: solid; border-top-width: 1px; border-top: none; color: #686F7A; line-height: 1.33;} ';
$custom_style .= 'ul.list-group.list-group-striped li.list-group-item:nth-of-type(2n+1) {background-color: rgba(0,0,0,.05);} ';
$custom_style .= 'ul.list-group.list-group-striped li.list-group-item > img {height: 20px;} ';
$jdoc->addStyleDeclaration( $custom_style );

// Add custom javascript
$custom_javascript  = '
    jQuery(document).ready(function($){
        $(\'.collapse\').on(\'shown.bs.collapse\', function(){
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on(\'hidden.bs.collapse\', function(){
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });';
$custom_javascript .= '
    fixBrokenImages = function( url ){
        var img = document.getElementsByTagName(\'img\');
        var i=0, l=img.length;
        for(;i<l;i++){
            var t = img[i];
            if(t.naturalWidth === 0){
                //this image is broken
                t.src = url;
                t.width = "1";
                t.parentNode.removeChild(t);
                //var img_parent = t.closest(".joomdle_section_list_item_resources_content");
                //img_parent.parentNode.removeChild(img_parent);
            }
        }
    };';
$jdoc->addScriptDeclaration( $custom_javascript );

$unicodeslugs = JFactory::getConfig()->get('unicodeslugs');             // custom

if (!array_key_exists ('cost',$course_info))
    $course_info['cost'] = 0;
?>

<div class="jf_col_fluid joomdle-coursedetails<?php echo $this->pageclass_sfx;?>">



















<?php
                    if (is_array ($this->teachers))
                        foreach ($this->teachers as $teacher) :
?>
<?php
                            $user_info = JoomdleHelperMappings::get_user_info_for_joomla ($teacher['username']);
                            if (!count ($user_info)) //not a Joomla user
                                continue;

                            $user_info = array_combine(array_map(function($old_user_info_k, $old_user_info_v) {

                                if (strncmp ($old_user_info_k, 'cf_', 3) == 0)
                                {
                                    $db    = JFactory::getDbo();
                                    $query = 'SELECT joomla_field' .
                                        ' FROM #__joomdle_field_mappings' .
                                        " WHERE moodle_field = " . $db->quote($old_user_info_k);
                                    $db->setQuery($query);
                                    $new_user_info_k = $db->loadResult();
                                    $old_user_info_k = $new_user_info_k;
                                }
                                return ($old_user_info_k);

                            }, array_keys($user_info), $user_info),$user_info);
?>
                            <div class="flex-col wi-lg-25 wi-md-33 wi-sm-100 wi-xs-100 infoWrap" >
                                <div class="span4 author-info wi-100" >
                                    <div class="authorInfoParent wi-100 flex-col al-center jus-start" typeof="Person">
                                        <div class="anniversary flex-col">
                                            <i aria-hidden="true" class="udregistersince fa fa-birthday-cake"><br>
                                                <span style=' font-family: "IRANSansWeb", "WebYekan", "tahoma", sans-serif;font-size: 10px;padding:2px 2px;'>
<?php
                                                $date = new DateTime($user_info['registerDate']);
                                                $interval = $date->diff(new DateTime());

                                                if ( ($interval->y) && ($interval->m) )
                                                    echo $date->diff(new DateTime())->format("%y سال و %m ماه");

                                                elseif ( ($interval->y) && ($interval->m == 0) )
                                                    echo $date->diff(new DateTime())->format("%y سال");

                                                elseif ( ($interval->m) && ($interval->d) )
                                                    echo $date->diff(new DateTime())->format("%m ماه و %d روز");

                                                elseif ( ($interval->m) && ($interval->d == 0) )
                                                    echo $date->diff(new DateTime())->format("%m ماه");

                                                elseif ( ($interval->d) && ($interval->h) )
                                                    echo $date->diff(new DateTime())->format("%d روز و %h ساعت");

                                                elseif ( ($interval->d) && ($interval->h == 0) )
                                                    echo $date->diff(new DateTime())->format("%d روز");

                                                elseif ( ($interval->h) && ($interval->i) )
                                                    echo $date->diff(new DateTime())->format("%h ساعت و %i دقیقه");

                                                elseif ( ($interval->h) && ($interval->i == 0) )
                                                    echo $date->diff(new DateTime())->format("%h ساعت");

                                                elseif ($interval->i)
                                                    echo $date->diff(new DateTime())->format("%i دقیقه");

                                                else
                                                    echo "جدید";
?>
                                                </span>
                                            </i>
                                                <div class="cut"></div>
                                        </div>
<?php
                                        // Use thumbs if available
                                        if ((array_key_exists ('thumb_url', $user_info)) && ($user_info['thumb_url'] != ''))
                                            $user_info['pic_url'] = $user_info['thumb_url'];

                                        if (!(array_key_exists ('pic_url', $user_info)) || ($user_info['pic_url'] == ''))
                                            $user_info['pic_url'] = JURI::root ().'media/joomdle/grow/anonymous_user_avatar_100.jpg';
?>
                                        <div class="teacherproilepic joomdle_user_pic">
                                            <img height="64" width="64" src="<?php echo $user_info['pic_url']; ?>">
                                        </div>
<?php
                                        if ( !(array_key_exists('profile_url', $user_info)) )
                                            $user_info['profile_url'] = '#';
?>
                                        <a class="nameLink" href="<?php echo JRoute::_($user_info['profile_url']."&Itemid=$itemid");// $itemid=956 for udemy template?>">
                                            <?php echo $user_info['name']; ?>
                                        </a>

                                        <div class="activity portions wi-100 flex-row jus-between al-start">
                                            <div class="titles">فعالیت مدرس</div>
                                            <div class="flex-col jus-center al-center wi-30">
                                                <i class="icon-faranesh-font-11"></i>
                                                <div class="desc">تعداد دوره‌ها</div>
                                                <div class="value">
                                                    <?php echo $teacher['coursecounter']; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="aboutAuthor portions wi-100 flex-row jus-between al-start">
                                            <div class="titles">درباره مدرس</div>
                                            <p class="flex-col jus-center al-center wi-100" style="text-align: right;">
                                                <?php echo $user_info['cb_teacherscv']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>





















    <?php if ($show_summary) : ?>
        <div class="row span8" style="padding:10px;float: right;">
<?php
            if (count ($course_info['summary_files']))
            {
                foreach ($course_info['summary_files'] as $file) :
?> 
                    <div style="display:flex;">
                        <div class="jf_col grid_6" style="flex:1;">
                            <img id="Mbanner" align="left" class="course_pic" src="<?php echo $file['url']; ?>" >
                            <div class="joomdle_course_buttons" style="text-align: center;margin:10px auto 0;">
                                <?php echo JoomdleHelperSystem::actionbutton($course_info, $free_courses_button, $paid_courses_button) ?>
                            </div>
                        </div>
                        <div class="jf_col grid_6 last-column" style="flex:1;">
                            <div class="jf_col_fluid " style="text-align:center;"><?php echo strip_tags($this->course_info["cat_description"]); ?></div>
                            <div class="jf_col_fluid " style="text-align:center;"><?php echo $course_info['fullname']; ?></div>
                            <div class="jf_col_fluid " style="text-align:center;">
                                <div class="jf_col grid_3">
                                    <!--<i class="fa fa-bookmark fa-3x" aria-hidden="true" style="font-size:1vmax">[cat name]</i>-->
                                    <!-- <i class="material-icons" >view_quilt</i> -->
                                    <div><?php echo $course_info['cat_name']; ?></div>
                                </div>
                                <div class="jf_col grid_3">
                                   <!--  <i class="material-icons" >picture_in_picture</i> -->
                                    <div><?php echo JText::_('COM_JOOMDLE_CERTIFICATE_ISSUANCE'); ?></div>
                                </div>
                                <div class="jf_col grid_3">
                                  <!--   <i class="material-icons" >date_range</i> -->
                                    <div><?php echo JHtml::_('date',date('Y-m-d',$course_info['startdate']), JText::_('DATE_FORMAT_LC1')); ?></div>
                                </div>

                                <?php if ($show_enrolperiod) : ?>
                                    <?php if (array_key_exists ('enrolperiod',$course_info)) : ?>
                                        <div class="jf_col grid_3 last-column">
                                         <!--    <i class="material-icons">sort</i>-->
                                            <div>
<?php
                                                if ($course_info['enrolperiod'] == 0)
                                                    echo JText::_('COM_JOOMDLE_UNLIMITED');
                                                else
                                                    echo ($course_info['enrolperiod'] / 86400)." ".JText::_('COM_JOOMDLE_DAYS');
?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
<?php  
                
                endforeach;
            }
?>
        </div>


        <div class="details">
            <br>
            <br>
            <hr style="background-color:#03a9f4;z-index: 800; height:2px;" />
            <div class="rightSide">
                <?php if ($show_language) : ?>
                    <?php if ($course_info['lang']) : ?>
                        <div class="jf_col_fluid joomdle_course_language" >
                            <b><?php echo JText::_('COM_JOOMDLE_LANGUAGE'); ?>:&nbsp;</b><?php echo JoomdleHelperContent::get_language_str ($course_info['lang']); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($show_startdate) : ?>
                    <div class="jf_col_fluid joomdle_course_startdate" >
                        <b><?php echo JText::_('COM_JOOMDLE_START_DATE'); ?>:&nbsp;</b>
                        <?php echo JHtml::_('date',date('Y-m-d',$course_info['startdate']), JText::_('DATE_FORMAT_LC1')); ?>
                    </div>
                <?php endif; ?>

                <?php if ($show_enroldates) : ?>
                    <?php if ((array_key_exists ('enrolstartdate',$course_info)) && ($course_info['enrolstartdate'])) : ?>
                        <div class="jf_col_fluid joomdle_course_enrolstartdate">
                            <b><?php echo JText::_('COM_JOOMDLE_ENROLMENT_START_DATE'); ?>:&nbsp;</b>
                            <?php echo JHtml::_('date',date('Y-m-d',$course_info['enrolstartdate']), JText::_('DATE_FORMAT_LC1')); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ((array_key_exists ('enrolenddate',$course_info)) && ($course_info['enrolenddate'])) : ?>
                        <div class="jf_col_fluid joomdle_course_enrolenddate">
                            <b><?php echo JText::_('COM_JOOMDLE_ENROLMENT_END_DATE'); ?>:&nbsp;</b>
                            <?php echo JHtml::_('date',date('Y-m-d',$course_info['enrolenddate']), JText::_('DATE_FORMAT_LC1')); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($show_enrolperiod) : ?>
                    <?php if (array_key_exists ('enrolperiod',$course_info)) : ?>
                        <div class="jf_col_fluid joomdle_course_enrolperiod">
                            <b><?php echo JText::_('COM_JOOMDLE_ENROLMENT_DURATION'); ?>:&nbsp;</b><?php
                            if ($course_info['enrolperiod'] == 0)
                                echo JText::_('COM_JOOMDLE_UNLIMITED');
                            else
                                echo ($course_info['enrolperiod'] / 86400)." ".JText::_('COM_JOOMDLE_DAYS');
                            ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($show_cost) : ?>
                    <?php if ($course_info['cost']) : ?>
                        <div class="jf_col_fluid joomdle_course_cost">
                            <b><?php echo JText::_('COM_JOOMDLE_COST'); ?>:&nbsp;</b><?php echo $course_info['cost']." (".
                                          ( JText::_('COM_JOOMDLE_CURRENCY_' . $course_info['currency']) == 'COM_JOOMDLE_CURRENCY_' . $course_info['currency']
                                            ? $course_info['currency'] : JText::_('COM_JOOMDLE_CURRENCY_' . $course_info['currency']) )
                                                                                                                         .")"; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php $index_url = JURI::base()."index.php"; ?>
                <?php if ($show_topicsnumber) : ?>
                    <div class="jf_col_fluid joomdle_course_topicsnumber">
                        <b><?php echo JText::_('COM_JOOMDLE_TOPICS'); ?>:&nbsp;</b><?php echo $course_info['numsections']; ?>
                    </div>
                <?php endif; ?>
            </div>


<!--
            <div class="leftSide">
                <div class="jf_col_fluid" style="left: 35px;
                                                 position: relative;
                                                 bottom: 90px;
                                                 z-index: 900;">
                    <div class="jf_col grid_3 last-column" style="width: 150px;z-index: 910;color: white;
                                                                  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                                                                  text-align: center;">
<?php
                        // master photo
                        if (is_array ($this->teachers))
                            foreach ($this->teachers as $teacher) :
?>
<?php
                                $user_info = JoomdleHelperMappings::get_user_info_for_joomla ($teacher['username']);
                                if (!count ($user_info)) //not a Joomla user
                                    continue;

                                // Use thumbs if available
                                if ((array_key_exists ('thumb_url', $user_info)) && ($user_info['thumb_url'] != ''))
                                    $user_info['pic_url'] = $user_info['thumb_url'];
?>
                                <a href="<?php echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid"); ?>" >
                                    <img src="<?php echo $user_info['pic_url']; ?>" style="padding: 10px 10px 0px 10px;">
                                </a>
                                <div class="jf_col_fluid ">
                                    <a href="<?php echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid"); ?>"><?php echo $teacher['firstname']." ".$teacher['lastname']; ?></a>
                                </div>
                            <?php endforeach; ?>
                    </div>
                </div>
            </div>
-->
        </div>
    <?php endif; ?>




<!--
<?php
// mahdi agnelli {

if (is_array ($this->mods))
{
    foreach ($this->mods as $tema)
    {
        if (  ( !($tema['name']) ) && ( !($tema['section']) )  )
        {
?>
            <div class="container">
<?php
                if ($show_summary_course_view)
                    if ($tema['summary'])
                        echo $tema['summary'];

                $resources = $tema['mods'];
                if (is_array($resources)) :
?>
<?php
                    foreach ($resources as $id => $resource) {
                        $mtype = JoomdleHelperSystem::get_mtype ($resource['mod']);
                        if (!$mtype) // skip unknow modules
                            continue;

                        $icon_url = JoomdleHelperSystem::get_icon_url ($resource['mod'], $resource['type']);
                        if ($icon_url)
                            echo '<img align="center" src="'. $icon_url.'">&nbsp;';

                        if ($resource['mod'] == 'label')
                        {
                            echo '</P>';
                            echo $resource['content'];
                            echo '</P>';
                            continue;
                        }

                        if (($this->is_enroled) && ($resource['available']))
                        {
                            $direct_link = JoomdleHelperSystem::get_direct_link ($resource['mod'], $course_id, $resource['id'], $resource['type']);
                            if ($direct_link)
                            {
                                // Open in new window if configured like that in moodle
                                if ($resource['display'] == 6)
                                    $resource_target = 'target="_blank"';
                                else
                                    $resource_target = '';

                                if ($direct_link != 'none')
                                    echo "<a $resource_target  href=\"".$direct_link."\">".$resource['name']."</a><br>";
                            }
                            else
                                echo "<a $target href=\"".$jump_url."&mtype=$mtype&id=".$resource['id']."&course_id=$course_id&create_user=0&Itemid=$itemid&redirect=$direct_link\">".$resource['name']."</a><br>";
                        }
                        else
                        {
                            echo $resource['name'] .'<br>';
                            if ((!$resource['available']) && ($resource['completion_info'] != '')) : ?>
                                <div class="joomdle_completion_info">
                                    <?php echo $resource['completion_info']; ?>
                                </div>
<?php
                            endif;
                        }

                        if ($resource['content'] != '') : ?>
                            <div class="joomdle_section_list_item_resources_content">
                                <?php echo $resource['content']; ?>
                            </div>
<?php
                        endif;
                    }
?>
                <?php endif; ?>
            </div>
<?php
        }
    }
}

// } mahdi agnelli
?>
-->



</div>


<div class="span8 joomdle_course_links">
<?php
    $cat_id = $course_info['cat_id'];
    $course_id = $course_info['remoteid'];
    if ($unicodeslugs == 1)
	{
		$course_slug = JFilterOutput::stringURLUnicodeSlug($course_info['fullname']);
		$cat_slug = JFilterOutput::stringURLUnicodeSlug($course_info['cat_name']);
	}
	else
	{
		$course_slug = JFilterOutput::stringURLSafe($course_info['fullname']);
		$cat_slug = JFilterOutput::stringURLSafe($course_info['cat_name']);
	}

    $linkstarget = $this->params->get( 'linkstarget' );
    if ($linkstarget == "new")
        $target = " target='_blank'";
    else
        $target = "";
?>
    <div class="jf_col_fluid joomdle-course<?php echo $this->pageclass_sfx;?>">
    <div class="panel-group" id="joomdle_accordion_v<?php echo $this->pageclass_sfx; ?>" role="tablist" aria-multiselectable="true">
<?php
        if ($course_info['guest'])
            $this->is_enroled = true;

        //skip intro
        //array_shift ($this->mods);
        if (is_array ($this->mods))
        {
            $i = 1;
            foreach ($this->mods as $tema) :
                // if (  ( !($tema['name']) ) && ( !($tema['section']) )  ) // mahdi agnelli
                    // continue;
?>





<!--
                <div class="jf_toggle light-blue">
                    <?php if ($show_topics_numbers) : ?>
                        <div class="trigger jf_waves_light_30 waves-effect waves-light-30" data-toggle="collapse" data-target="#joomdle_item_content_<?php echo $i; ?>" style="cursor: pointer;">
                            <span class="fa fa-plus-square" aria-hidden="true"></span>
                            <i class="fa fa-hand-o-left" aria-hidden="true"></i> 
<?php
                            $title = '';
                            if ($tema['name'])
                                $title = $tema['name'];
                            else
                            {
                                if ($tema['section'])
                                {
                                    $title =  JText::_('COM_JOOMDLE_SECTION') . " ";
                                    $title .= $tema['section'] ;
                                }
                                else
                                    $title =  JText::_('COM_JOOMDLE_INTRO');
                            }
                            echo $title;
?>
                        </div>
                    <?php endif; ?>
                    <div id="joomdle_item_content_<?php echo $i; ?>" class="container">
<?php
                        if ($show_summary_course_view)
                            if ($tema['summary'])
                                echo $tema['summary'];

                        $resources = $tema['mods'];
                        if (is_array($resources)) :
?>
<?php
                            foreach ($resources as $id => $resource) {
                                $mtype = JoomdleHelperSystem::get_mtype ($resource['mod']);
                                if (!$mtype) // skip unknow modules
                                    continue;

                                $icon_url = JoomdleHelperSystem::get_icon_url ($resource['mod'], $resource['type']);
                                if ($icon_url)
                                    echo '<img align="center" src="'. $icon_url.'">&nbsp;';

                                if ($resource['mod'] == 'label')
                                {
                                    echo '</P>';
                                        echo $resource['content'];
                                    echo '</P>';
                                    continue;
                                }

                                if (($this->is_enroled) && ($resource['available']))
                                {
                                    $direct_link = JoomdleHelperSystem::get_direct_link ($resource['mod'], $course_id, $resource['id'], $resource['type']);
                                    if ($direct_link)
                                    {
                                        // Open in new window if configured like that in moodle
                                        if ($resource['display'] == 6)
                                            $resource_target = 'target="_blank"';
                                        else
                                            $resource_target = '';

                                        if ($direct_link != 'none')
                                            echo "<a $resource_target  href=\"".$direct_link."\">".$resource['name']."</a><br>";
                                    }
                                    else
                                        echo "<a $target href=\"".$jump_url."&mtype=$mtype&id=".$resource['id']."&course_id=$course_id&create_user=0&Itemid=$itemid&redirect=$direct_link\">".$resource['name']."</a><br>";
                                }
                                else
                                {
                                    echo $resource['name'] .'<br>';
                                    if ((!$resource['available']) && ($resource['completion_info'] != '')) : ?>
                                        <div class="joomdle_completion_info">
                                            <?php echo $resource['completion_info']; ?>
                                        </div>
<?php
                                    endif;
                                }

                                if ($resource['content'] != '') : ?>
                                    <div class="joomdle_section_list_item_resources_content">
                                        <?php echo $resource['content']; ?>
                                    </div>
<?php
                                endif;
                            }
?>
                        <?php endif; ?>
                    </div>
                </div>
-->
                
                
                
                
                
                
                


                <div class="panel panel-default">
                    <a class="joomdle_accordion_v<?php echo $this->pageclass_sfx; ?>" role="button" data-toggle="collapse" data-parent_="#joomdle_accordion_v<?php echo $this->pageclass_sfx; ?>" href="#joomdle_section_content_<?php echo $i; ?>" aria-expanded="true" aria-controls="joomdle_section_content_<?php echo $i; ?>">
                        <div class="panel-heading" role="tab" id="joomdle_section_heading_<?php echo $i; ?>">
                            <h4 class="panel-title">
                                <span class="glyphicon glyphicon-plus"></span>
<?php
                                $title = '';
                                if ($tema['name'])
                                    $title = $tema['name'];
                                else
                                {
                                    if ($tema['section'])
                                    {
                                        $title =  JText::_('COM_JOOMDLE_SECTION') . " ";
                                        $title .= $tema['section'] ;
                                    }
                                    else
                                        $title =  JText::_('COM_JOOMDLE_INTRO');
                                }
                                echo $title;

                                if (is_array($tema['mods'])) :
?>
                                    <span class="badge"><?php echo count($tema['mods']); ?></span>
                                <?php endif; ?>
                            </h4>
                        </div>
                    </a>
                    <div id="joomdle_section_content_<?php echo $i; ?>" class="panel-collapse collapse in_" role="tabpanel" aria-labelledby="joomdle_section_heading_<?php echo $i; ?>">
                    
<?php
                        if ($show_summary_course_view)
                            if ($tema['summary'])
                                echo $tema['summary'];

                        $resources = $tema['mods'];
                        if (is_array($resources)) :
?>
                            <ul class="list-group list-group-striped">
<?php
                                $j = 1;
                                foreach ($resources as $id => $resource) :
?>
                                    <li class="list-group-item list-group-item-striped-<?php echo $j % 2; ?>">
<?php
                                    
                                    $mtype = JoomdleHelperSystem::get_mtype ($resource['mod']);
                                    if (!$mtype) // skip unknow modules
                                        continue;

                                    $icon_url = JoomdleHelperSystem::get_icon_url ($resource['mod'], $resource['type']);
                                    if ($icon_url)
                                        echo '<img align="center" src="'. $icon_url.'">&nbsp;';

                                    if ($resource['mod'] == 'label')
                                    {
                                        echo '</P>';
                                            echo $resource['content'];
                                        echo '</P>';
                                        continue;
                                    }

                                    if (($this->is_enroled) && ($resource['available']))
                                    {
                                        $direct_link = JoomdleHelperSystem::get_direct_link ($resource['mod'], $course_id, $resource['id'], $resource['type']);
                                        if ($direct_link)
                                        {
                                            // Open in new window if configured like that in moodle
                                            if ($resource['display'] == 6)
                                                $resource_target = 'target="_blank"';
                                            else
                                                $resource_target = '';

                                            if ($direct_link != 'none')
                                                echo "<a $resource_target  href=\"".$direct_link."\">".$resource['name']."</a><br>";
                                        }
                                        else
                                            echo "<a $target href=\"".$jump_url."&mtype=$mtype&id=".$resource['id']."&course_id=$course_id&create_user=0&Itemid=$itemid&redirect=$direct_link\">".$resource['name']."</a><br>";
                                    }
                                    else
                                    {
                                        echo $resource['name'] .'<br>';
                                        if ((!$resource['available']) && ($resource['completion_info'] != '')) : ?>
                                            <div class="joomdle_completion_info">
                                                <?php echo $resource['completion_info']; ?>
                                            </div>
<?php
                                        endif;
                                    }

                                    if ($resource['content'] != '') : ?>
                                        <div class="joomdle_section_list_item_resources_content">
                                            <?php echo $resource['content']; ?>
                                        </div>
<?php
                                    endif;
?>
                                    </li>
<?php
                                $j++;
                                endforeach;
?>
                            </ul>
                        <?php endif; ?>
                    
                    </div>
                </div>

                
                
                
                
                
                
                
                
                
                
<?php
                $i++;
            endforeach;
        }
?>
    </div>
    </div>
</div>


<div>
<?php 
    $comments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
    if (file_exists($comments)) {
?>    
        <div class="row-fluid comment-template">
<?php
            require_once($comments);
            echo JComments::showComments($course_id, 'com_joomdle', $course_info['fullname']);
?>
        </div>
<?php
    }
?>
</div>


<?php if ($this->params->get('show_back_links')) : ?>
    <div>
        <P align="center">
            <a href="javascript: history.go(-1)"><?php echo JText::_('COM_JOOMDLE_BACK'); ?></a>
        </P>
    </div>
<?php endif; ?>


<?php
$custom_javascript .= '
    window.onload = function() {
        fixBrokenImages(\'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==\');
    };';
$jdoc->addScriptDeclaration( $custom_javascript );
?>