// плавно отображаем прелоадер (fadeIn = 300) и скрываем товары (callback функция)
function showPreloader(target = '.product-one', delay = 300, preloader = '.preloader') {
	$(preloader).fadeIn(delay, function() {
		$(target).hide();
	});
}

function hidePreloader(callback = function(){}, delay = 500, fadeOut = 'slow', preloader = '.preloader') {
	$(preloader).delay(delay).fadeOut(fadeOut, callback);
}

// Ajax-запрос - отправляет стандартный ajax-запрос
function ajax(url, success = null, data = {}, args = {}, errorMsg = null, beforeSend = null, type = 'GET') {
	success = success ? success : function(){};
	errorMsg = errorMsg ? errorMsg : 'Ошибка! Попробуйте позже';
	beforeSend = beforeSend ? beforeSend : function(){};
	$.ajax({
		url: url, // адрес для отправки запроса на серевер ('/' вначале - путь будет идти от корня или path + '/cart/add')
		data: data, // объект с данными для отправки на серевер
		type: type, // метод отправки запроса
		//processData: false,
		//contentType: false,
		// функция, вызываемая перед отправкой запроса
		beforeSend: beforeSend,
		success: res => success.call(this, res, args, data),
		//success: function(res){
		//	success.call(this, res, args, data);
		//},
		/* success: function(res){
			// res - ответ от сервера
			success(res); // отображаем корзину (showCart())
		}, */
		// success: stage.bind(this), // или success: stage.bind(this, data, text) если нужно какие то аргументы передавать
		// Ответ от сервера будет последний в списке аргументов, передаваемых в функцию (text - response).
		// То есть: data = arguments[arguments.length-1];
		error: function() {
			console.log(errorMsg);
		},
		error: function(jqXHR, exception) {
			var msg = '';
			if (jqXHR.status === 0) {
				msg = 'Not connect.\n Verify Network.';
			}
			else if (jqXHR.status == 404) {
				msg = 'Requested page not found. [404]';
			}
			else if (jqXHR.status == 500) {
				msg = 'Internal Server Error [500].';
			}
			else if (exception === 'parsererror') {
				msg = 'Requested JSON parse failed.';
			}
			else if (exception === 'timeout') {
				msg = 'Time out error.';
			}
			else if (exception === 'abort') {
				msg = 'Ajax request aborted.';
			}
			else {
				msg = 'Uncaught Error.\n' + jqXHR.responseText;
			}
			console.log({ errorMsg: errorMsg + '\n' + msg, jqXHR: jqXHR, exception: exception });
		}
	});
}

// плавно скрываем прелоадер (fadeOut = 'slow') с задержкой (delay = 500) и отображаем товары (call-back функция)
function showFilter(res, data) {
	//$('.preloader').delay(500).fadeOut('slow', function(){
	hidePreloader(function() {
		$('.product-one').html(res).fadeIn();
		// удаляем из строки поиска выражение 'filter=1,' (начиная со слова filter, поле могут идти любые символы (=1,) до знака &)
		// ?filter=1,&page=2 => ?page=2
		var url = location.search.replace(/filter(.+?)(&|$)/g, ''); //$2
		// если в объекте location есть search (get-параметр), добавляем & и прибавляем параметр filter к существующим get-параметрам
		// иначе добавляем ? - формируем get-параметры
		// /category/men + ?page=2 + &filter=1, или /category/men + ?filter=1,
		var newURL = location.pathname + url + (location.search ? "&" : "?") + "filter=" + data;
		newURL = newURL.replace('&&', '&'); // заменяем дублирующие & на 1 знак &
		newURL = newURL.replace('?&', '?'); // символ '?&' заменяем на ?
		// pushState - отправляет новый url (обновляет состояние url, заменяя то, что в нем хранится на newURL)
		history.pushState({}, '', newURL); // объект истории браузера (позволяет запоминать состояние строки url)
	});
}

// отображает корзину
function showCart(cart) {
	changeCart(cart); // изменяем содержимое корзины
	$('#cart').modal(); // показываем модальное окно
}

// изменяет содержимое корзины
function changeCart(cart) {
	$('#cart .modal-body').html(cart); // в тело модального окна записываем полученный из запроса ответ (контент)
	// если есть элемент с классом 'cart-sum' (корзина не пуста), меняем значение общей сумму у иконки с корзиной
	if ($('.cart-sum').text()) {
		$('.simpleCart_total').html($('#cart .cart-sum').text()); // в элемент с классом 'simpleCart_total' добавляем сумму заказа
	}
	else {
		$('.simpleCart_total').text('Empty Cart');
	}
	// если корзина пуста - скрываем в футере кнопки для взаимодействия с содержимым (оформить заказ и очистить корзину)
	// trim - обрезаем пробелы по бокам
	if ($.trim(cart) == '<h3>Корзина пуста</h3>') {
		// скрываем ссылку для оформления заказа и кнопку для очистки корзины
		$('#cart-order, #cart-clean').css('display', 'none');
	}
	else {
		// отображаем ссылку для оформления заказа и кнопку для очистки корзины
		$('#cart-order, #cart-clean').css('display', 'inline-block');
	}
}

// отображает корзину при клике по ней
function getCart() {
	ajax('/cart/show', showCart); // ajax-запрос
	/* $.ajax({
		url: '/cart/show',
		type: 'GET',
		success: function(res){
			showCart(res);
		},
		error: function(){
			alert('Ошибка! Попробуйте позже');
		}
	}); */
}

// очищает корзину
function clearCart() {
	ajax('/cart/clear', changeCart); // ajax-запрос
	/* $.ajax({
		url: '/cart/clear',
		type: 'GET',
		success: function(res){
			showCart(res);
		},
		error: function(){
			alert('Ошибка! Попробуйте позже');
		}
	}); */
}

// пересчитывает корзину при изменении количества товаров
function cartRecalc() {
	var productsChange = {}; // количество товара (если нет = 1)
	cartRecalc.productsChange.forEach(function(item) {
		productsChange[$(item).data('id')] = $(item).val() - $(item).data('qty');
	});
	ajax('/cart/recalc', changeCart, {productsChange: productsChange}); // ajax-запрос
}
