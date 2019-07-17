<?php
/**
  * @package      Joomdle
  * @copyright    Qontori Pte Ltd
  * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
  */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.user.helper');
require_once(JPATH_ADMINISTRATOR.'/components/com_joomdle/helpers/content.php');

/**
 * Content Component Query Helper
 *
 * @static
 * @package     Joomdle
 */
class JoomdleHelperEvents
{
    static function trigger_event ($event, $data)
    {
        JPluginHelper::importPlugin( 'joomdleevent' );
        $dispatcher = JDispatcher::getInstance();
        $result = $dispatcher->trigger($event, array ($data));
    }
}
