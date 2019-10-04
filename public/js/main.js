// корзина
/*Cart*/
// Урок - делегирование событий в JS (для элементов которых изначально не было на странице - добавлены динамически)
// берем элемент 'body' (он есть всегда) и от него делегируем событие 'click' для элементов с классом 'add-to-cart-link'
$('body').on('click', '.add-to-cart-link', function(e){
    e.preventDefault(); // отменяем действие по умолчанию (запретить переход по ссылке и тд) - также можно return false;
    var id = $(this).data('id'), // id с номером товара
        qty = $('.quantity input').val() ? $('.quantity input').val() : 1, // количество товара (если нет = 1)
        mod = $('.available select').val(); // id модификатора товара
    // отправляет стандартный ajax-запрос
    $.ajax({
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
    });
});

// отображаем корзину
function showCart(cart){
    if($.trim(cart) == '<h3>Корзина пуста</h3>'){
        $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'none');
    }else{
        $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'inline-block');
    }
    $('#cart .modal-body').html(cart);
    $('#cart').modal();
}
/*Cart*/

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