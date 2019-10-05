/* Search */
var products = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: path + '/search/typeahead?query=%QUERY'
    }
});

products.initialize();

$("#typeahead").typeahead({
    // hint: false,
    highlight: true
},{
    name: 'products',
    display: 'title',
    limit: 10,
    source: products
});

$('#typeahead').bind('typeahead:select', function(ev, suggestion) {
    // console.log(suggestion);
    window.location = path + '/search/?s=' + encodeURIComponent(suggestion.title);
});
/* // Search */

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
/* // Cart */

// Ajax-запрос - отправляет стандартный ajax-запрос
function ajax(url, success, data = {}, errorMsg = 'Ошибка! Попробуйте позже', type = 'GET') {
    $.ajax({
        url: url, // адрес для отправки запроса на серевер ('/' вначале - путь будет идти от корня или path + '/cart/add')
        data: data, // объект с данными для отправки на серевер
        type: type, // метод отправки запроса
        success: success.bind(this),
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

// передача пользовательской функции (some_func) в качестве аргумента другой функции
/* function ajaxFormRequest(form_id, url, dataT, some_func) {
    $.ajax({
        url: url,
        type: "POST", // Тип запроса
        data: jQuery("#"+form_id).serialize(), 
        dataType: dataT, 
        success: function(response) {
            getInfo('alert-'+response.type, response.msg);
            some_func();
        },
        error: function(response) {
            getInfo('alert-danger', 'Ошибка при отправке формы');
        }
    });
} */

// bind - создаёт "обёртку" над функцией, которая подменяет контекст этой функции. Поведение похоже на call и apply, но, в отличие от них, bind не вызывает функцию, а лишь возвращает "обёртку", которую можно вызвать позже.
/* function f() {
    alert(this);
}

var wrapped = f.bind('abc');

f(); // [object Window]
wrapped(); // abc */

// call - вызов функции с подменой контекста - this внутри функции.
/* function f(arg) {
    console.log(this);
    console.log(arg);
}

f('abc'); // abc, [object Window]

f.call('123', 'abc'); // 123 (this), abc */

// apply - вызов функции с переменным количеством аргументов и с подменой контекста.
/* Пример:
function f() {
    console.log(this);
    console.log(arguments);
}

f(1, 2, 3); // [object Window], [1, 2, 3]

f.apply('abc', [1, 2, 3, 4]); // abc (this), [1, 2, 3, 4] */

// создание пользовательской функции с передачей аргументов в виде объекта (по типу JQuery Ajax)
/**
 * This is how to document the shape of the parameter object
 * @param {boolean} [args.arg1 = false] Blah blah blah
 * @param {boolean} [args.notify = false] Blah blah blah
 */
/* function doSomething(args) {
    var defaults = {
        arg1: false,
        notify: false
    };
    args = Object.assign(defaults, args);
    console.log(args);

    var arg1 = args.arg1 !== undefined ? args.arg1 : false,
        notify = args.notify !== undefined ? args.notify : false;
    console.log('arg1 = ' + arg1 + ', notify = ' + notify);

    if (args.hasOwnProperty('arg1')) {
        // arg1 isset
    }

    if (args.hasOwnProperty('notify')) {
        // notify isset
    }
}

doSomething({notify: true}); // {arg1: false, notify: true} */