<?php if (empty($files)): ?>
	<p>Aucun fichier en conflit, youpi.</p>
<?php else: ?>

<?php 
	$checkboxes = !empty($checkboxes) ? $checkboxes : false;
?>
<?php if ($checkboxes): ?>
<form class="dropbox-form" method="post" action="/">
	<input type="submit" value="Supprimer les éléments sélectionnés">
	<div><input checked type=checkbox name="toggleAll" value="" id=""></div>
<?php endif ?>
<table class="dropbox-list">
<?php foreach ($files as $key => $value):
	$url = 'https://www.dropbox.com/home';
	$url .= $value['is_dir'] ? $value['path'] : dirname($value['path']);
	$labelOffset = Helpers::strrpos_offset('/', $value['path'], 3);
	$label = ($labelOffset === false ? '' : '...') . substr($value['path'], $labelOffset); ?>
	<tr>
		<?php if ($checkboxes): ?>
		<td class="dropbox-file-delete">
			<input checked type=checkbox name="toDelete[]" value="<?php echo $value['path'] ?>" id="file_<?php echo $value['rev'] ?>">
		</td>
		<?php endif ?>
		<td class="dropbox-file-name">
			<label for="file_<?php echo $value['rev'] ?>">
				<a href="<?php echo $url ?>" target="_blank"><?php echo $label ?></a>
			</label>
		</td>
		<td class="dropbox-file-size"><?php echo $value['size'] ?></td>
		<td class="dropbox-file-modified"><?php echo $value['modified'] ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php if ($checkboxes): ?>
	<input type="submit" value="Supprimer les éléments sélectionnés">
</form>
<?php endif ?>
<?php endif ?>