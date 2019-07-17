<?php

	// CUSTOM : Entire File

    /**
      * @package      Joomdle
      * @copyright    Qontori Pte Ltd
      * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
      */

    // no direct access
    defined('_JEXEC') or die('Restricted access');

    require_once(JPATH_ADMINISTRATOR.'/'.'components'.'/'.'com_joomdle'.'/'.'helpers'.'/'.'system.php');
    require_once(JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/mappings.php');
    // require_once(JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/shop.php'); 



    // JHtml::_('jquery.framework');
    // JHtml::_('bootstrap.framework');

    // JHtml::_('jquery.ui');
    // JHtml::_('jquery.ui', array('sortable'));



    //document object
    $jdoc = JFactory::getDocument();

    //add the stylesheet
    $jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_courses_grow/assets/css/mod_joomdle_UTheme.css');

    // slick carousel slider stylesheets and JS
    $jdoc->addStyleSheet(JURI::root ().'media/joomdle/grow/slick/slick-theme.css');
    $jdoc->addStyleSheet(JURI::root ().'media/joomdle/grow/slick/slick.css');
    $jdoc->addScript(JURI::root ().'media/joomdle/grow/slick/slick.min.js');

    // owl carousel slider stylesheets and JS
    // $jdoc->addStyleSheet(JURI::root ().'media/joomdle/grow/owlcarousel/owl.carousel.css');
    // $jdoc->addStyleSheet(JURI::root ().'media/joomdle/grow/owlcarousel/owl.theme.default.css');
    // $jdoc->addScript(JURI::root ().'media/joomdle/grow/owlcarousel/owl.carousel.js');
    // JHtml::_('jquery.framework');

    // popover stylesheet and JS
    $jdoc->addStyleSheet(JURI::root ().'media/joomdle/grow/bootstrap.min_popovers.css');
    $jdoc->addScript(JURI::root ().'media/joomdle/grow/bootstrap.min_popovers.js');

    // $jdoc->addScript(JURI::root ().'media/joomdle/grow/popper.min.js');
    // $jdoc->addScript(JURI::root ().'media/joomdle/grow/bootstrap.min.js');

    $itemid = JoomdleHelperContent::getMenuItem();

    if ($linkstarget == "new")
        $target = " target='_blank'";
    else $target = "";

    if ($linkstarget == 'wrapper')
        $open_in_wrapper = 1;
    else $open_in_wrapper = 0;

    $unicodeslugs = JFactory::getConfig()->get('unicodeslugs');

    $free_courses_button = $comp_params->get( 'free_courses_button' );
    $paid_courses_button = $comp_params->get( 'paid_courses_button' );

    // load LANG for JText::_()
	$lang = JFactory::getLanguage();
	$lang->load('com_joomdle', JPATH_ROOT);

?>
<style>
.imgwi_m {width:216px;height:122px;}
.imgwite_m {width:100px;height:100px;}
.cen_m {text-align:center;}
</style>
    <div class="owl-carousel-joomdlecourses-m<?php echo $module->id; ?> owl-theme joomdlecourses<?php echo $moduleclass_sfx; ?>" style="display: block; margin: 0 auto;">
<?php
        $courseShowLimit = 0;
        if (is_array($courses))
            $courseCounter=0;
        foreach ($courses as $id => $course) //$course_info = JoomdleHelperContent::getCourseInfo($id, $username)
        {
            $id = $course['remoteid'];

			$teachers = $course['teachers']; //$teachers = JoomdleHelperContent::getCourseTeachers($id)
            if (count($teachers) == count($teachers, COUNT_RECURSIVE))
                // array is not multidimensional
                $teacher = $teachers;
            else
                if (is_array ($teachers))
                    $teacher = array_shift($teachers);

            if ($unicodeslugs == 1) { // Joomla SEO Settings : Unicode Aliases
                $course_slug = JFilterOutput::stringURLUnicodeSlug($course['fullname']);
                $cat_slug = JFilterOutput::stringURLUnicodeSlug($course['cat_name']);
            }
            else {
                $course_slug = JFilterOutput::stringURLSafe($course['fullname']);
                $cat_slug = JFilterOutput::stringURLSafe($course['cat_name']);
            }

            $summary_file = $course['summary_files'];
            if (is_array ($summary_file))
                $summary_file = array_shift($summary_file);
            if (empty ($summary_file)) // summary_file is empty
                $summary_file["url"] = JURI::root ().'media/joomdle/grow/no-image-min.png';

			if (!array_key_exists ('cost',$course))
				$course['cost'] = 0;


            if ($linkto == 'moodle') {
                if ($default_itemid)
                    $itemid = $default_itemid;

                if ($username) {
                    $url = $moodle_auth_land_url."?username=$username&token=$token&mtype=course&id=$id&use_wrapper=$open_in_wrapper&create_user=1&Itemid=$itemid";
                }
                else
                    if ($open_in_wrapper)
                        $url = $moodle_auth_land_url."?username=guest&mtype=course&id=$id&use_wrapper=$open_in_wrapper&Itemid=$itemid";
                    else
                        $url = $moodle_url."/course/view.php?id=$id";
            }
		
            elseif ($course_itemid) {
                
                    $itemid = $course_itemid;

                $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$course['cat_id'].":".$cat_slug."&course_id=".$course['remoteid'].':'.$course_slug."&Itemid=$itemid");
            }
			else {
                if ($joomdle_itemid)
                    $itemid = $joomdle_itemid;

                $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$course['cat_id'].":".$cat_slug."&course_id=".$course['remoteid'].':'.$course_slug."&Itemid=$itemid");
            }
