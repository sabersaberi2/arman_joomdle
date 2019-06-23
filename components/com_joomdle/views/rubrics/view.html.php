<?php
/**
  * @package      Joomdle
  * @copyright    Qontori Pte Ltd
  * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
  */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Joomdle component
 */
class JoomdleViewRubrics extends JViewLegacy {
    function display($tpl = null) {
        global $mainframe;

        $app                = JFactory::getApplication();
        $pathway =$app->getPathWay();

        $app        = JFactory::getApplication();
        $menus      = $app->getMenu();
        $menu  = $menus->getActive();

        $params = $app->getParams();
        $this->assignRef('params',              $params);

        $id = $app->input->get('id');

         $id = (int) $id;

        if (!$id)
        {
            echo JText::_('COM_JOOMDLE_NO_COURSE_SELECTED');
            return;
        }

        $this->rubrics = JoomdleHelperContent::call_method('get_rubrics', $id);

        $this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

        parent::display($tpl);
    }
}
?>
