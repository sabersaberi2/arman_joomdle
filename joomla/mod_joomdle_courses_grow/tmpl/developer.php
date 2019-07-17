<?php
///public_html/modules/mod_joomdle_courses_grow/tmpl
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
 #g-showcase,#g-feature,#g-subfeature{padding:1.5rem 6rem;}
 #g-showcase{background-color:#f0f0f0;}
 .g-content{padding:0;}
 #g-showcase,#g-showcase h1, #g-showcase h2, #g-showcase h3, #g-showcase h4, #g-showcase h5, #g-showcase h6, #g-showcase strong{color:#000;}
@media screen and (max-width: 600px){
    #g-showcase,#g-feature,#g-subfeature{padding:1.5rem 1rem;}
#g-main{min-height:550px !important;}
#g-subfeature .g-title{margin-top:-180px;}
}
#g-subfeature .g-title{color: #fff;}
.text_to_html * {font-size:12px !important;font-family:'IRANSansWeb'}
input[type="button"], input[type="reset"], input[type="submit"]{font-family:'IRANSansWeb'}
.popover-content{    background-color: #fff;
    border: 1px solid #dedfe0;
    box-shadow: 0 0 1px 1px rgba(20,23,28,.1), 0 3px 1px 0 rgba(20,23,28,.1);
    color: #29303b;
    padding: 16px;
    font-size: 13px;
    max-width: 288px;
    position: relative;
    z-index: 1060;
}
.btn-primary:hover {
    color: #fff;
    background-color: #992337;
    border-color: transparent;
}
.btn-group-lg>.btn, .btn-lg {
    padding: 16px 12px;
    font-size: 15px;
    line-height: 1.35135;
    border-radius: 2px;
}
.btn {
    display: inline-block;
    margin-bottom: 0;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    touch-action: manipulation;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    white-space: nowrap;
    padding: 11px 12px;
    font-size: 15px;
    line-height: 1.35135;
    border-radius: 2px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
.btn-primary {
    color: #fff;
    background-color: #ec5252;
    border: 1px solid transparent;
}
.fa-heart-o:hover {   color: #ec5252;}
.imgwi_m{width:100%;height:100%;}

.g-container{width:100% !important;}
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
			//dump($itemid,"itemidmojtaba");
?>
            <div class="grid_4 last-column joomdle_course_columns" >
                <div class="jf_card" style="background-color:#fff;padding:10px;">
                <?php if ($courseCounter==0){  ?>
                    <div class="card-main" id="card-main_f<?php echo $module->id; ?>_<?php echo $courseCounter; ?>">
                <?php }else{  ?>
                    <div class="card-main" id="card-main_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>">
                <?php }
                        // foreach ($summary_files as $file) {
                            echo '<a style="width:100%" href="'. $url . '" />';
?>
                                <div class="ovimgslider<?php echo $course['cat_id'];?> ovimgslider">
<?php
                                    /* SUMMARY IMAGE FILE SECTION */
                                    echo '<img class="imgwi_m img lazy-joomdlecourses-m'.$module->id.' courseslider" hspace="0" vspace="5" align="center" data-lazy="'.$summary_file['url'].'" >';
                                    /* COURSE TITLE SECTION */
                                    echo '<p class="joomdle_course_columns_titr" style="color:#000;height:55px;">';
                                    $msgTrimmed = mb_substr($course['fullname'],0,40);
                                        echo $msgTrimmed. " ...";
                                    echo '</p>';
                                echo '</div>';
                            echo '</a>';
                        // }
                        echo '<br/>';
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
                                <a style="color:#00ff00;" href="<?php if($teacher['username']){ echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid");} ?>"><?php if($teacher['lastname']){ echo $teacher['lastname']; }?></a>
                            <?php else: ?>
                                <a style="color:#00ff00;" href="/"><?php echo JText::_('COM_JOOMDLE_COURSE_NO_TEACHER'); ?></a>
                            <?php endif ?>
                        </div>

						<!-- TOPICS 5 STAR -->
						<div style="display:block">
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <?php //echo JText::_('COM_JOOMDLE_COURSE_SUGGESTION'); ?>
                        </div>

                        <!-- TOPICS NUMBER SECTION -->
                        <!--
                        <div class="jf_col_fluid joomdle_course_topicsnumber topicnumber<?php// echo $course['cat_id']; ?>">
                            <b><?php //echo $course['numsections']." ".JText::_('COM_JOOMDLE_COURSE_SESSION'); ?></b>
                        </div>
                        -->
                        <!-- COURSE COST SECTION -->
                        <?php if ($course['cost']) : ?>
                        
                            <div class="jf_col_fluid joomdle_course_cost" style="text-align:left;color:#bbb5b5;">
                                <b>