?>

            <div class="grid_4 last-column joomdle_course_columns">
                <div class="jf_card">
                    <div class="card-main" id="card-main_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>">
<?php
                        // foreach ($summary_files as $file) {
                            echo '<a style="width:100%" href="'. $url . '" />';
?>
                                <div class="ovimgslider<?php echo $course['cat_id'];?> ovimgslider">
<?php
                                    /* SUMMARY IMAGE FILE SECTION */
                                    echo '<img class="imgwi_m img lazy-joomdlecourses-m'.$module->id.' courseslider" hspace="0" vspace="5" align="center" data-lazy="'.$summary_file['url'].'" >';
                                    /* COURSE TITLE SECTION */
                                    echo '<p class="joomdle_course_columns_titr" style="">';
                                    $msgTrimmed = mb_substr($course['fullname'],0,40);

                                        echo $msgTrimmed. " ...";
                                    echo '</p>';
                                echo '</div>';
                            echo '</a>';
                        // }

                        /* TEACHER PHOTO SECTION */
                        /* require (JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/mappings.php') */
                        if (!empty($teacher))
                            $teacher_user_info = JoomdleHelperMappings::get_user_info_for_joomla ($teacher['username']);
                            // if (!count ($teacher_user_info)) //not a Joomla user
                                // continue;
                        else
                            $teacher_user_info['pic_url'] = JURI::root ().'media/joomdle/grow/anonymous_user_avatar_100.jpg';

                        // Use thumbs if available
                        if ((array_key_exists ('thumb_url', $teacher_user_info)) && ($teacher_user_info['thumb_url'] != ''))
                            $teacher_user_info['pic_url'] = $teacher_user_info['thumb_url'];

                        if (!(array_key_exists ('pic_url', $teacher_user_info)) || ($teacher_user_info['pic_url'] == ''))
                            $teacher_user_info['pic_url'] = JURI::root ().'media/joomdle/grow/anonymous_user_avatar_100.jpg';
