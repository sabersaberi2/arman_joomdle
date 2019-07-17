<?php

/**
* @version		1.0.1
* @package		Joomdle - Mod Display List of Moodle Teachers (grow)
* @copyright	Qontori Pte Ltd
* @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/mappings.php') ;

    // document object
    $jdoc = JFactory::getDocument();

    //add the stylesheet
    $jdoc->addStyleSheet(JURI::root ().'modules/mod_joomdle_teachers_grow/assets/css/udteachers.css');

    // slick carousel slider stylesheets and JS
    $jdoc->addStyleSheet(JURI::root ().'media/joomdle/grow/slick/slick-theme.css');
    $jdoc->addStyleSheet(JURI::root ().'media/joomdle/grow/slick/slick.css');
    $jdoc->addScript(JURI::root ().'media/joomdle/grow/slick/slick.min.js');

?>
    <div class="owl-carousel-joomdleteachers_grow-m<?php echo $module->id; ?>">
<?php
        $courseItemid = $comp_params = JComponentHelper::getParams( 'com_joomdle' )->get( 'courseview_itemid' );
        $teacherItemid = $comp_params = JComponentHelper::getParams( 'com_joomdle' )->get( 'default_itemid' ); // we get teacher itemid from defult itemid can be set in Joomdle>Cofiguration>LinkBehaviour> default itemid
        // dump(JComponentHelper::getParams( 'com_joomdle' )->get('default_itemid'), "allitemid");
        if (is_array($teachers))
        {
            $j=0;
            foreach ($teachers as $id => $teacher)
            {
                // $j++;
                // echo " $j - ";
                // $i=0;
                foreach ($teachers_excluded as $teachers_excluded_counter => $teachers_excluded_user)
                {
                    // $i++;
                    // var_export($teachers_excluded);
                    // echo $teachers_excluded_user . '<br />';
                    // echo " $i- " . $teacher['username'] . ' =? ' . $teachers_excluded_user . " : " . (boolean)($teacher['username'] == $teachers_excluded_user) . '<br />';
                    
                    if ($teacher['username'] == $teachers_excluded_user)
                        continue 2;
                }
                // die();

                if (!empty($teacher))
                    $teacher_user_info = JoomdleHelperMappings::get_user_info_for_joomla ($teacher['username']);
                    // if (!count ($teacher_user_info)) //not a Joomla user
                        // continue;
                else
                    $teacher_user_info['pic_url'] = JRoute::_(JURI::root ().'media/joomdle/grow/anonymous_user_avatar_100.jpg');

                // Use thumbs if available
                if ((array_key_exists ('thumb_url', $teacher_user_info)) && ($teacher_user_info['thumb_url'] != ''))
                    $teacher_user_info['pic_url'] = $teacher_user_info['thumb_url'];

                if (!(array_key_exists ('pic_url', $teacher_user_info)) || ($teacher_user_info['pic_url'] == ''))
                    $teacher_user_info['pic_url'] = JRoute::_(JURI::root ().'media/joomdle/grow/anonymous_user_avatar_100.jpg');
                $itemid = $comp_params = JComponentHelper::getParams( 'com_joomdle' )->get( 'courseview_itemid' );
                //dump($teacherItemid, "teacherItemid");
                $url = JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$teacherItemid");
                // $teacherCoursesCount = count( JoomdleHelperContent::call_method('teacher_courses', $teacher['username'], 'summary') );
                echo '<div class="alaki" style="margin:0px 5px">';  
                    echo '<div class="eachteacher">';
                        echo '<img style="clip-path: circle();" class="teacherpic lazy-joomdleteachers_grow-m'.$module->id.'" data-lazy="'.$teacher_user_info['pic_url'].'" data-src="'.$teacher_user_info['pic_url'].'" >';
                        echo'<div class="teachername">';
                            echo "<div><a href=\"".$url."\">".$teacher['firstname']. " " . $teacher['lastname'] ."</a></div>";
                            echo "<div><span>". $teacher['coursecounter'] . " دوره" . "</span></div>";
                        echo'</div>';	
                    echo '</div>';
                echo '</div>';
			}
        }
?>
    </div>
 <script type="text/javascript">
        jQuery(document).ready(function($){
            $('.owl-carousel-joomdleteachers_grow-m<?php echo $module->id; ?>').slick({
                infinite: false,
                rtl: true,
                slidesToShow: 6,
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

            $('.lazy-joomdleteachers_grow-m<?php echo $module->id; ?>').slick({
              lazyLoad: 'ondemand',
              //slidesToShow: 3,
              //slidesToScroll: 3
            });
        });
    </script>