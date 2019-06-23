<?php 
/**
  * @package      Joomdle
  * @copyright    Qontori Pte Ltd
  * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
  */

defined('_JEXEC') or die('Restricted access'); ?>

<?php
$itemid = JoomdleHelperContent::getMenuItem();
?>

<div class="joomdle-userlist<?php echo $this->pageclass_sfx;?>">
    <?php if ($this->params->get('show_page_heading', 1)) : ?>
    <h1>
    <?php echo $this->escape($this->params->get('page_heading')); ?>
    </h1>
    <?php endif; ?>

<?php
if (is_array ($this->teachers))
foreach ($this->teachers as  $teacher) :
    $user_info = JoomdleHelperMappings::get_user_info_for_joomla ($teacher['username']);

    if (!count ($user_info)) //not a Joomla user
        continue;
    ?>
        <div class="joomdle_user_list_item">
            <div class="joomdle_user_list_item_pic">
            <?php
            // Use thumbs if available: no, they are too small for this view
//          if ((array_key_exists ('thumb_url', $user_info)) && ($user_info['thumb_url'] != ''))
//              $user_info['pic_url'] = $user_info['thumb_url'];
            ?>
                <a href="<?php echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid"); ?>"><img height='64' width='64' src="<?php echo $user_info['pic_url']; ?>"></a>
            </div>
            <div class="joomdle_user_list_item_name">
               <a href="<?php echo JRoute::_("index.php?option=com_joomdle&view=teacher&username=".$teacher['username']."&Itemid=$itemid"); ?>"><?php echo $teacher['firstname']." ".$teacher['lastname']; ?></a>
            </div>
        </div>
<?php endforeach; ?>

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