<?php
									if  ( $params->get( 'discount' ))
									{
										echo '<s>' . number_format($course['cost'])." (".
												  ( JText::_('COM_JOOMDLE_CURRENCY_' . $course['currency']) == 'COM_JOOMDLE_CURRENCY_' . $course['currency']
													? $course['currency'] : JText::_('COM_JOOMDLE_CURRENCY_' . $course['currency']) )
																 .")" . '</s>';
									}
									else
									{
										echo number_format($course['cost'])." (".
												  ( JText::_('COM_JOOMDLE_CURRENCY_' . $course['currency']) == 'COM_JOOMDLE_CURRENCY_' . $course['currency']
													? $course['currency'] : "تومان" )
																 .")";
									}
?>
								</b>
                            </div>
						<?php else : ?>
                            <div class="jf_col_fluid joomdle_course_cost" style="color:#fff">
                                <b><?php //echo JText::_('COM_JOOMDLE_COURSE_FREE_COST'); ?></b>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- POPUP CONTENTS 
                    <div id="popover_container_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="MyModal MyFade" style="padding:10px; display: none;" tabindex="-1">
                        <div id="popover_dialog_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="MyModal-dialog waves-effect" style="cursor: crosshair;">
                            <div id="popover_content_m<?php echo $module->id; ?>_<?php echo $courseCounter; ?>" class="MyModal-content" style="border-radius: 16px;padding-bottom: 20px;">


								
                                <div style="margin:5% 0px"></div>
<?php
									echo '<h3 class="joomdle_course_columns_titr" style="">';
                                        echo $course['fullname'];
                                    echo '</h3>';
?>
									<div style="font-size:10px;font-family:'IRANSansWeb'">
										<span class="fa fa-calendar"></span>&nbsp&nbsp پنج شنبه 16 اسفند
										<span class="fa fa-print"></span>&nbsp&nbspصدورمدرک
										<span class="fa fa-list"></span>&nbsp&nbspمتفرقه
									</div>

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
                                    //echo '<span style="color:red;font-size:24px;" class="fa fa-heart-o"></span>';
									
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div> -->';
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
        });
    </script>

    <script type="text/javascript">
        jQuery(document).ready(function($){
			$('input[type=button]').addClass("btn");
			$('input[type=button]').addClass("btn-block");
			$('input[type=button]').addClass("add-to-cart");
			$('input[type=button]').addClass("btn-lg");
			$('input[type=button]').addClass("btn-primary");
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
            $('div[id^=card-main_f<?php echo $module->id; ?>_]').popover({
                title: '',
                trigger: 'manual',
                html: true,
                placement: 'left',
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
                //$(".card-main").popover("hide");
                $("#" + this.id).popover('show');
            }).mouseleave(function() {
               var _this = this;
                setTimeout(function () {
                    if (!$(".popover:hover").length) {
                        $(_this).popover("hide")
                    }
                }, 100);
            });

            $('div[id^=card-main_f<?php echo $module->id; ?>_]').mouseenter(function() {
                //$(".card-main").popover("hide");
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