?>

                        <!-- TEACHER NAME SECTION -->
                        <div class="profnme<?php echo $course['cat_id']; ?> jf_linkhover jf_linkhover2 jf_col_fluid profnme" >
                            <?php if (!empty($teacher)): ?>
                                <a href="<?php if($teacher['username']){ echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid");} ?>"><?php if($teacher['lastname']){ echo $teacher['lastname']; }?></a>
                            <?php else: ?>
                                <a href="/"><?php echo JText::_('COM_JOOMDLE_COURSE_NO_TEACHER'); ?></a>
                            <?php endif ?>
                        </div>

						<!-- TOPICS 5 STAR -->
						<div style="display:block">
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <?php echo JText::_('COM_JOOMDLE_COURSE_SUGGESTION'); ?>
                        </div>

                        <!-- TOPICS NUMBER SECTION -->
                        <!--
                        <div class="jf_col_fluid joomdle_course_topicsnumber topicnumber<?php// echo $course['cat_id']; ?>">
                            <b><?php //echo $course['numsections']." ".JText::_('COM_JOOMDLE_COURSE_SESSION'); ?></b>
                        </div>
                        -->

                        <!-- COURSE COST SECTION -->
                        <?php if ($course['cost']) : ?>
                            <div class="jf_col_fluid joomdle_course_cost" >
                                <b >
<?php
									if  ( $params->get( 'discount' ))
									{
										echo '<s>' . $course['cost']." (".
												  ( JText::_('COM_JOOMDLE_CURRENCY_' . $course['currency']) == 'COM_JOOMDLE_CURRENCY_' . $course['currency']
													? $course['currency'] : JText::_('COM_JOOMDLE_CURRENCY_' . $course['currency']) )
																 .")" . '</s>';
									}
									else
									{
										echo $course['cost']." (".
												  ( JText::_('COM_JOOMDLE_CURRENCY_' . $course['currency']) == 'COM_JOOMDLE_CURRENCY_' . $course['currency']
													? $course['currency'] : JText::_('COM_JOOMDLE_CURRENCY_' . $course['currency']) )
																 .")";
									}
?>
								</b>
                            </div>
						<?php else : ?>
                            <!--div class="jf_col_fluid joomdle_course_cost">
                                <b><?php echo JText::_('COM_JOOMDLE_COURSE_FREE_COST'); ?></b>
                            </div-->
                        <?php endif; ?>
                    </div>

                    <!-- POPUP CONTENTS -->
                    <div id="popover_container_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="MyModal MyFade" style="padding:10px; display: none;" tabindex="-1">
                        <div id="popover_dialog_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="MyModal-dialog waves-effect" style="cursor: crosshair;">
                            <div id="popover_content_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="MyModal-content" style="border-radius: 16px;padding-bottom: 20px;">

                                <!-- POPUP SUMMARY IMAGE FILE SECTION -->
                                <div id="popover_summaryimg_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="MyModalovimg<?php echo $course['cat_id']; ?> MyModalovimg">
                                    <img id="popover_summaryimg_img_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="imgwi_m MyModalimg" style="display: block;" hspace="0" vspace="5" align="center" data-lazy="<?php echo $summary_file['url']; ?>" data-src="<?php echo $summary_file['url']; ?>" >
                                </div>

                                <!-- POPUP TEACHER NAME SECTION -->
                                <div id="popover_profnme_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="MyModalprofnme MyModalprofnme<?php echo $course['cat_id']; ?> jf_linkhover jf_linkhover2 jf_col_fluid" >
                                    <b>
                                    <?php if (!empty($teacher)): ?>
                                        <a id="popover_profnme_name_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" href="<?php if($teacher['username']){ echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid");} ?>"><?php if($teacher['lastname']){ echo $teacher['lastname']; }?></a>
                                    <?php else: ?>
                                        <a id="popover_profnme_name_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" href="/"><?php echo JText::_('COM_JOOMDLE_COURSE_NO_TEACHER'); ?></a>
                                    <?php endif ?>
                                    </b>
                                </div> 

                                <!-- POPUP TOPICS NUMBER SECTION -->
                                <div id="popover_numsec_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="MyModalnumsec MyModalnumsec<?php echo $course['cat_id']; ?> jf_col_fluid ">
                                    <b id="popover_numsec_number_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>"><?php echo $course['numsections']." ".JText::_('COM_JOOMDLE_COURSE_SESSION'); ?></b>
                                </div>

                                <!-- POPUP TEACHER PHOTO SECTION -->
                                <div id="popover_profimg_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="MyModalprofimg cen_m">
                                    <img id="popover_profimg_img_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" data-lazy="<?php echo $teacher_user_info['pic_url']; ?>" class="imgwite_m">
                                </div>
                                <div style="margin:20% 0px"></div>
