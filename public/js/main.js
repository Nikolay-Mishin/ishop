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