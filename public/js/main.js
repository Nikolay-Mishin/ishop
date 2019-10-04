/*Cart*/
$('body').on('click', '.add-to-cart-link', function(e){
     e.preventDefault();
     var id = $(this).data('id'),
         qty = $('.quantity input').val() ? $('.quantity input').val() : 1,
         mod = $('.available select').val();
     $.ajax({
         url: '/cart/add',
         data: {id: id, qty: qty, mod: mod},
         type: 'GET',
         success: function(res){
             showCart(res);
         },
         error: function(){
             alert('Ошибка! Попробуйте позже');
         }
     });
});

function showCart(cart){
    console.log(cart);
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