<?php 

    /**
      * @package      Joomdle
      * @copyright    Qontori Pte Ltd
      * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
      */

    defined('_JEXEC') or die('Restricted access');

    $itemid = JoomdleHelperContent::getMenuItem();
    $unicodeslugs = JFactory::getConfig()->get('unicodeslugs');

    $document = JFactory::getDocument();
    $document->addStyleSheet(JURI::root ().'/components/com_joomdle/views/teacher/assets/css/ud.css');

    // mahdi agnelli {
    $otherlanguage = JFactory::getLanguage();
    $otherlanguage->load( 'com_joomdle', JPATH_SITE );

    /**
     *
     * @function  array_combine(array $keys , array $values) : array ; Creates an array by using one array for keys and another for its values
     * @function  array_map(callable $callback , array $array1 [, array $... ]) : array ; Applies the callback to the elements of the given arrays
     *
     */
    $this->user_info = array_combine(array_map(function($old_user_info_k, $old_user_info_v) {

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

    }, array_keys($this->user_info), $this->user_info),$this->user_info);
    // } mahdi agnelli

?>
<style>
.g-container{width:95%;}
.g-content{margin:0;padding:0;}
#g-navigation{background-color:#303642 !important;}
.list-view-course-card--container{background-color:#eee;}
.list-view-course-card--headline-and-instructor-container span div h1,.list-view-course-card--headline-and-instructor-container span div {font-size:12px !important;}
hr{height:2px;border-bottom:2px solid black;margin:0;}
.text_to_html *{color:black;}
.g-menu-item---module-U2YkH{margin-top:8px;}
.myshortlist {width:98%;}
.col-md-3{margin-top:-150px;margin-right:7%;}
.list-view-course-card--image-container{width:150px;}
@media (max-width: 502px)
{
	.myshortlist {width:93%;}
	.col-md-3{margin-top:8px;}
	.list-view-course-card--image-container{width:50px;}
}
</style>

    <div class="joomdle-teacher<?php echo $this->pageclass_sfx;?>">
	
        <div width="100%" cellpadding="4" cellspacing="0" border="0" align="right" class="col-md-8 contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
            <tr>
                <td width="90%" height="20" class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
                    <?php echo JText::_('COM_JOOMDLE_TEACHED_COURSES'); ?>
                </td>
            </tr>
        </div>	
	
<div style="padding: 5px 35px;" class="col-md-8 teachercoursesprofile ">
<?php
            if (is_array ($this->courses))
                foreach ($this->courses as $id => $course)
                {
                    $summary_file = $course['summary_files'];
                    if (is_array ($summary_file))
                        $summary_file = array_shift($summary_file);
                    if (empty ($summary_file)) // summary_file is empty
                        $summary_file["url"] = JURI::root ().'media/joomdle/grow/no-image-min.png';

                    if (!array_key_exists ('cost',$course))
                        $course['cost'] = 0;

                    if ($unicodeslugs == 1) {
                        $course_slug = JFilterOutput::stringURLUnicodeSlug($course['fullname']);
                        $cat_slug = JFilterOutput::stringURLUnicodeSlug($course['cat_name']);
                    }
                    else {
                        $course_slug = JFilterOutput::stringURLSafe($course['fullname']);
                        $cat_slug = JFilterOutput::stringURLSafe($course['cat_name']);
                    }

                    $course_link = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$course['cat_id']."-$cat_slug&course_id=".$course['remoteid']."-$course_slug&Itemid=$itemid");

                    echo '<div class="list-view-course-card--course-card-wrapper">';
                        
                            echo '<div class="list-view-course-card--container list-view-course-card--bottom-border">';
							echo "<a href=\"$course_link\">";
                                echo '<div class="list-view-course-card--image-container">';
                                    /* SUMMARY IMAGE FILE SECTION */
                                    echo '<img class="img" hspace="0" vspace="5" align="center" src="'.$summary_file['url'].'" data-src="'.$summary_file['url'].'" >';
                                echo '</div>';
								echo '</a>';
                                echo '<div class="list-view-course-card--content">';
echo '<div>';
                                    echo "<a href=\"$course_link\">";
									echo '<div class="list-view-course-card--title"><h4>';
                                        /* COURSE TITLE SECTION */
                                        echo $course['fullname'];
                                    echo '</h4></div>';
									echo '</a>';
                                    echo '<div class="list-view-course-card--headline-and-instructor-container">';
                                        /* COURSE SUMMARY SECTION */
                                        echo '<span>' . $course['summary'] . '</span>';
                                    echo '</div>';
echo '</div>';
                                    echo '<div class="list-view-course-card--price-container">';

                                        echo '<span>';
										echo '<div style="color:red;font-size:14px;padding:5px;">
								<span class="glyphicon glyphicon-star"></span>
								<span class="glyphicon glyphicon-star"></span>
								<span class="glyphicon glyphicon-star"></span>
								<span class="glyphicon glyphicon-star"></span>
								<span class="glyphicon glyphicon-star-empty"></span>
							</div>';
                                            /* COURSE ORIGINAL PRICE SECTION */
                                            if ($course['cost'])
                                                echo $course['cost'] . '<span style="opacity: 0.5;"> (' .
                                                  ( JText::_('COM_JOOMDLE_CURRENCY_' . $course['currency']) == 'COM_JOOMDLE_CURRENCY_' . $course['currency']
                                                    ? $course['currency'] : JText::_('COM_JOOMDLE_CURRENCY_' . $course['currency']) )
                                                                                                                                 . ")</span>";
                                            else
                                                echo 'رایگان';
                                        echo '</span>';

                                    echo '</div>';

                                echo '</div>';
                            echo '</div>';
                        
                    echo '</div>';
					echo '<hr/>';
                }
