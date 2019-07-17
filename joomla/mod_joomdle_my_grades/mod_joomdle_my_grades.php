<?php
/**
* @version		1.0
* @package		Joomdle - Mod Show User Grades
* @copyright	Qontori Pte Ltd
* @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_SITE.'/components/com_joomdle/helpers/content.php');

// Include the whosonline functions only once
require_once (dirname(__FILE__).'/helper.php');

$comp_params = JComponentHelper::getParams( 'com_joomdle' );

$moodle_xmlrpc_server_url = $comp_params->get( 'MOODLE_URL' ).'/mnet/xmlrpc/server.php';
$moodle_auth_land_url = $comp_params->get( 'MOODLE_URL' ).'/auth/joomdle/land.php';
$linkstarget = $comp_params->get( 'linkstarget' );
$show_averages = $params->get( 'show_averages' );
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));


$itemid = JoomdleHelperContent::getMenuItem();


$user = JFactory::getUser();
$id = $user->get('id');
$username = $user->get('username');

$limit = $params->get('last_grade_limit', 10);

if ($username)
	$tareas = JoomdleHelperContent::call_method ("get_last_user_grades", $username, (int) $limit);
else $tareas = array ();


require(JModuleHelper::getLayoutPath('mod_joomdle_my_grades'));
