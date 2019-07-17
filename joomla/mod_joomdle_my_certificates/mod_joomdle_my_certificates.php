<?php
/**
* @version		1.0.1
* @package		Joomdle - Mod Display My Certificates
* @copyright	Qontori Pte Ltd
* @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_SITE.'/components/com_joomdle/helpers/content.php');

require_once (dirname(__FILE__).'/helper.php');

$comp_params = JComponentHelper::getParams( 'com_joomdle' );

$moodle_xmlrpc_server_url = $comp_params->get( 'MOODLE_URL' ).'/mnet/xmlrpc/server.php';
$moodle_url = $comp_params->get( 'MOODLE_URL' );
$linkstarget = $comp_params->get( 'linkstarget' );
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$show_send_certificate = $params->get('show_send_certificate');
$cert_type = $params->get('certificate_type');

$user = JFactory::getUser();
$username = $user->get('username');

$session = JFactory::getSession();
$token = md5 ($session->getId());

$certs = JoomdleHelperContent::call_method ("my_certificates", $username, $cert_type);

$jdoc = JFactory::getDocument();
$jdoc->addStyleSheet(JURI::root ().'components/com_joomdle/css/joomdle.css');


require(JModuleHelper::getLayoutPath('mod_joomdle_my_certificates'));
