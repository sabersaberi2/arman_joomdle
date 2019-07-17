<?php
/**
* @version		1.0
* @package		Joomdle - Mod Show User Grades
* @copyright	Qontori Pte Ltd
* @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
*/
?>
<div class="joomdlegradesmod<?php echo $moduleclass_sfx; ?>">
<table width="100%">
<?php
foreach ($tareas as  $tarea) :
if ($tarea['itemname']) : ?>
<tr>
<td>
<?php
	echo $tarea['itemname'];
?>
</td>
<td>
<?php
	echo $tarea['finalgrade'];
?>
</td>
<?php if ($show_averages) : ?>
<td>
(<?php
	echo $tarea['average'];
?>)
</td>
<?php endif; ?>
</tr>
<?php endif; ?>
<?php endforeach; ?>
</table>
</div>
