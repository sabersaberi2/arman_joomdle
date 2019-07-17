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



	// var_export($module->id);
	// die();
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
    else
        $open_in_wrapper = 0;

    $unicodeslugs = JFactory::getConfig()->get('unicodeslugs');
    $free_courses_button = $comp_params->get( 'free_courses_button' );
    $paid_courses_button = $comp_params->get( 'paid_courses_button' );

	$lang = JFactory::getLanguage();
	$lang->load('com_joomdle', JPATH_ROOT);
    
?>
    <div class="owl-carousel-joomdlecourses-<?php echo $module->id; ?> owl-theme joomdlecourses<?php echo $moduleclass_sfx; ?>" style="display: block; margin: 0 auto;">
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
?>
            <div class="grid_4 last-column joomdle_course_columns">
                <div class="jf_card">
                    <div class="card-main" id="card-main_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>">
<?php
                        // foreach ($summary_files as $file) {
                            // echo '<a style="width:100%" data-toggle="MyModal" data-target="'.'#mo'.$courseCounter.'" href="#" >';
?>
                                <div class="ovimgslider<?php echo $course['cat_id'];?> ovimgslider">
<?php
                                    /* SUMMARY IMAGE FILE SECTION */
                                    echo '<img class="img lazy-joomdlecourses-1 courseslider" hspace="0" vspace="5" align="center" data-lazy="'.$summary_file['url'].'" data-src="'.$summary_file['url'].'" >';
                                    /* COURSE TITLE SECTION */
                                    echo '<p class="joomdle_course_columns_titr" style="">';
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
                                    echo '</p>';
                                echo '</div>';
                            // echo '</a>';
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
                                <a href="/">استاد نامشخص</a>
                            <?php endif ?>
                        </div>

						<!-- TOPICS 5 STAR -->
						<div style="display:block">
						<span class="fa fa-star checked"></span>
						<span class="fa fa-star checked"></span>
						<span class="fa fa-star checked"></span>
						<span class="fa fa-star checked"></span>
						<span class="fa fa-star checked"></span>
						پیشنهاد دانه</div>
						
						
                        <!-- TOPICS NUMBER SECTION
                        <div class="jf_col_fluid joomdle_course_topicsnumber topicnumber<?php// echo $course['cat_id']; ?>">
                            <b><?php //echo $course['numsections']." جلسه"; ?></b>
                        </div>
 -->
                        <?php if ($course['cost']) : ?>
                            <div class="jf_col_fluid joomdle_course_cost">
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
                            <div class="jf_col_fluid joomdle_course_cost">
                                <b><?php echo "رایگان"; ?></b>
                            </div>
                        <?php endif; ?>
                    </div>





                    <div id="popover_container_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="MyModal MyFade" style="padding:10px; display: none;" tabindex="-1">
                        <div id="popover_dialog_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="MyModal-dialog waves-effect" style="cursor: crosshair;">
                            <div id="popover_content_<?php echo $courseCounter; ?>" class="MyModal-content" style="border-radius: 16px;padding-bottom: 20px;">

                                <!-- POPUP SUMMARY IMAGE FILE SECTION -->
                                <div id="popover_summaryimg_<?php echo $courseCounter; ?>" class="MyModalovimg<?php echo $course['cat_id']; ?> MyModalovimg">
                                    <img id="popover_summaryimg_img_<?php echo $courseCounter; ?>" class="MyModalimg" style="display: block;" hspace="0" vspace="5" align="center" src="<?php echo $summary_file['url']; ?>" data-src="<?php echo $summary_file['url']; ?>" >
                                </div>

                                <!-- POPUP TEACHER NAME SECTION -->
                                <div id="popover_profnme_<?php echo $courseCounter; ?>" class="MyModalprofnme MyModalprofnme<?php echo $course['cat_id']; ?> jf_linkhover jf_linkhover2 jf_col_fluid" >
                                    <b>
                                    <?php if (!empty($teacher)): ?>
                                        <a id="popover_profnme_name_<?php echo $courseCounter; ?>" href="<?php if($teacher['username']){ echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid");} ?>"><?php if($teacher['lastname']){ echo $teacher['lastname']; }?></a>
                                    <?php else: ?>
                                        <a id="popover_profnme_name_<?php echo $courseCounter; ?>" href="/">استاد نامشخص</a>
                                    <?php endif ?>
                                    </b>
                                </div> 

                                <!-- POPUP TOPICS NUMBER SECTION -->
                                <div id="popover_numsec_<?php echo $courseCounter; ?>" class="MyModalnumsec MyModalnumsec<?php echo $course['cat_id']; ?> jf_col_fluid ">
                                    <b><?php echo $course['numsections']." جلسه"; ?></b>
                                </div>

                                <!-- POPUP TEACHER PHOTO SECTION -->
                                <div id="popover_profimg_<?php echo $courseCounter; ?>" class="MyModalprofimg">
                                    <img id="popover_profimg_img_<?php echo $courseCounter; ?>" src="<?php echo $teacher_user_info['pic_url']; ?>">
                                </div>
                                <div style="margin:20% 0px"></div> 

