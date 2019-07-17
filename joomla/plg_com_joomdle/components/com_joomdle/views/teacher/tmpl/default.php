<?php 

    /**
      * @package      Joomdle
      * @copyright    Qontori Pte Ltd
      * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
      */

    defined('_JEXEC') or die('Restricted access');

    $itemid = JoomdleHelperContent::getMenuItem();
	$courseItemid = $comp_params = JComponentHelper::getParams( 'com_joomdle' )->get( 'courseview_itemid' );
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
    <div class="joomdle-teacher<?php echo $this->pageclass_sfx;?>">

        <div class="flex-col wi-lg-25 wi-md-33 wi-sm-100 wi-xs-100 infoWrap" >
            <div class="span3 author-info wi-100" >
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
                    <a class="nameLink" href="<?php echo JRoute::_($this->user_info['profile_url']."&Itemid=$itemid");// $itemid=956 for udemy template?>">
                        <?php echo $this->user_info['name']; ?>
                    </a>

                    <div class="activity portions wi-100 flex-row jus-between al-start">
                        <div class="titles">فعالیت مدرس</div>
                        <div class="flex-col jus-center al-center wi-30">
                            <i class="icon-faranesh-font-11"></i>
                            <div class="desc">تعداد دوره‌ها</div>
                            <div class="value">
                                <?php echo count($this->courses); ?>
                            </div>
                        </div>
                    </div>
                    <div class="aboutAuthor portions wi-100 flex-row jus-between al-start">
                        <div class="titles">درباره مدرس</div>
                        <p class="flex-col jus-center al-center wi-100" style="text-align: right;">
                            <?php echo $this->user_info['cb_teacherscv']; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
            <tr>
                <td width="90%" height="20" class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
                    <?php echo JText::_('COM_JOOMDLE_TEACHED_COURSES'); ?>
                </td>
            </tr>
        </div>

        <div class="span9 teachercoursesprofile col-md-9 col-md-pull-3">
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

                    $course_link = JRoute::_("index.php?option=com_joomdle&view=detail&cat_id=".$course['cat_id']."-$cat_slug&course_id=".$course['remoteid']."-$course_slug&Itemid=$courseItemid");

                    echo '<div class="list-view-course-card--course-card-wrapper">';
                        echo "<a href=\"$course_link\">";
                            echo '<div class="list-view-course-card--container list-view-course-card--bottom-border">';
                                echo '<div class="list-view-course-card--image-container">';
                                    /* SUMMARY IMAGE FILE SECTION */
                                    echo '<img class="img" hspace="0" vspace="5" align="center" src="'.$summary_file['url'].'" data-src="'.$summary_file['url'].'" >';
                                echo '</div>';

                                echo '<div class="list-view-course-card--content">';
echo '<div>';
                                    echo '<div class="list-view-course-card--title"><h4>';
                                        /* COURSE TITLE SECTION */
                                        echo $course['fullname'];
                                    echo '</h4></div>';

                                    echo '<div class="list-view-course-card--headline-and-instructor-container">';
                                        /* COURSE SUMMARY SECTION */
                                        echo '<span>' . $course['summary'] . '</span>';
                                    echo '</div>';
echo '</div>';
                                    echo '<div class="list-view-course-card--price-container">';

                                        echo '<span>';
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
                        echo '</a>';
                    echo '</div>';
                }
?>

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
