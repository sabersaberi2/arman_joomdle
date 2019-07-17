<?php
/**
* @version		1.0.1
* @package		Joomdle - Mod Display List of Moodle Teachers Grow
* @copyright	Qontori Pte Ltd
* @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).'/helper.php');
require_once(JPATH_SITE.'/components/com_joomdle/helpers/content.php');


$comp_params = JComponentHelper::getParams( 'com_joomdle' );

$joomdle_itemid = $comp_params->get( 'joomdle_itemid' );
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$teachers = JoomdleHelperContent::call_method ('teachers_abc', '');
$teachers_excluded = explode(',', $params->get('teachers_to_exclude'));

$layout           = $params->get('layout', 'default');

// $module->module = 'mod_joomdle_teachers_grow'
require(JModuleHelper::getLayoutPath($module->module, $layout));