// при нажатии на ссылку с классом 'delete' происходит переход по ссылке либо отмена действия (в зависимости от выбранного действия)
$('.delete').click(function () {
	var res = confirm('Подтвердите действие'); // окно с подтверждением ок/отмена
	if (!res) return false; // отменяет действие, если получен параметр false (отмена)
});

// подсвечивает активный пункт меню
$('.sidebar-menu a').each(function () {
	// текущий запрос (адресная строка) - http://ishop/admin/order
	// protocol = http:
	// host = ishop/
	// pathname = admin / order
	var location = window.location.protocol + '//' + window.location.host + window.location.pathname;
	var link = this.href; // текущая ссылка в цикле
	// если текущая ссылка совпадает с текущей страницей, то делаем данный элемент активным
	if (link == location) {
		$(this).parent().addClass('active'); // непосредственный родитель ссылки (подпункт меню)
		$(this).closest('.treeview').addClass('active'); // родитель категории меню (пункт меню - родитель списка подпугктов меню)
	}
});

// CK Editor
// CKEDITOR.replace('editor1');
$( '#editor1' ).ckeditor();