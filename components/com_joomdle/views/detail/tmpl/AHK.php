<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php

$course_info = $this->course_info;
$course_id = $this->course_info['remoteid'];                            // custom : course view variable
$show_topics_numbers = $this->params->get( 'course_show_numbers');      // custom : course view variable
$itemid = JoomdleHelperContent::getMenuItem();
$jump_url =  JoomdleHelperContent::getJumpURL ();                       // custom : course view variable

$show_contents_link = $this->params->get( 'show_contents_link' );
$show_topics_link = $this->params->get( 'show_topìcs_link' );
$show_grading_system_link = $this->params->get( 'show_grading_system_link' );
$show_teachers_link = $this->params->get( 'show_teachers_link' );
$show_category = $this->params->get( 'show_detail_category', 1 );
$show_summary = $this->params->get( 'show_detail_summary', 1 );
$show_summary_course_view = $this->params->get( 'course_show_summary'); // custom : course view variable
$show_language = $this->params->get( 'show_detail_language', 0 );
$show_startdate = $this->params->get( 'show_detail_startdate', 1 );
$show_enroldates = $this->params->get( 'show_detail_enroldates', 0 );
$show_enrolperiod = $this->params->get( 'show_detail_enrolperiod', 1 );
$show_topicsnumber = $this->params->get( 'show_detail_topicsnumber', 1 );
$show_cost = $this->params->get( 'show_detail_cost', 1 );
$show_motivation = $this->params->get( 'show_detail_application_motivation', 'no' );
$show_experience = $this->params->get( 'show_detail_application_experience', 'no' );
$free_courses_button = $this->params->get( 'free_courses_button' );
$paid_courses_button = $this->params->get( 'paid_courses_button' );

$user = JFactory::getUser();                                            // detail & course view variable
$user_logged = $user->id;
$username = $user->username;                                            // custom : course view variable
$session                = JFactory::getSession();                       // custom : course view variable
$token = md5 ($session->getId());                                       // custom : course view variable
$direct_link = 1;                                                       // custom : course view variable


$jdoc = JFactory::getDocument();                                                                                   // custom : document object
$jdoc->addStyleSheet(JURI::root ().'components/com_joomdle/views/detail/assets/css/com_joomdle_views_detail.css'); // custom : add the stylesheet

if (!array_key_exists ('cost',$course_info))
	$course_info['cost'] = 0;
?>

<div class="jf_col_fluid joomdle-coursedetails<?php echo $this->pageclass_sfx;?>">

<?php	if ($show_summary) : ?>
	<div class=" row " style="padding:10px;">
		<?php 
		
			if (count ($course_info['summary_files']))
			{
				foreach ($course_info['summary_files'] as $file) :
				?> 
				<div style="display:flex;">
				<div class="jf_col grid_6" style="flex:1;">
				<img id="Mbanner" align="left" class="course_pic" src="<?php echo $file['url']; ?>" >
				</div>
				<div   class="jf_col grid_6 last-column" style="flex:1;">
				
				<div class="jf_col_fluid " style="text-align:center;"><?php echo strip_tags($this->course_info["cat_description"]); ?></div>
				<div class="jf_col_fluid " style="text-align:center;"><?php echo $course_info['fullname']; ?></div>
				<div class="jf_col_fluid " style="text-align:center;">
				<div class="jf_col grid_3">
				<!--<i class="fa fa-bookmark fa-3x" aria-hidden="true" style="font-size:1vmax">اقتصاد مقاومتی</i>-->
				<i class="material-icons" style="font-size:2vmax">view_quilt</i> 
				<div style="font-size:0.75vmax"><?php echo $course_info['cat_name']; ?></div>
				</div>
				<div class="jf_col grid_3">
				<i class="material-icons" style="font-size:2vmax">picture_in_picture</i>
				<div style="font-size:0.75vmax">صدور مدرک</div>
				</div>
				<div class="jf_col grid_3">
				<i class="material-icons" style="font-size:2vmax">date_range</i>
				<div style="font-size:0.75vmax"><?php echo JHtml::_('date',date('Y-m-d',$course_info['startdate']), JText::_('DATE_FORMAT_LC1')); ?></div>
				</div>
				<div class="jf_col grid_3 last-column">
				<i class="material-icons" style="font-size:2vmax">sort</i> 
				<div style="font-size:0.75vmax"><?php   echo ($course_info['enrolperiod'] / 86400)." ".JText::_('COM_JOOMDLE_DAYS'); ?></div>
				</div>
				</div>
				
				
				
				
				
				
				
				</div>
					
					
				</div> 
				
				
				<?php  
				
				endforeach;
			}
		?>
		<hr style="background-color:#03a9f4; height:2px;" /> 
    </div>
	<div class="jf_col_fluid" style="left: 35px;
    position: relative;
    bottom: 78px;
    z-index: 900;">
				<div class="jf_col grid_3 last-column" style="width: 200px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  text-align: center;">
					<div class="jf_col_fluid ">
					<img id="Mbanner" align="left" class="course_pic" src="<?php echo $file['url']; ?>" >
					</div>
					<div class="jf_col_fluid ">
						<h6>ali </h6>
					</div>
				</div>
				</div>
	
    <div class="jf_col_fluid joomdle_course_description" >
		
       <!-- <div class="joomdle_course_name">
            <h1 class="joomdle_course_name_titr"><?php //echo $course_info['fullname']; ?></h1>
        </div>-->

        <?php if ($show_category) : ?>
        <div class="joomdle_course_category">
            <b><?php //echo JText::_('COM_JOOMDLE_CATEGORY'); ?>:&nbsp;</b><?php //echo $course_info['cat_name']; ?>
        </div>
        <?php endif; ?>

		<div>
            <b><?php //echo JText::_('COM_JOOMDLE_SUMMARY'); ?>:&nbsp;</b>
		</div>
		<?php //echo JoomdleHelperSystem::fix_text_format($course_info['summary']);
			
			Dump($this,"roholla");
			//var_Dump($course_info['summary'],"roholla2");
			 
		?>  
		
	</div>
