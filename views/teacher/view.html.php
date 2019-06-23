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
class JoomdleViewTeacher extends JViewLegacy {
    function display($tpl = null) {
        global $mainframe;

        $app        = JFactory::getApplication();
        $params = $app->getParams();
        $this->assignRef('params',              $params);

        $username = $app->input->get('username');
        if (!$username)
            $username = $params->get( 'username');

        if (!$username)
        {
            echo JText::_('COM_JOOMDLE_NO_USER_SELECTED');
            return;
        }

        $this->courses = JoomdleHelperContent::call_method('teacher_courses', $username, 'full');
        // var_export($this->courses);
        // die();
        $this->username = $username;
        // var_export($this->username);
        // die();
        $this->user_info = JoomdleHelperMappings::get_user_info_for_joomla ($this->username);
        // var_export($this->user_info);
        // die();

        $document = JFactory::getDocument();
        $document->setTitle($this->user_info['name']);

        $this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		// $layout = $this->params->get('teacher_layout');
        // var_export($layout);
        // die();
		// $this->setLayout($layout);

        parent::display($tpl);
    }
}
?>