<?php
                                /* POPUP COURSE INFO SECTION */
                                echo '<div id="popover_courseinfo_m'.$module->id.'_'.$courseCounter.'" class="MyModal-body MyModal-body'.$course['cat_id'].'" style="padding:0px 32px; text-align-all: center">';
                                    if ($course['summary'])
                                        echo '<div id="popover_courseinfo_text_m'.$module->id.'_'.$courseCounter.'">'.JoomdleHelperSystem::fix_text_format($course['summary']).'</div>';
                                    if ($linkto == 'moodle') {
                                        echo "<a id='popover_courseinfo_link_m".$module->id."_".$courseCounter."' $target href=\"".$url." \">".JText::_('COM_JOOMDLE_COURSE_SESSION')." ...</a><br>";
                                    }
                                echo '</div>';

                                /* POPUP MORE LINK AND ENROLL BUTTON SECTION */
                                echo '<div id="popover_courselinks_m'.$module->id.'_'.$courseCounter.'" class="inlineForm center" style="padding:10px" >';
                                    // echo JoomdleHelperSystem::actionbutton ( $course );
                                    echo JoomdleHelperSystem::actionbutton ( $course, $free_courses_button, $paid_courses_button);
                                    echo "<a id='popover_courselinks_link_m".$module->id."_".$courseCounter."' style=\"direction:rtl\" href=\"".$url." \">".JText::_('COM_JOOMDLE_COURSE_SESSION')." ...</a><br>";
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
            $courseCounter++;
            $courseShowLimit++;
            if ($courseShowLimit >= $limit) // Show only this number of latest courses
                break; 
        }
?>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('.owl-carousel-joomdlecourses-m<?php echo $module->id; ?>').slick({
                infinite: false,
                rtl: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: false,
                autoplaySpeed: 2000,
                adaptiveHeight: true,
                dots: false,
                lazyLoad: 'ondemand',
                responsive: [{
                    breakpoint: 2000,
                    settings: {
                        slidesToShow: 6,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 1500,
                    settings: {
                        slidesToShow: 6,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 1300,
                    settings: {
                        slidesToShow: 6,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 500,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
            });
        });
    </script>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('div[id^=card-main_m<?php echo $module->id; ?>_]').popover({
                title: '',
                trigger: 'manual',
                html: true,
                placement: 'right',
                container: 'body',
                //template: `<div id="popover_m<?php echo $module->id; ?>" class="popover" onmouseleave='jQuery(document).ready(function($){var _that = "popover_m<?php echo $module->id; ?>";$(#{_that}).hide()});><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>`,
                template: `<div id="popover_m<?php echo $module->id; ?>" class="popover" onmouseleave="jQuery(document).ready(function($){$('[id^=popover_m]').hide()});"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>`,
                //nesbatan dorost //template: `<div id="popover_m<?php echo $module->id; ?>" class="popover" onmouseleave="jQuery(document).ready(function($){$('#popover_m<?php echo $module->id; ?>').hide()});"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>`,
                // template: '<div id="popover_m<?php echo $module->id; ?>" class="popover" onmouseleave="_that=this;setTimeout(function(){$(_that).hide;},100);"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
                content: function() {
                    return $("#popover_container_m<?php echo $module->id; ?>_" + getID(this.id)).html();
                }
            });

            function getID(p) {
                var regex = /[0-9]+/g;
                var found = p.match(regex);
                return found[found.length-1];
            }

            // var _this = null;

            // $(document).mouseover(function() {
               // var _this = this;
                // setTimeout(function () {
                        // console.log('$(document).mouseover');
                        // $('div[id^=card-main_m<?php echo $module->id; ?>_]').popover("hide")
                // }, 100);
            // });

            $('div[id^=card-main_m<?php echo $module->id; ?>_]').mouseenter(function() {
                $("#" + this.id).popover('show');
            }).mouseleave(function() {
               var _this = this;
                setTimeout(function () {
                    if (!$(".popover:hover").length) {
                        $(_this).popover("hide")
                    }
                }, 100);
            });
        });
    </script>