<?php endif;  ?>

<?php	if ($show_language) : ?>
	<?php if ($course_info['lang']) : ?>
	<div class="jf_col_fluid joomdle_course_language" >
		<b><?php echo JText::_('COM_JOOMDLE_LANGUAGE'); ?>:&nbsp;</b><?php echo JoomdleHelperContent::get_language_str ($course_info['lang']); ?>
	</div>
	<?php endif; ?>
<?php endif; ?>

<?php	if ($show_startdate) : ?>
	<div class="jf_col_fluid joomdle_course_startdate" >
		<b><?php echo JText::_('COM_JOOMDLE_START_DATE'); ?>:&nbsp;</b>
		<?php echo JHtml::_('date',date('Y-m-d',$course_info['startdate']), JText::_('DATE_FORMAT_LC1')); ?>

	</div>
<?php endif; ?>


<?php	if ($show_enroldates) : ?>
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

<?php	if ($show_enrolperiod) : ?>
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


<?php	if ($show_cost) : ?>
	<?php if ($course_info['cost']) : ?>
	<div class="jf_col_fluid joomdle_course_cost">
		<b><?php echo JText::_('COM_JOOMDLE_COST'); ?>:&nbsp;</b><?php echo $course_info['cost']."(".$course_info['currency'].")"; ?>
	</div>
	<?php endif; ?>
<?php endif; ?>

<?php $index_url = JURI::base()."index.php"; ?>
<?php	if ($show_topicsnumber) : ?>
	<div class="jf_col_fluid joomdle_course_topicsnumber">
		<b><?php echo JText::_('COM_JOOMDLE_TOPICS'); ?>:&nbsp;</b><?php echo $course_info['numsections']; ?>
	</div>
<?php endif; ?>

	<div class="jf_col_fluid joomdle_course_links">
	<?php
		$cat_id = $course_info['cat_id'];
		$course_id = $course_info['remoteid'];
		$cat_slug = JFilterOutput::stringURLSafe ($course_info['cat_name']);
		$course_slug = JFilterOutput::stringURLSafe ($course_info['fullname']);

	if ($show_topics_link) : ?>
		<?php $url = JRoute::_("index.php?option=com_joomdle&view=topics&cat_id=$cat_id:$cat_slug&course_id=$course_id:$course_slug&Itemid=$itemid"); ?>
		<P><b><?php  echo "<a href=\"$url\">".JText::_('COM_JOOMDLE_COURSE_TOPICS')."</a><br>"; ?></b>
	<?php endif; ?>
	<?php
	if ($show_contents_link) : ?>
        <?php $url = JRoute::_("index.php?option=com_joomdle&view=course&cat_id=$cat_id:$cat_slug&course_id=$course_id:$course_slug&Itemid=$itemid"); ?>
        <P><b><?php  echo "<a href=\"$url\">".JText::_('COM_JOOMDLE_COURSE_CONTENTS')."</a><br>"; ?></b>
    <?php endif; ?>
	<?php
	if ($show_grading_system_link) : ?>
		<?php $url = JRoute::_("index.php?option=com_joomdle&view=coursegradecategories&cat_id=$cat_id:$cat_slug&course_id=$course_id:$course_slug&Itemid=$itemid"); ?>
		<P><b><?php  echo "<a href=\"$url\">".JText::_('COM_JOOMDLE_COURSE_GRADING_SYSTEM')."</a><br>"; ?></b>
	<?php endif; ?>
	<?php
	if ($show_teachers_link) : ?>
		<?php $url = JRoute::_("index.php?option=com_joomdle&view=teachers&cat_id=$cat_id:$cat_slug&course_id=$course_id:$course_slug&Itemid=$itemid"); ?>
		<P><b><?php  echo "<a href=\"$url\">".JText::_('COM_JOOMDLE_COURSE_TEACHERS')."</a><br>"; ?></b>
	<?php endif; ?>
	</div>

