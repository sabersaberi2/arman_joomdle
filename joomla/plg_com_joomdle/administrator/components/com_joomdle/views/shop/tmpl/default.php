<?php
/**
  * @package      Joomdle
  * @copyright    Qontori Pte Ltd
  * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
  */

defined('_JEXEC') or die('Restricted access');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$i = 0;

?>

<form action="index.php?option=com_joomdle&view=shop" method="POST" id="adminForm" name="adminForm" class="table table-stripped">
  <?php if(!empty( $this->sidebar)): ?>
        <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
    <?php else : ?>
        <div id="j-main-container">
    <?php endif;?>

        <?php
        // Search tools bar
        echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
        ?>

       <table class="table table-striped" width="100%">
             <thead>
                    <tr>
                           <th width="10">ID</th>
                          <th width="10"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" /></th>
                           <th width="600"><?php echo JText::_('COM_JOOMDLE_COURSE'); ?></th>
                           <th class="center"><?php echo JText::_('COM_JOOMDLE_SELL_ON_SHOP'); ?></th>
                    </tr>              
             </thead>
            <tfoot>
                        <tr>
                                <td colspan="10">
                                        <?php echo $this->pagination->getListFooter(); ?>
                                </td>
                        </tr>
            </tfoot>
             <tbody>
                    <?php
                    $k = 0;
                    foreach ($this->items as $row){
                        if ((property_exists ($row, 'is_bundle')) && ($row->is_bundle))
                           $checked = JHTML::_('grid.id', $i, "bundle_".$row->id);
                        else
                           $checked = JHTML::_('grid.id', $i, $row->id);
                           $published      = JHTML::_('jgrid.published', $row->published, $i , 'shop.');
               ?>
                           <tr class="<?php echo "row$k";?>">
                                  <td><?php echo $row->id;?></td>
                                  <td><?php echo $checked; ?></td>

                            <?php if ((property_exists ($row, 'is_bundle')) && ($row->is_bundle)) : ?>
                                  <td><a href="index.php?option=com_joomdle&view=bundle&task=bundle.edit&id=<?php echo $row->id; ?>"><?php echo $row->name;?></a></td>

                            <?php else : ?>
                                  <td><?php echo '(' . $row->shortname . ') ' . $row->fullname;?></td>
                            <?php endif; ?>
                                  <td class="center"><?php echo $published; ?> </td>
                           </tr>
                    <?php
                    $k = 1 - $k;
                    $i++;
                    }
                    ?>
             </tbody>
       </table>
      
       <input type="hidden" name="option" value="com_joomdle"/>
       <input type="hidden" name="task" value=""/>
       <input type="hidden" name="boxchecked" value="0"/>   
       <input type="hidden" name="hidemainmenu" value="0"/> 
       <?php echo JHTML::_( 'form.token' ); ?>
</form>
