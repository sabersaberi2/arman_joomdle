<?php // no direct access
defined('_JEXEC') or die('Restricted access');

?>
        <ul class="joomdle_certificates<?php echo $moduleclass_sfx; ?>">
<?php
		$simple = '';
		if ($cert_type == 'simple')
			$simple = 'simple';
        if (is_array($certs)) {
			foreach ($certs as $id => $cert) {
				$id = $cert['id'];

			 $redirect_url = $moodle_url."/mod/${simple}certificate/view.php?id=$id&certificate=1&action=review";
			
			echo "<li>";
		?>
			<span>
			<a target='_blank' href="<?php echo $redirect_url; ?>"><?php echo $cert['name']; ?></a>
			<?php if ($show_send_certificate) : ?>
			<a href="index.php?option=com_joomdle&view=sendcert&tmpl=component&simple=<?php echo $simple; ?>&cert_id=<?php echo $id; ?>" onclick="window.open(this.href,'win2','width=400,height=350,menubar=yes,resizable=yes'); return false;" title="Email"><img alt="Email" src="media/system/images/emailButton.png"></a>
			</span>
			<?php endif; ?>
		<?php
			echo "</li>";
			}
		}
?>
        </ul>