<?php
                                /* POPUP COURSE INFO SECTION */
                                echo '<div id="popover_courseinfo_'.$courseCounter.'" class="MyModal-body MyModal-body'.$course['cat_id'].'" style="padding:0px 32px; text-align-all: center">';
                                    if ($course['summary'])
                                        echo '<div id="popover_courseinfo_text_'.$courseCounter.'">'.JoomdleHelperSystem::fix_text_format($course['summary']).'</div>';
                                    if ($linkto == 'moodle') {
                                        if ($default_itemid)
                                            $itemid = $default_itemid;

                                        if ($username) {
                                            echo "<a id='popover_courseinfo_link_".$courseCounter."' $target href=\"".$moodle_auth_land_url."?username=$username&token=$token&mtype=course&id=$id&use_wrapper=$open_in_wrapper&create_user=1&Itemid=$itemid \">اطلاعات بیشتر ...</a><br>";
                                        }
                                        else
                                            if ($open_in_wrapper)
                                                echo "<a id='popover_courseinfo_link_".$courseCounter."' $target href=\"".$moodle_auth_land_url."?username=guest&mtype=course&id=$id&use_wrapper=$open_in_wrapper&Itemid=$itemid \">اطلاعات بیشتر ...</a><br>";
                                            else
                                                echo "<a id='popover_courseinfo_link_".$courseCounter."' $target href=\"".$moodle_url."/course/view.php?id=$id \">اطلاعات بیشتر...</a><br>";
                                    }
                                    else {
                                        if ($joomdle_itemid)
                                            $itemid = $joomdle_itemid;

                                        $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$course['cat_id'].":".$cat_slug."&course_id=".$course['remoteid'].':'.$course_slug."&Itemid=$itemid");
                                    }
                                echo '</div>';

                                /* POPUP MORE LINK AND ENROLL BUTTON SECTION */
                                echo '<div id="popover_courselinks_'.$courseCounter.'" class="inlineForm center" style="padding:10px" >';
                                    //echo JoomdleHelperSystem::actionbutton ( $course );
                                    echo JoomdleHelperSystem::actionbutton ( $course, $free_courses_button, $paid_courses_button);
                                    echo "<a id='popover_courselinks_link_".$courseCounter."' style=\"direction:rtl\" href=\"".$url."\">اطلاعات بیشتر...</a><br>";
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

