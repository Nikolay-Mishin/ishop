/* Comments */
var comment = $('#comment');
if(comment){
	var btn = comment.find('button'),
		value = editorOnChange(comment, function (value){
			if (value) {
				btn.prop('disabled', false);
			}
			else {
				btn.prop('disabled', true);
			}
			console.log(value);
		});

	btn.prop('disabled', true);

	comment.on('submit', function(){
		if (value){
			console.log(value);
		}
		return false;
	});
}

/* Filters */
// делигируем событие изменения от body инпутам в сайдбаре (списке фильтров)
$('body').on('change', '.w_sidebar input', function(){
	var checked = $('.w_sidebar input:checked'), // список выбранных фильтров
		data = '';
	// проходим в фикле по выбранным фильтрам и формируем строку со списком id фильтров, разделенных ','
	checked.each(function () {
		data += this.value + ',';
	});
	// если список фильтров не пуст обрабатываем его, иначе перезапрашиваем текущую страницу
	if(data){
		ajax(location.href, showFilter, {filter: data}, 'Ошибка!', showPreloader, data); // ajax-запрос
		/* $.ajax({
			url: location.href, // url для отправки на сервер (абсолютный адрес текущей страницы - http://ishop/category/men)
			data: {filter: data}, // данные для отправки на сервер
			type: 'GET',
			// функция, вызываемая перед отправкой запроса
			beforeSend: showPreloader(),
			success: function(res){
				showFilter(res, data);
			},
			error: function () {
				alert('Ошибка!');
			}
		}); */
	}else{
		window.location = location.pathname; // /category/men
	}
});

// плавно отображаем прелоадер (fadeIn = 300) и скрываем товары (call-back функция)
function showPreloader(){
	$('.preloader').fadeIn(300, function(){
		$('.product-one').hide();
	});
}

