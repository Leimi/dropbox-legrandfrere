(function() {
	$('.dropbox-form').on('submit', function(e) {
		if (!confim('Êtes-vous sûr de vouloir supprimer de votre Dropbox les éléments sélectionés ?'))
			e.preventDefault();
	});

	$('.dropbox-form input[name=toggleAll]').on('click', function(e) {
		$('.dropbox-file-delete input[type=checkbox]').prop('checked', $(this).prop('checked'));
		$('.dropbox-file-delete input[type=checkbox]').closest('tr').toggleClass('checked', $(this).prop('checked'));
	});

	$('.dropbox-form tr').each(function(n, item) {
		if ($(this).find('.dropbox-file-delete input[type=checkbox]').prop("checked"))
			$(this).addClass('checked');
	});
	$('.dropbox-form tr > td').on('click', function(e) {
		var checkbox = null;

		if ($(this).hasClass('dropbox-file-delete'))
			checkbox = $(this).find('input[type=checkbox]');
		else
			checkbox = $(this).closest('tr').find('.dropbox-file-delete input[type=checkbox]');

		checkbox.prop("checked", !checkbox.prop("checked"));
		$(this).closest('tr').toggleClass('checked', checkbox.prop("checked"));
	});
})();