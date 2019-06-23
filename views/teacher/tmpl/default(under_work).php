<?php 

    /**
      * @package      Joomdle
      * @copyright    Qontori Pte Ltd
      * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
      */

    defined('_JEXEC') or die('Restricted access');

    $itemid = JoomdleHelperContent::getMenuItem();
    $unicodeslugs = JFactory::getConfig()->get('unicodeslugs');

?>
    <div class="joomdle-teacher<?php echo $this->pageclass_sfx;?>">
        <h1>
            <?php echo $this->user_info['name']; ?>
        </h1>

        <div class="joomdle_user">
            <div class="joomdle_user_pic">
<?php
                // Use thumbs if available
                if ((array_key_exists ('thumb_url', $this->user_info)) && ($this->user_info['thumb_url'] != ''))
                    $this->user_info['pic_url'] = $this->user_info['thumb_url'];

                if (!(array_key_exists ('pic_url', $this->user_info)) || ($this->user_info['pic_url'] == ''))
                    $this->user_info['pic_url'] = JURI::root ().'media/joomdle/grow/anonymous_user_avatar_100.jpg';

                if (array_key_exists ('profile_url', $this->user_info))
                    echo '<a href="' . JRoute::_($this->user_info['profile_url']."&Itemid=$itemid") . '">' .
                             '<img height="64" width="64" src="' . $this->user_info['pic_url'] . '">' .
                         '</a>';
                else
                    echo '<a>' . 
                             '<img height="64" width="64" src="' . $this->user_info['pic_url'] . '">' .
                         '</a>';
?>
            </div>

            <div class="joomdle_user_details">

                <?php if ((array_key_exists ('city', $this->user_info)) && ($this->user_info['city'])) : ?>
                    <div class="joomdle_user_city">
                        <?php echo '<b>'.JText::_('COM_JOOMDLE_CITY'). ': </b>'; ?>
                        <?php echo $this->user_info['city']; ?>
                    </div>
                <?php endif; ?>

                <?php if ((array_key_exists ('country', $this->user_info)) && ($this->user_info['country'])) : ?>
                    <div class="joomdle_user_country">
                        <?php echo '<b>'.JText::_('COM_JOOMDLE_COUNTRY'). ': </b>'; ?>
                        <?php echo $this->user_info['country']; ?>
                    </div>
                <?php endif; ?>

                <?php if ((array_key_exists ('description', $this->user_info)) && ($this->user_info['description'])) : ?>
                    <div class="joomdle_user_country">
                        <?php echo '<b>'.JText::_('COM_JOOMDLE_ABOUTME'). ': </b>'; ?>
                        <?php echo $this->user_info['description']; ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->params->get('additional_data_source') == 'jomsocial') : ?>
                    <div class="joomdle_user_actions">
<?php
                        echo '<br>';
                        echo '<img src="'.JURI::root().'/components/com_community/templates/default/images/action/icon-email-go.png" />';
                        // Send message button
                        $jspath = JPATH_ROOT.'/components/com_community';
                        include_once($jspath.'/libraries/core.php');
                        include_once($jspath.'/libraries/messaging.php');

                        $user_id = JUserHelper::getUserId($this->username);
                        $user = JFactory::getUser ($user_id);
                        $onclick = CMessaging::getPopup($user->id);
                        echo '<a href="javascript:void(0)" onclick="'. $onclick .'">'. JText::_('COM_JOOMDLE_WRITE_MESSAGE').'</a>';
?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
            <tr>
                <td width="90%" height="20" class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
                    <?php echo JText::_('COM_JOOMDLE_TEACHED_COURSES'); ?>
                </td>
            </tr>
        </table>

        <div class="col-md-9 col-md-pull-3">
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
                        echo "<a href=\"$course_link\">";
                            echo '<div class="list-view-course-card--container list-view-course-card--bottom-border">';
                                echo '<div class="list-view-course-card--image-container">';
                                    /* SUMMARY IMAGE FILE SECTION */
                                    echo '<img class="img" hspace="0" vspace="5" align="center" src="'.$summary_file['url'].'" data-src="'.$summary_file['url'].'" >';
                                echo '</div>';

                                echo '<div class="list-view-course-card--content">';

                                    echo '<div class="list-view-course-card--title"><h4>';
                                        /* COURSE TITLE SECTION */
                                        echo $course['fullname'];
                                    echo '</h4></div>';

                                    echo '<div class="list-view-course-card--headline-and-instructor-container">';
                                        echo '<div class="list-view-course-card--headline-and-instructor">';
                                            /* COURSE SUMMARY SECTION */
                                            echo '<span>' . $course['summary'] . '</span>';
                                        echo '</div>';
                                    echo '</div>';

                                    echo '<div class="list-view-course-card--price-rating">';
                                        echo '<div class="list-view-course-card--price-container">';

                                            echo '<div class="price-text-container price-text--base-price__container price-text--right price-text--vertical">';

                                                echo '<div class="course-price-text price-text--base-price__discount price-text--black price-text--medium price-text--bold">';
                                                    echo '<span><span>';
                                                        // €10.99
                                                        /* COURSE DISCOUNT PRICE SECTION */
                                                        if ($course['cost'])
                                                            echo $course['cost'];
                                                        else
                                                            echo 'رایگان';
                                                    echo '</span></span>';
                                                echo '</div>';

                                                echo '<div class="original-price-container price-text--base-price__original price-text--lighter price-text--small price-text--regular price-text--base-price__original--no-margin">';
                                                    echo '<div>';
                                                        echo '<span><s><span>';
                                                            // €199.99
                                                            /* COURSE ORIGINAL PRICE SECTION */
                                                            if ($course['cost'])
                                                                echo $course['cost'];
                                                            else
                                                                echo 'رایگان';
                                                        echo '</span></s></span>';
                                                    echo '</div>';
                                                echo '</div>';

                                            echo '</div>';

                                        echo '</div>';
                                    echo '</div>';

                                echo '</div>';
                            echo '</div>';
                        echo '</a>';
                    echo '</div>';
                }
?>
        </div>
    </div>
