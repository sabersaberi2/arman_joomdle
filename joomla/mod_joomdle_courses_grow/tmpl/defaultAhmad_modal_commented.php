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
    $jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_courses_grow/assets/css/mod_joomdle_new.css');
    
    // slick carousel slider stylesheets and JS
    $jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_courses_grow/assets/css/slick-theme.css');
    $jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_courses_grow/assets/css/slick.css');
    $jdoc->addScript(JURI::root ().'modules/mod_joomdle_courses_grow/assets/js/slick.min.js');
    
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
        $i = 0;
        if (is_array($cursos))
            $count=0;
        foreach ($cursos as $id => $curso)
        {
            $id = $curso['remoteid'];
            $course_info = JoomdleHelperContent::getCourseInfo($id, $username);

            $teachers = JoomdleHelperContent::getCourseTeachers($id);
            // $teachers = array("firstname"=>"محمد", "lastname"=>"زنجانی", "username"=>"7555");
            if (count($teachers) == count($teachers, COUNT_RECURSIVE))
                // array is not multidimensional
                $teacher = $teachers;
            else
                if (is_array ($teachers))
                    $teacher = array_shift($teachers);

            $summary_files = $curso['summary_files'];
            if (is_array ($summary_files))
                $summary_file = array_shift($summary_files);
?>
            <div class="grid_4 last-column joomdle_course_columns">
                <div class="card-main">
<?php
                    if ($curso['summary']) {
                        /* SUMMARY FILES SECTION */
                        if (count ($curso['summary_files'])) {
                            // foreach ($curso['summary_files'] as $file) {
?>
                                <!-- SUMMARY FILE IMAGE SECTION -->
                                <div class="sumimg<?php echo $curso['cat_id']; ?> sumimg">
<?php
                                    echo '<img class="img" hspace="0" vspace="5" align="center" data-lazy="'.$summary_file['url'].'" data-src="'.$summary_file['url'].'" >';
                                echo '</div>';
                                
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
                                $curso_summary = trim(strip_tags($curso['summary']));
                                $curso_summary = substr($curso_summary, 0, 250);
                                $curso_summary = trim(substr($curso_summary, 0, strrpos($curso_summary, ' '))) . " ...";
                                echo '<div class="corssummary" style="">';
                                    // echo $curso_summary;
                                    echo trim(JoomdleHelperSystem::fix_text_format(trim($curso['summary'])));
                                echo '</div>';
                            // }
                        }
                    }

                    /* COURSE MORE LINK SECTION */
                    echo '<div style="float: left; padding-left: 8px; padding-top: 10px; padding-bottom: 10px;">';
                        echo "<a style=\"direction:rtl\" "."href=\"".$url."\">[اطلاعات بیشتر]</a><br>";
                    echo '</div>';
?>
                    <!-- TEACHER NAME SECTION -->
                    <div class="profnme<?php echo $course_info['cat_id']; ?> profnme" >
                        <a href="<?php if($teacher['username']){ echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid");} ?>"><?php if($teacher['lastname']){ echo "مدرس : استاد ".$teacher['lastname']; }?></a>
                    </div>
<?php
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
                
                <div style="padding:10px" id="modal<?php echo $count; ?>" class="modal fade" tabindex="-1">
                    <div class="modal-dialog waves-effect" style="cursor: crosshair;">
                        <div class="modal-content" style="border-radius: 16px;padding-bottom: 20px;">

                            <!-- POPUP EXIT BUTTON SECTION -->
                            <div class="modal-header" style="position:absolute;z-index:110;">
                                <button class="close" type="button" data-dismiss="modal">×</button>
                                <!-- <h4 id="myModalLabel-1-demo" class="modal-title">شرح دوره</h4> -->
                            </div>

                            <!-- POPUP SUMMARY FILES SECTION -->
                            <div class="modalovimg<?php echo $curso['cat_id']; ?> modalovimg">
                                <img class="modalimg" style="display: block;" hspace="0" vspace="5" align="center" src="<?php echo $summary_file['url']; ?>" data-src="<?php echo $summary_file['url']; ?>" >
                            </div>

                            <!-- POPUP TEACHER NAME SECTION -->
                            <div class="modalprofnme modalprofnme<?php echo $course_info['cat_id']; ?> jf_linkhover jf_linkhover2 jf_col_fluid" >
                                <b><a href="<?php if($teacher['username']){ echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid");} ?>"><?php if($teacher['lastname']){ echo " استاد".$teacher['lastname']; }?></a></b>
                            </div> 

                            <!-- POPUP TOPICS NUMBER SECTION -->
                            <div class="modalnumsec modalnumsec<?php echo $course_info['cat_id']; ?> jf_col_fluid ">
                                <b><?php echo $course_info['numsections']." جلسه"; ?></b>
                            </div>

                            <!-- POPUP TEACHER PHOTO SECTION -->
                            <div class="modalprofimg ">
                                <img src="<?php echo $teacher_user_info['pic_url']; ?>">
                            </div>
                            <div style="margin:20% 0px"></div> 

<?php
                            /* POPUP COURSE INFO SECTION */
                            echo '<div class="modal-body modal-body'.$curso['cat_id'].'" style="padding:0px 32px; text-align-all: center">';
                                echo '<div>'.JoomdleHelperSystem::fix_text_format($curso['summary']).'</div>';
                                if ($linkto == 'moodle') {
                                    if ($default_itemid)
                                        $itemid = $default_itemid;

                                    if ($username) {
                                        echo "<a "."$target href=\"".$moodle_auth_land_url."?username=$username&token=$token&mtype=course&id=$id&use_wrapper=$open_in_wrapper&create_user=1&Itemid=$itemid\">اطلاعات بیشتر ...</a><br>";
                                    }
                                    else
                                        if ($open_in_wrapper)
                                            echo "<a "."$target href=\"".$moodle_auth_land_url."?username=guest&mtype=course&id=$id&use_wrapper=$open_in_wrapper&Itemid=$itemid\">اطلاعات بیشتر ...</a><br>";
                                        else
                                            echo "<a "."$target href=\"".$moodle_url."/course/view.php?id=$id\">اطلاعات بیشتر...</a><br>";
                                }
                                else {
                                    if ($joomdle_itemid)
                                        $itemid = $joomdle_itemid;

                                    $url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$curso['cat_id'].":"."&course_id=".$curso['remoteid'].':'."&Itemid=$itemid");
                                    //	$url = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$curso['cat_id'].":".JFilterOutput::stringURLSafe($curso['cat_name'])."&course_id=".$curso['remoteid'].':'.JFilterOutput::stringURLSafe($curso['fullname']));
                                }
                            echo '</div>';

                            /* POPUP MORE LINK AND ENROLL BUTTON SECTION */
                            echo '<div class="inlineForm center" style="padding:10px" >';
                                echo JoomdleHelperSystem::actionbutton ( $curso );
                                echo "<a style=\"direction:rtl\" "."href=\"".$url."\">اطلاعات بیشتر...</a><br>";
                            echo '</div>';
                        echo '</div>';
                    echo '</div>'; 
                echo '</div>'; 
            echo '</div>'; 
            $count++;
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
                slidesToShow: 3,
                margin: 10,
                slidesToScroll: 3,
                // autoplay: true,
                // autoplaySpeed: 4000,
                adaptiveHeight: true,
                // variableWidth: true,
                dots: true
            });

            $('.lazy').slick({
              lazyLoad: 'ondemand',
              slidesToShow: 3,
              slidesToScroll: 3
            });
        });
    </script>