// плавно скрываем прелоадер (fadeOut = 'slow') с задержкой (delay = 500) и отображаем товары (call-back функция)
function showFilter(res, data){
	$('.preloader').delay(500).fadeOut('slow', function(){
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
/* // Filters */

/* Search */
// переменная для хранения объекта движка Bloodhound плагина typeahead
// используем для получения данных поискового запроса
var products = new Bloodhound({
	datumTokenizer: Bloodhound.tokenizers.whitespace,
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	remote: {
		wildcard: '%QUERY', // маркер, который будет заменен поисковым запросом (подставляется в url)
		// на экшен 'typeahead' отправляется GET-параметр 'query'
		url: path + '/search/typeahead?query=%QUERY' // адрес для отправки запроса (вместо %QUERY подставляется маркер wildcard)
	}
});

products.initialize(); // инициализируем объект для поискового запроса

$("#typeahead").typeahead({
	// hint: false,
	highlight: true // подсветка вводимого текста
},{
	name: 'products',
	display: 'title', // то, что хотим показывать
	limit: 10, // на 1 меньше, чем будет приходить (LIMIT 11)
	source: products // источник данных
});

// при выборе результата из выпадающего списка срабатывает событие (typeahead:select)
$('#typeahead').bind('typeahead:select', function(ev, suggestion) {
	// console.log(suggestion);
	// на экшен 'index' отправляется GET-параметр 's' со значением наименования продукта
	window.location = path + '/search/?s=' + encodeURIComponent(suggestion.title); // перенаправление на страницу поиского запроса
});
/* // Search */

// корзина
/* Cart */
// событие при клике по сслыке для добавления в корзину
// Урок - делегирование событий в JS (для элементов которых изначально не было на странице - добавлены динамически)
// берем элемент 'body' (он есть всегда) и от него делегируем событие 'click' для элементов с классом 'add-to-cart-link'
$('body').on('click', '.add-to-cart-link', function(e){
	e.preventDefault(); // отменяем действие по умолчанию (запретить переход по ссылке и тд) - также можно return false;
	var id = $(this).data('id'), // id с номером товара
		qty = $('.quantity input').val() ? $('.quantity input').val() : 1, // количество товара (если нет = 1)
		mod = $('.available select').val(); // id модификатора товара
	
	ajax('/cart/add', changeCart, {id: id, qty: qty, mod: mod}); // ajax-запрос
	/* $.ajax({
		url: '/cart/add', // адрес для отправки запроса на серевер ('/' вначале - путь будет идти от корня или path + '/cart/add')
		data: {id: id, qty: qty, mod: mod}, // объект с данными для отправки на серевер
		type: 'GET', // метод отправки запроса
		success: function(res){
			// res - ответ от сервера
			showCart(res); // отображаем корзину
		},
		error: function(){
			alert('Ошибка! Попробуйте позже');
		}
	}); */
});

// событие при клике на ссылку для удаления товара из корзины
// делегируем событие клика от тела модального окна корзины элементу с классом 'del-item'
$('#cart .modal-body').on('click', '.del-item', function(){
	var id = $(this).data('id'); // id товара, который хотим удалить из корзины
	ajax('/cart/delete', changeCart, {id: id}, 'Error!'); // ajax-запрос
	/* $.ajax({
		url: '/cart/delete',
		data: {id: id},
		type: 'GET',
		success: function(res){
			showCart(res);
		},
		error: function(){
			alert('Error!');
		}
	}); */
});

// отображает корзину
function showCart(cart){
	changeCart(cart); // изменяем содержимое корзины
	$('#cart').modal(); // показываем модальное окно
}

// изменяет содержимое корзины
function changeCart(cart){
	$('#cart .modal-body').html(cart); // в тело модального окна записываем полученный из запроса ответ (контент)
	// если есть элемент с классом 'cart-sum' (корзина не пуста), меняем значение общей сумму у иконки с корзиной
	if($('.cart-sum').text()){
		$('.simpleCart_total').html($('#cart .cart-sum').text()); // в элемент с классом 'simpleCart_total' добавляем сумму заказа
	}else{
		$('.simpleCart_total').text('Empty Cart');
	}
	// если корзина пуста - скрываем в футере кнопки для взаимодействия с содержимым (оформить заказ и очистить корзину)
	// trim - обрезаем пробелы по бокам
	if($.trim(cart) == '<h3>Корзина пуста</h3>'){
		// скрываем ссылку для оформления заказа и кнопку для очистки корзины
		$('#cart-order, #cart-clean').css('display', 'none');
	}else{
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

// при изменении инпута корзины
$('body').on('change', '#cart input', function(){
	// если кнопка пересчета корзины заблокирована, разблокируем ее
	if($('#cart-recalc').attr('disabled')){
		$('#cart-recalc').attr('disabled', false);
		cartRecalc.productsChange = [];
	}
	if (!cartRecalc.productsChange.includes(this)){
		cartRecalc.productsChange.push(this);
	}
});

// пересчитывает корзину при изменении количества товаров
function cartRecalc(){
	var productsChange = {}; // количество товара (если нет = 1)
	cartRecalc.productsChange.forEach(function(item){
		productsChange[$(item).data('id')] = $(item).val() - $(item).data('qty');
	});
	ajax('/cart/recalc', changeCart, {productsChange: productsChange}); // ajax-запрос
}

/*
function asdf() {
	this.dsa = function() {
		alert(123);
	}
}
let Asdf = new asdf();
Asdf.dsa();
*/
/* // Cart */

// отслеживаем изменение выпадающего списка валют
$('#currency').change(function(){
	window.location = 'currency/change?curr=' + $(this).val(); // запрашиваем страницу и передаем управление контроллеру валюты
});

// отслеживаем изменение выпадающего списка модификаций
$('.available select').on('change', function(){
	var modId = $(this).val(), // идентификатор (id) выбранного модификатора
		color = $(this).find('option').filter(':selected').data('title'), // цвет выбранного модификатора
		price = $(this).find('option').filter(':selected').data('price'), // цена выбранного модификатора
		basePrice = $('#base-price').data('base'); // базовая цена товара
	// если цена установалена (выбран модификатор), устанавливаем цену модификатора
	if(price){
		$('#base-price').text(symboleLeft + price + symboleRight);
	}else{
		$('#base-price').text(symboleLeft + basePrice + symboleRight); // если пользователь вернулся к базовой версии товара
	}
});

// Ajax-запрос - отправляет стандартный ajax-запрос
function ajax(url, successFunc, data = {}, errorMsg = 'Ошибка! Попробуйте позже', beforeSend = null, args = [], type = 'GET') {
	$.ajax({
		url: url, // адрес для отправки запроса на серевер ('/' вначале - путь будет идти от корня или path + '/cart/add')
		data: data, // объект с данными для отправки на серевер
		type: type, // метод отправки запроса
		// функция, вызываемая перед отправкой запроса
		beforeSend: beforeSend != null ? beforeSend.bind() : function(){},
		// success: beforeSend == null ? successFunc.bind(this) : function(res){successFunc.call(this, res, data);},
		success: function(res){
			successFunc.call(this, res, args, data);
		},
		/* success: function(res) {
			// res - ответ от сервера
			success(res); // отображаем корзину (showCart())
		}, */
		// success: stage1_3.bind(this), // или success: stage1_3.bind(this, data, text) если нужно какие то аргументы передавать
		// Ответ от сервера будет последний в списке аргументов, передаваемых в функцию (text - response).
		// То есть: data = arguments[arguments.length-1];
		error: function() {
			alert(errorMsg);
		}
	});
}
