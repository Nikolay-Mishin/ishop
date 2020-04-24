/* Sort */

var sort = $('#sort');

if(notEmpty(sort)){
	$('button[name="random"]').on('click', randomeSort); // Рандомно перемешаем блоки

	$('button[name="start"]').on('click', function(event){
		console.clear();

		$('button[name="random"]').attr('disabled', 'disabled');
		$('button[name="start"]').attr('disabled', 'disabled');

		bubbleSort(0, 1); // Отсортируем элементы по `data-id`
	});

	randomeSort();
}

function bubbleSort(first, second){
	const cols = sort.find('.col').toArray();

	if (!cols[second]){
		console.info('Проход закончен');

		const isComplited = cols.every(function(node, index, arr){
			return $(node).data('id') - 1 === index
		});

		if(isComplited){
			console.info('Сортировка простыми обменами завершена.');

			$('button[name="random"]').removeAttr('disabled');
			$('button[name="start"]').removeAttr('disabled');

			return false;
		}

		return bubbleSort(0, 1);
	}

	console.log('Сравниваем элементы с индексами:', first, second);

	if($(cols[first]).data('id') > $(cols[second]).data('id')){
		const duration = 288;
		const distance = $(cols[first]).outerWidth(true);

		console.log('Расстояние: ' + distance);

		// Окончание анимации привязываем к первому элементу
		$(cols[first])
			.animate({
				top: -50
			}, duration)
			.animate({
				left: distance
			}, duration)
			.animate({
				top: 0
			}, {
				duration: duration,
				complete: function(){
					console.log('Анимация выполнена.');

					$(cols[second]).removeAttr('style');
					$(cols[first]).removeAttr('style');

					$(cols[first]).before($(cols[second]));

					bubbleSort(second, ++second);
				}
			});

		// Второй элемент у нас всегда двигается только влево
		// При этом делаем задержку `delay`
		$(cols[second])
			.delay(duration)
			.animate({
				left: -distance
			}, duration);
	}
	else{
		bubbleSort(second, ++second);
	}
}

function randomeSort(){
	const cols = sort.find('.col').toArray();

	const comparing = function(a, b){
		return Math.random() - 0.5;
	}

	const appending = function(element, index){
		$(element).parent().append(element);
	}

	cols.sort(comparing).forEach(appending);
}

/* // Sort */
