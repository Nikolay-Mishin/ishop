function uploadImg(target, upload = 'single'){
	new AjaxUpload(target, {
		action: adminpath + target.data('url') + "?upload=1", // адрес запроса
		data: { name: target.data('name') }, // передаваемые данные
		name: target.data('name'), // имя файла
		// действие при нажатии кнопки
		onSubmit: function(file, ext){
			if(!(ext && /^(jpg|png|jpeg|gif)$/i.test(ext))){
				alert('Ошибка! Разрешены только картинки');
				return false;
			}
			target.closest('.file-upload').find('.overlay').css({ 'display': 'block' }); // отображаем прелоадер

		},
		// действие после окончания загрузки
		onComplete: function(file, response){
			// устанавливаем задержку для выполнения кода в 1000 мс (1с)
			setTimeout(function(){
				target.closest('.file-upload').find('.overlay').css({ 'display': 'none' }); // скрываем прелоадер

				response = JSON.parse(response); // ответ от запроса (парсим json)
				var img = `<img src="/images/${response.file}" style="max-height: 150px; cursor: pointer;" data-src="${response.file}" data-upload="${upload}" class="del-item">`;
				console.log(img);
				// отображаем загруженную картинку
				if(upload == 'gallery'){
					$('.' + target.data('name')).append(img);
				}else{
					$('.' + target.data('name')).html(img);
				}
			}, 1000);
		}
	});
}

// проверяет является данное значения числом
function isNumeric(n){
	return !isNaN(parseFloat(n)) && isFinite(n);
}

function isChecked(target){
	return target.prop('checked'); // значение checkbox
}

function changeInput(target, change = true, value = 1){
	target.prop('disabled', change);
	if(change){
		target.data('value', target.val());
		target.val(value);
	}else{
		target.val(target.data('value'));
	}
}

function getItems(target, item){
	return target.find(item);
}

function getModItems(){
	return getItems(mod_list, '.mod-item');
}

function resetInputs(target){
	for (var input of target.find('input')){
		$(input).val('');
	}
}

function resetLabels(target, find){
	target = target.find(find);
	var i = 1;
	for(var label of target){
		$(label).text('Модификация ' + i++);
	}
}