?>

        </div>
		<div class="clearfix visible-xs"></div>
            <div class="col-md-3 author-info wi-100" >
                <div class="authorInfoParent wi-100 flex-col al-center jus-start" typeof="Person">
                    <div class="anniversary flex-col">
                        <i aria-hidden="true" class="udregistersince fa fa-birthday-cake"><br>
							<span style=' font-family: "IRANSansWeb", "WebYekan", "tahoma", sans-serif;font-size: 10px;padding:2px 2px;'>
<?php
                            $date = new DateTime($this->user_info['registerDate']);
                            $interval = $date->diff(new DateTime());
                            // echo $date->diff(new DateTime())->format("%y سال, %m ماه و %d روز و %h ساعت و %i دقیقه");
                            // echo $date->diff(new DateTime())->format("%y سال و %m ماه");
                            // var_export($date->diff(new DateTime()));
                            // die();
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
                    if ((array_key_exists ('thumb_url', $this->user_info)) && ($this->user_info['thumb_url'] != ''))
                        $this->user_info['pic_url'] = $this->user_info['thumb_url'];

                    if (!(array_key_exists ('pic_url', $this->user_info)) || ($this->user_info['pic_url'] == ''))
                        $this->user_info['pic_url'] = JURI::root ().'media/joomdle/grow/anonymous_user_avatar_100.jpg';
?>
                    <div class="teacherproilepic joomdle_user_pic">
                        <img height="64" width="64" src="<?php echo $this->user_info['pic_url']; ?>">
                    </div>
<?php
                    if ( !(array_key_exists('profile_url', $this->user_info)) )
                        $this->user_info['profile_url'] = '#';
?>
                    <h3 style="text-align:center"><a class="nameLink" href="<?php echo JRoute::_($this->user_info['profile_url']."&Itemid=$itemid");// $itemid=956 for udemy template?>">
                        <?php echo $this->user_info['name']; ?>
                    </a></h3>
					<div class="titles-teacher">
						<img src='/images/titles-teacher.png' style='width:100%;' /><br/>
						<div class="row" style="text-align:center;">
							<div class="col-md-4 col-xs-4"><img src='/images/tea1.png' /></div>
							<div class="col-md-4 col-xs-4"><img src='/images/tea2.png' /></div>
							<div class="col-md-4 col-xs-4"><img src='/images/tea3.png' /></div>
						</div>
						<div class="row" style="text-align:center;">
							<div class="col-md-4 col-xs-4"><?php echo count($this->courses); ?></div>
							<div class="col-md-4 col-xs-4">4.33</div>
							<div class="col-md-4 col-xs-4">254 نظر</div>
						</div><br/>
						<div style='text-align:center;'>
							<span class="glyphicon glyphicon-user"></span> 481,923 دانشجو
						</div>
					</div>
					<div class="titles" style='text-align:center;'>
						<img src='/images/aboutAuthor.png' style='width:100%;' /><br/>
						 <?php echo $this->user_info['cb_teacherscv']; ?>
					</div>
					<br/>
					<div style='text-align:center;'>
						<i class="col-md-2 col-sm-2"></i>
						<i class="col-md-2 col-sm-2 fa fa-envelope"></i>
						<i class="col-md-2 col-sm-2 fa fa-linkedin"></i>
						<i class="col-md-2 col-sm-2 fa fa-twitter"></i>
						<i class="col-md-2 col-sm-2 fa fa-facebook"></i>
						<i class="col-md-2 col-sm-2"></i>
					</div><br/>
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
		echo JComments::showComments(1, 'com_joomdle', 10);
	?>
    </div>
    <?php
		}
	?>

</div>