<!--
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $(".owl-carousel-joomdlecourses-<?php echo $module->id; ?>").owlCarousel({
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
            $('.owl-carousel-joomdlecourses-<?php echo $module->id; ?>').slick({
				draggable: false,
                infinite: false,
                rtl: true,
                slidesToShow: 4,
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
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 1500,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 1300,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
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

            $('.lazy-joomdlecourses-1').slick({
              lazyLoad: 'ondemand',
              //slidesToShow: 3,
              //slidesToScroll: 3
            });
        });
    </script>

<!--
    <script type="text/javascript">
        jQuery(document).ready(function($){
            // Enables popover
            $("#kaka1").popover({
                html : true,
                placement: 'right',
                container: 'body',
                content: function() {
                  return $("#kaka2").html();
                }

            });
        });
    </script>
-->

    <script type="text/javascript">

        var getClosest = function (elem, selector) {
            // Element.matches() polyfill : https://developer.mozilla.org/en-US/docs/Web/API/Element/closest
            if (!Element.prototype.matches) {
                Element.prototype.matches =
                    Element.prototype.matchesSelector ||
                    Element.prototype.mozMatchesSelector ||
                    Element.prototype.msMatchesSelector ||
                    Element.prototype.oMatchesSelector ||
                    Element.prototype.webkitMatchesSelector ||
                    function(s) {
                        var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                            i = matches.length;
                        while (--i >= 0 && matches.item(i) !== this) {}
                        return i > -1;
                    };
            }
            // Get the closest matching element
            for ( ; elem && elem !== document; elem = elem.parentNode ) {
                if ( elem.matches( selector ) ) return elem;
            }
            return null;
        };

        function getID(p) {
            var regex = /[0-9]+/g;
            var found = p.match(regex);
            return found[found.length-1];
        }

        jQuery(document).ready(function($) {

            var mouseLeaveTimeoutVar;
            var selfMouseenter = false;
            var popover_trigger_id;

            // Enables popover
            // $("#card-main_ma_1").popover({
                // title: 'test',
                // trigger: 'manual',
                // html: true,
                // placement: 'right',
                // container: 'body',
                // content: function() {
                  // return $("#popover_container_1").html();
                // }
            // });

            // document.getElementById("card-main_ma_1").addEventListener("click", mouseClick);
            // document.getElementById("card-main_ma_1").addEventListener("mouseenter", mouseEnter);
            // document.getElementById("card-main_ma_1").addEventListener("mouseleave", mouseLeave);

            // console.log(document.getElementById("popover_dialog_1"))
            // document.getElementById("popover_dialog_1").addEventListener('mouseenter',function(event){
                // console.log('mouseover to popover container to kept show');
                // event.stopPropagation();
                // // event.preventDefault();
                // clearTimeout(mouseLeaveTimeoutVar);
            // },true);

            // document.addEventListener( 'mouseover', function ( event ) {
                // // console.log(this); // Logs output to dev tools console.
                // if ( event.target && (event.target !== this) && event.target.id.startsWith('popover') ) {
                    // event.stopPropagation();
                    // console.log('mouseover to : /' + event.target.id + '/ to kept show');
                    // clearTimeout(mouseLeaveTimeoutVar);
                // };
            // },true);

            // Enables popover
            $('div[id^=card-main_m<?php echo $module->id; ?>_]').popover({
                title: 'test',
                trigger: 'manual',
                html: true,
                placement: 'right',
                container: 'body',
                content: function() {
                    // var $input = $( this );
                    // console.log($input);
                    return $("#popover_container_m<?php echo $module->id; ?>_" + getID(this.id)).html();
                }
            }).mouseenter({
                delay_time: 120,
            },mouseEnter).mouseleave({
                delay_time: 120,
            },mouseLeave).click(mouseClick);

            document.addEventListener( 'mouseover', function ( event ) {
                // if ( event.target && event.target.parentNode.id.startsWith('popover') ) {
                // if ( event.target && event.target.closest("div").id.startsWith('popover') ) {
                // if ( event.target && getClosest(event.target, 'div[id^="popover_"]') ) {
                // if ( event.target && getClosest(event.target, 'div[class^="popover-"],div[id^="popover"]') ) {
                if ( event.target && getClosest(event.target, 'div[id^="popover_dialog_m<?php echo $module->id; ?>_"]') ) {
                    if (selfMouseenter == false) {
                        clearTimeout(mouseLeaveTimeoutVar);
                        selfMouseenter = true;
                        console.log('mouseover to popover container to kept show');
                    };
                } else {
                    if (selfMouseenter == true) {
                        $("#" + popover_trigger_id).trigger('mouseleave');
                        console.log('mouseover to other objects from popover container to hide');
                    };
                };
            });

            function mouseClick(e) {
                $("#" + this.id).popover('toggle');
                // console.log('mouseClick to toggle popover');
            }

            function mouseEnter(e) {
                $('div[id^=card-main_m<?php echo $module->id; ?>_]').popover('hide');

                popover_trigger_id = this.id;
                if (selfMouseenter == false) {
                    // set an delay_time-milliseconds
                    setTimeout(
                        function() 
                        {
                            $("#" + popover_trigger_id).popover('show');
                            // console.log('mouseEnter to show popover');
                        }, e.data.delay_time
                    );
                };
            }

            function mouseLeave(e) {
                popover_trigger_id = this.id;
                // set an delay_time-milliseconds
                mouseLeaveTimeoutVar = setTimeout(
                    function() 
                    {
                        $("#" + popover_trigger_id).popover('hide');
                        selfMouseenter = false;
                        // console.log('mouseLeave to hide popover');
                    }, e.data.delay_time
                );
            }

        });

    </script>