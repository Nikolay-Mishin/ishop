// сброс фильтров
$('#reset-filter').click(function(){
	$('#filter input[type=radio]').prop('checked', false); // сбрасываем все выбранные радио-кнопки фильтров
	return false;
});

// при нажатии на ссылку с классом 'delete' происходит переход по ссылке либо отмена действия (в зависимости от выбранного действия)
$('.delete').click(function(){
	var res = confirm('Подтвердите действие'); // окно с подтверждением ок/отмена
	if (!res) return false; // отменяет действие, если получен параметр false (отмена)
});

// подсвечивает активный пункт меню
$('.sidebar-menu a').each(function(){
	// текущий запрос (адресная строка) - http://ishop/admin/order
	// protocol = http:
	// host = ishop/
	// pathname = admin / order
	var location = window.location.protocol + '//' + window.location.host + window.location.pathname;
	var link = this.href; // текущая ссылка в цикле
	// если текущая ссылка совпадает с текущей страницей, то делаем данный элемент активным
	if(link == location){
		$(this).parent().addClass('active'); // непосредственный родитель ссылки (подпункт меню)
		$(this).closest('.treeview').addClass('active'); // родитель категории меню (пункт меню - родитель списка подпугктов меню)
	}
});

$(".select2").select2({
	placeholder: "Начните вводить наименование товара",
	// minimumInputLength: 2,
	cache: true,
	ajax: {
		url: adminpath + "/product/related-product",
		delay: 250,
		dataType: 'json',
		data: function(params){
			return {
				q: params.term,
				page: params.page
			};
		},
		processResults: function(data, params){
			return {
				results: data.items
			};
		}
	}
});

// загрузка картинок
//if(isset('#single')){
if (notEmpty('#single')){
	var buttonSingle = $("#single"),
		buttonMulti = $("#gallery"),
		file;
}

// загрузка основной картинки
//if(buttonSingle){
if(notEmpty(buttonSingle)){
	uploadImg(buttonSingle);
}

// загрузка картинок галлереи
//if(buttonMulti){
if(notEmpty(buttonMulti)){
	uploadImg(buttonMulti, 'gallery');
}

//function uploadImg(target, upload = 'single'){
//	new AjaxUpload(target, {
//		action: adminpath + target.data('url') + "?upload=1", // адрес запроса
//		data: { name: target.data('name') }, // передаваемые данные
//		name: target.data('name'), // имя файла
//		// действие при нажатии кнопки
//		onSubmit: function(file, ext){
//			if(!(ext && /^(jpg|png|jpeg|gif)$/i.test(ext))){
//				alert('Ошибка! Разрешены только картинки');
//				return false;
//			}
//			target.closest('.file-upload').find('.overlay').css({ 'display': 'block' }); // отображаем прелоадер

//		},
//		// действие после окончания загрузки
//		onComplete: function(file, response){
//			// устанавливаем задержку для выполнения кода в 1000 мс (1с)
//			setTimeout(function(){
//				target.closest('.file-upload').find('.overlay').css({ 'display': 'none' }); // скрываем прелоадер
//				response = JSON.parse(response); // ответ от запроса (парсим json)
//				var img = `<img src="/images/${response.file}" style="max-height: 150px; cursor: pointer;" data-src="${response.file}" data-upload="${upload}" class="del-item">`;
//				console.log(img);
//				// отображаем загруженную картинку
//				if(upload == 'gallery'){
//					$('.' + target.data('name')).append(img);
//				}else{
//					$('.' + target.data('name')).html(img);
//				}
//			}, 1000);
//		}
//	});
//}

