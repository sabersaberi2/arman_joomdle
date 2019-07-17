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

    //document object
    $jdoc = JFactory::getDocument();

    //add the stylesheet
    $jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_courses_grow/assets/css/mod_joomdle_UTheme_one_slider.css');

    // slick carousel slider stylesheets and JS
    $jdoc->addStyleSheet(JURI::root ().'media/joomdle/grow/slick/slick-theme.css');
    $jdoc->addStyleSheet(JURI::root ().'media/joomdle/grow/slick/slick.css');
    $jdoc->addScript(JURI::root ().'media/joomdle/grow/slick/slick.min.js');

    // owl carousel slider stylesheets and JS
    // $jdoc->addStyleSheet(JURI::root ().'media/joomdle/grow/owlcarousel/owl.carousel.css');
    // $jdoc->addStyleSheet(JURI::root ().'media/joomdle/grow/owlcarousel/owl.theme.default.css');
    // $jdoc->addScript(JURI::root ().'media/joomdle/grow/owlcarousel/owl.carousel.js');
    // JHtml::_('jquery.framework');

    $itemid = JoomdleHelperContent::getMenuItem();

    if ($linkstarget == "new")
        $target = " target='_blank'";
    else $target = "";

    if ($linkstarget == 'wrapper')
        $open_in_wrapper = 1;
    else
        $open_in_wrapper = 0;

    $unicodeslugs = JFactory::getConfig()->get('unicodeslugs');
    $free_courses_button = $comp_params->get( 'free_courses_button' );
    $paid_courses_button = $comp_params->get( 'paid_courses_button' );

	$lang = JFactory::getLanguage();
	$lang->load('com_joomdle', JPATH_ROOT);
    
?>
    <div class="owl-carousel-joomdlecourses-m<?php echo $module->id; ?> owl-theme joomdlecourses<?php echo $moduleclass_sfx; ?>" style="display: block; margin: 0 auto;padding-bottom:20px;">
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
            <div class="grid_4 last-column joomdle_course_one_slider_container">
                <div class="jf_card">
                    <div class="card-main-oe-slider">
<?php
                        // foreach ($summary_files as $file) {
                            echo '<a style="width:100%" data-toggle="modal" data-target="'.'#modal'.$courseCounter.'" href="'. $url . '" >';
?>
                                <div class="ovimgoneslider<?php echo $course['cat_id'];?> ovimgoneslider">
								
<?php
                                    /* SUMMARY IMAGE FILE SECTION */
                                    echo '<img class="courseimgoneslider img lazy-joomdlecourses-m'.$module->id.'" hspace="0" vspace="5" align="center" data-lazy="'.$summary_file['url'].'" data-src="'.$summary_file['url'].'" >';
                                    /*feature courses*/
				echo '<div class="content_one_slider">'.'<div style="padding-right: 16px;color: #0000008a;font-size: 15px;">دوره های ویژه</div>';	
									/* COURSE TITLE SECTION */
                                    echo '<div class="joomdle_course_columns_titr_one_slider" style="">';
                                        if ($linkto == 'moodle') {
                                            if ($default_itemid)
                                                $itemid = $default_itemid;
                                            if ($username) {
                                                echo $course['fullname'];
                                            }
                                            else
                                                if ($open_in_wrapper)
                                                    echo $course['fullname'];
                                                else
                                                    echo $course['fullname'];
                                        }
                                        else {
                                            if ($joomdle_itemid)
                                                $itemid = $joomdle_itemid;
                                            $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$course['cat_id'].":".$cat_slug."&course_id=".$course['remoteid'].':'.$course_slug."&Itemid=$itemid");
                                            echo $course['fullname'];
                                        }
                                    echo '</div>';
									/*show start date*/
                                    list($gy,$gm,$gd) = explode('-',date('Y-n-d', $course['startdate']));
                                    $j_date_string = modJoomdleCoursesGrowHelper::gregorian_to_jalali($gy, $gm, $gd, '/');
									// echo '<div class="startdate">'.'شروع دوره '.date("d/m/Y", $course['startdate']).'</div>';
									echo '<div class="startdate">'.'شروع دوره '.$j_date_string.'</div>';
									/*course enrol button
									  echo JoomdleHelperSystem::actionbutton ( $course, $free_courses_button, $paid_courses_button);*/
									  /*course summery*/
										if ($course['summary'])
echo '<div class="udesc">'.JoomdleHelperSystem::fix_text_format($course['summary']).'';

echo JoomdleHelperSystem::actionbutton ( $course, $free_courses_button, $paid_courses_button);
									echo '</div>';
				echo '</div>';
									/*like heart icon*/
									echo '<i class="fa fa-heart-o fa-2x" style="color: red;position: absolute;left: 14px;top: 14px;"></i>';
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

                     

                        <?php if ($course['cost']) : ?>
                            <div class="jf_col_fluid joomdle_course_cost_one_slider">
                                <b>
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
                            <div class="jf_col_fluid joomdle_course_cost_one_slider">
                                <b><?php echo "رایگان"; ?></b>
                            </div>
                        <?php endif; ?>
                    </div>
<?php
                echo '</div>'; 
            echo '</div>'; 
            $courseCounter++;
            $courseShowLimit++;
            if ($courseShowLimit >= $limit) // Show only this number of latest courses
                break; 
        }
?>
    </div>

<!--
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $(".owl-carousel-joomdlecourses-m<?php echo $module->id; ?>").owlCarousel({
                rtl:true,
                loop:true,
                nav:true,
                margin: 10,
                items:4,
                autoHeight:true
            });
        });
    </script>
-->

    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('.owl-carousel-joomdlecourses-m<?php echo $module->id; ?>').slick({
                infinite: false,
                rtl: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: false,
                autoplaySpeed: 2000,
                adaptiveHeight: true,
                //nextArrow: '<button type="button" class="slick-next fa fa-chevron-circle-left fa-3x" aria-label="Next">Next</button>',
                //prevArrow: '<button type="button" class="slick-prev fa fa-chevron-circle-right fa-3x" aria-label="Previous">Previous</button>',
                dots: false,
                responsive: [{
                    breakpoint: 2000,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 1500,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 1300,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 1,
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

            $('.lazy-joomdlecourses-m<?php echo $module->id; ?>').slick({
              lazyLoad: 'ondemand',
              //slidesToShow: 3,
              //slidesToScroll: 3
            });
        });
    </script>