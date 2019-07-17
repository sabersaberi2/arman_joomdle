<?php
    /**
      * @package      Joomdle
      * @copyright    Qontori Pte Ltd
      * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
      */

    // CUSTOM : Entire File
    // dump($Variable,"Message");
    
    // no direct access
    defined('_JEXEC') or die('Restricted access');

    require_once(JPATH_ADMINISTRATOR.'/'.'components'.'/'.'com_joomdle'.'/'.'helpers'.'/'.'system.php');
    require_once(JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/mappings.php');
    // require_once(JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/shop.php'); 

    //document object
    $jdoc = JFactory::getDocument();

    //add the stylesheet
    $jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_courses_grow/assets/css/mod_joomdle.css');

    // slick carousel slider stylesheets and JS
    $jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_courses_grow/assets/slick/slick-theme.css');
    $jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_courses_grow/assets/slick/slick.css');
    $jdoc->addScript(JURI::root ().'modules/mod_joomdle_courses_grow/assets/slick/slick.min.js');

    // owl carousel slider stylesheets and JS
    // $jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_courses_grow/assets/owlcarousel/owl.carousel.css');
    // $jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_courses_grow/assets/owlcarousel/owl.theme.default.css');
    // $jdoc->addScript(JURI::root ().'modules/mod_joomdle_courses_grow/assets/owlcarousel/owl.carousel.js');
    // JHtml::_('jquery.framework');

    $itemid = JoomdleHelperContent::getMenuItem();

    if ($linkstarget == "new")
        $target = " target='_blank'";
    else $target = "";

    if ($linkstarget == 'wrapper')
        $open_in_wrapper = 1;
    else
        $open_in_wrapper = 0;
?>
    <div class="owl-carousel owl-theme joomdlecourses<?php echo $moduleclass_sfx; ?>" style="display: block; margin: 0 auto;">
<?php
        $courseShowLimit = 0;
        // if (is_array($cursos))
            // $cursCounter=0;
        foreach ($cursos as $id => $curso)
        {
            $id = $curso['remoteid'];
            $course_info = JoomdleHelperContent::getCourseInfo($id, $username);

            $haveTeacher = false;
            $teachers = JoomdleHelperContent::getCourseTeachers($id); // $teachers = array("firstname"=>"محمد", "lastname"=>"زنجانی", "username"=>"7555");
            if (count($teachers) == count($teachers, COUNT_RECURSIVE))
                $teacher = $teachers; // array is not multidimensional
            else
                if (is_array ($teachers))
                    $teacher = array_shift($teachers);
            if (!($teacher==null))
                $haveTeacher = true;
/*
            $haveSummaryFiles = false;
            $summary_files = $curso['summary_files'];
            $summary_file = $summary_files;
            if (is_array ($summary_files))
                $summary_file = array_shift($summary_files);
            if (!($summary_file==null))
                $haveSummaryFiles = true;
*/
?>
            <div class="grid_4 last-column joomdle_course_columns">
                <div class="card-main">
<?php
                    /* SUMMARY IMAGE FILES SECTION */
                    if (count ($curso['summary_files'])) {
                        foreach ($curso['summary_files'] as $imageFileID => $imageFile) {
?>
                            <!-- SUMMARY FILE IMAGE SECTION -->
                            <div class="sumimg<?php echo $curso['cat_id']; ?> sumimg">
<?php
                                echo '<img class="img'.$imageFileID.' img" hspace="0" vspace="5" align="center" data-lazy="'.$imageFile['url'].'" data-src="'.$imageFile['url'].'" >';
                            echo '</div>';
                        }
                    }

                    /* COURSE TITLE SECTION */
                    echo '<div class="corstitle',$curso['cat_id'],' corstitle">';
                        echo '<p class="joomdle_course_columns_titr" style="">';
                            if ($linkto == 'moodle') {
                                if ($default_itemid)
                                    $itemid = $default_itemid;
                                if ($username) {
                                    echo $curso['fullname'];
                                }
                                else
                                    if ($open_in_wrapper)
                                        echo $curso['fullname'];
                                    else
                                        echo $curso['fullname'];
                            }
                            else {
                                if ($joomdle_itemid)
                                    $itemid = $joomdle_itemid;

                                $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$curso['cat_id']."&course_id=".$curso['remoteid']."&Itemid=$itemid");
                                // $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$curso['cat_id'].":"."&course_id=".$curso['remoteid'].':'."&Itemid=$itemid");

                                echo $curso['fullname'];
                            }
                        echo '</p>';
                    echo '</div>';

                    /* COURSE SUMMARY SECTION */
                    if ($curso['summary']) {
                        $curso_summary = trim(strip_tags($curso['summary']));
                        $curso_summary = substr($curso_summary, 0, 250);
                        $curso_summary = trim(substr($curso_summary, 0, strrpos($curso_summary, ' '))) . " ...";
                        echo '<div class="corssummary" style="">';
                            // echo $curso_summary;
                            echo trim(JoomdleHelperSystem::fix_text_format(trim($curso['summary'])));
                        echo '</div>';
                    }

                    /* COURSE MORE LINK SECTION */
                    if ($linkto != 'moodle') {
                        echo '<div style="float: left; padding-left: 8px; padding-top: 10px; padding-bottom: 10px;">';
                            echo "<a style=\"direction:rtl\" "."href=\"".$url."\">[اطلاعات بیشتر]</a><br>";
                        echo '</div>';
                    }

                    /* TEACHER NAME SECTION */
                    echo '<div class="profnme' . $course_info['cat_id'] . ' profnme" >';
                        if ($haveTeacher==true){
                            if ($teacher['username'])
                                $teacherLink = JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid");
                            if ($teacher['lastname'])
                                $teacherPresentation = "مدرس : استاد " . $teacher['lastname'];
                        }
                        else {
                            $teacherLink = "#";
                            $teacherPresentation = "مدرس : نامشخص";
                        }
                        echo '<a href="' . $teacherLink . '">' . $teacherPresentation . '</a>';
                    echo '</div>';

                    /* COURSE ENROLL BUTTON SECTION */
                    echo '<div class="inlineForm center" style="padding:10px" >';
                        echo JoomdleHelperSystem::actionbutton ( $curso );
                    echo '</div>';
                    // file_put_contents(JPATH_ADMINISTRATOR."/XXX.txt", str_repeat("*",10) . PHP_EOL . "\$curso['summary'] : " . PHP_EOL . trim(strip_tags($curso['summary'])) . PHP_EOL, FILE_APPEND);
                    // echo '<div>'.$curso['cat_description'].'</div>';
?>
                    <!-- TOPICS NUMBER SECTION -->
                    <!--
                    <div class="jf_col_fluid joomdle_course_topicsnumber topicnumber<?php echo $course_info['cat_id']; ?>">
                        <b><?php echo $course_info['numsections']." جلسه"; ?></b>
                    </div>
                    -->
                </div>
<?php
            echo '</div>'; 
            // $cursCounter++;

            $courseShowLimit++;
            if ($courseShowLimit >= $limit) // Show only this number of latest courses
                break; 
        }
?>
    </div>
<!--
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $(".owl-carousel").owlCarousel({
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
            $('.owl-carousel').slick({
                infinite: true,
                rtl: true,
                slidesToShow: 6,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                adaptiveHeight: true,
                // nextArrow: '<button type="button" class="slick-next fa fa-chevron-circle-left" aria-label="Next">Next</button>',
                // prevArrow: '<button type="button" class="slick-prev fa fa-chevron-circle-right" aria-label="Previous">Previous</button>',
                dots: true,
                responsive: [{
                    breakpoint: 2000,
                    settings: {
                        slidesToShow: 6,
                        slidesToScroll: 1
                    }
                },{
                    breakpoint: 1500,
                    settings: {
                        slidesToShow: 5,
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

            $('.lazy').slick({
              lazyLoad: 'ondemand',
              slidesToShow: 3,
              slidesToScroll: 3
            });
        });
    </script>