// удаление картинок
$(document).on('click', '.del-item', function(){
	var res = confirm('Подтвердите действие'); // окно с подтверждением ок/отмена
	if(!res) return false; // отменяет действие, если получен параметр false (отмена)

	var $this = $(this), // текущий объект, по которому произведен клик
		id = $this.data('id'), // data-id
		src = $this.data('src'), // data-id
		upload = $this.data('upload'); // data-upload
	console.log([$this, id, src, upload]);

	// ajax-запрос
	$.ajax({
		url: adminpath + '/product/delete-image', // url запроса
		data: { id: id, src: src, upload: upload }, // данные для отправки
		type: 'POST', // метод передачи данных
		// перед отправкой запроса
		beforeSend: function(){
			$this.closest('.file-upload').find('.overlay').css({ 'display': 'block' }); // отображаем прелоадер
		},
		// после успешной откправки запроса
		success: function(res){
			// устанавливаем задержку для выполнения кода в 1000 мс (1с)
			setTimeout(function(){
				$this.closest('.file-upload').find('.overlay').css({ 'display': 'none' }); // скрываем прелоадер
				if(res == 1){
					$this.fadeOut(); // плавно скрываем удаленную картинку
				}
				console.log(res);
			}, 1000);
		},
		// ошибка отправки запроса
		error: function(res){
			setTimeout(function(){
				$this.closest('.file-upload').find('.overlay').css({ 'display': 'none' }); // скрываем прелоадер
				alert('Ошибка'); // отображаем окно с ошибкой
				console.log(res);
			}, 1000);
		}
	});
});

// блокируем отправку формы, если не выбрана категория
$('#add').on('submit', function(){
	if(!isNumeric($('#category_id').val())){
		alert('Выберите категорию');
		return false;
	}
});

// проверяет является данное значения числом
//function isNumeric(n){
//	return !isNaN(parseFloat(n)) && isFinite(n);
//}

// заполниет поля формы данными при выборе валюты из выпадающего списка
//if($('form').is('#course-form')){
if(notEmpty('#course-form')){
	var form = $('#course-form'), // форма добавления валюты
		title = form.find('#title'), // input названия валюты
		code = form.find('#code'), // input кода валюты
		course = form.find('#course'), // input курса валюты
		base = form.find('[type=checkbox]'); // checkbox базовой валюты

	$('#courses').on('change', function(){
		var selected = $(this).find('option:selected'), // текущий объект, по которому произведен клик
			codeCurr = selected.data('code'), // data-code
			courseVal = selected.data('course'), // data-course
			name = selected.data('title'); // data-title
		title.val(name);
		code.val(codeCurr);
		course.val(!isChecked(base) ? courseVal : 1);
		course.data('value', courseVal);
	});

	base.on('change', function(){
		changeInput(course, isChecked(base));
	});
}

//function isChecked(target){
//	return target.prop('checked'); // значение checkbox
//}

//function changeInput(target, change = true, value = 1){
//	target.prop('disabled', change);
//	if(change){
//		target.data('value', target.val());
//		target.val(value);
//	}else{
//		target.val(target.data('value'));
//	}
//}

//function getItems(target, item) {
//	return target.find(item);
//}

var mod_list = $('#mod-list');
//if($('div').is(mod_list)){
if(notEmpty(mod_list)){
	var mod_items = getItems(mod_list, '.mod-item');
}

if(mod_items){
	$(document).on('click touchstart', '.mod-add', function(e){
		e.preventDefault();
		var mod_item = $(this).closest('.mod-item').clone();
		resetInputs(mod_item);
		mod_item.find('[for=modification]').text('Модификация ' + (getModItems().length + 1));
		mod_list.append(mod_item);
	});

	$(document).on('click touchstart', '.mod-del', function(e){
		e.preventDefault();
		if(getModItems().length > 1){
			$(this).closest('.mod-item').remove();
			resetLabels(getModItems(), '[for=modification]');
		}
	});
}

//function getModItems(){
//	return getItems(mod_list, '.mod-item');
//}

//function resetInputs(target){
//	for(var input of target.find('input')){
//		$(input).val('');
//	}
//}

//function resetLabels(target, find){
//	target = target.find(find);
//	var i = 1;
//	for(var label of target){
//		$(label).text('Модификация ' + i++);
//	}
//}
