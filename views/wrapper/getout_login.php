<?php
/**
  * @package      Joomdle
  * @copyright    Qontori Pte Ltd
  * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
  */

// If anyone reads this code and comes with a better way to do it, please tell me ;)

$uri = $_SERVER['REQUEST_URI'];
$host = $_SERVER['HTTP_HOST'];
$path = strstr ($uri, '/components/com_joomdle/views/wrapper/getout_login.php');
$len = strlen ($uri);
$len2 = strlen ($path);
$root = substr ($uri, 0, $len - $len2);


$proto = 'http';
if ($_SERVER["HTTPS"] == "on")
    $proto .= "s";
$proto .= '://';

$root = $proto.$host.$root.'/index.php?option=com_users&view=login';

?>
<script type="text/javascript">
top.location.href = "<?php echo $root; ?>";
</script>