<!-- Custom -->

<?php 
$linkstarget = $this->params->get( 'linkstarget' );
if ($linkstarget == "new")
    $target = " target='_blank'";
else $target = "";
?>

<div class="jf_col_fluid joomdle-course<?php echo $this->pageclass_sfx?>">

    <?php
    
    if ($course_info['guest'])
        $this->is_enroled = true;

    //skip intro
    //array_shift ($this->mods);
    if (is_array ($this->mods)) {
        $i = 1;
        foreach ($this->mods as  $tema) : ?>
            <div class="jf_toggle deep-purple">
                <?php if ($show_topics_numbers) : ?>
                <div class="trigger jf_waves_light_30 waves-effect waves-light-30" data-toggle="collapse" data-target="#joomdle_item_content_<?php echo $i; ?>" style="cursor: pointer;">
                    <i class="fa fa-angle-left"></i>
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
                            else  $title =  JText::_('COM_JOOMDLE_INTRO');
                        }
                        echo $title;
                ?>
                </div>
                <?php endif; ?>
                <div id="joomdle_item_content_<?php echo $i; ?>" class="container">
                <?php
                        if ($show_summary_course_view)
                            if ($tema['summary'])
                                echo  $tema['summary'];
                ?>
                <?php
                    $resources = $tema['mods'];
                    if (is_array($resources)) : ?>
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
        <?php
        $i++;
        endforeach;
    }
    ?>

    <?php if ($this->params->get('show_back_links')) : ?>
        <div>
        <P align="center">
        <a href="javascript: history.go(-1)"><?php echo JText::_('COM_JOOMDLE_BACK'); ?></a>
        </P>
        </div>
    <?php endif; ?>
</div>

<!-- Custom -->

	<div class="joomdle_course_buttons">
		<?php echo JoomdleHelperSystem::actionbutton ( $course_info, $free_courses_button, $paid_courses_button) ?>
	</div>
</div>









<?php
$itemid = JoomdleHelperContent::getMenuItem();
?>
<div class="joomdle-userlist<?php echo $this->pageclass_sfx;?>">
    <h1>
        <?php echo $this->course_info['fullname'] . ': '; ?>
        <?php echo JText::_('COM_JOOMDLE_TEACHERS'); ?>
    </h1>


<?php
if (is_array ($this->teachers))
foreach ($this->teachers as  $teacher) : ?>
<?php
    $user_info = JoomdleHelperMappings::get_user_info_for_joomla ($teacher['username']);
    if (!count ($user_info)) //not a Joomla user
        continue;
?>
    <div class="joomdle_user_list_item">
        <div class="joomdle_user_list_item_pic">
			<?php
            // Use thumbs if available
            if ((array_key_exists ('thumb_url', $user_info)) && ($user_info['thumb_url'] != ''))
                $user_info['pic_url'] = $user_info['thumb_url'];
            ?>

            <a href="<?php echo JRoute::_($user_info['profile_url']."&Itemid=$itemid"); ?>"><img height='64' width='64' src="<?php echo $user_info['pic_url']; ?>"></a>
        </div>
        <div class="joomdle_user_list_item_name">
               <a href="<?php echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid"); ?>"><?php echo $teacher['firstname']." ".$teacher['lastname']; ?></a>
        </div>
    </div>
<?php endforeach; ?>

<?php if ($this->params->get('show_back_links')) : ?>
    <div>
    <P align="center">
    <a href="javascript: history.go(-1)"><?php echo JText::_('COM_JOOMDLE_BACK'); ?></a>
    </P>
    </div>
<?php endif; ?>

</div>

<?php
dump($this,"aks ostad");
?>





