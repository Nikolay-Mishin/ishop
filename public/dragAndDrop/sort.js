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
}

var dragAndDrop = $('#dragAndDrop');

if(notEmpty(dragAndDrop)){
	const containment = 'parent',
		accept = '.col',
		$drag = dragAndDrop.find('.col'),
		$drop = $drag;

	var distance,
		drag,
		context,
		offset,
		position;

	$drag.draggable({
		containment: containment, // Ограничение допустимой области перемещения элемента
		create: function(event, ui){
			//console.log(ui);
		},
		// Происходит в момент начала перетаскивания
		start: function(event, ui){
			//$('#draggable').text("Перетаскивание...");
			console.log(ui);
		},
		// Происходит в момент отпускания кнопки мыши в процессе перетаскивания
		stop: function(event, ui){
			//$('#draggable').text("Перетащи");
			console.log(ui);
		}
	});

	$drop.droppable({
		create: function(event, ui){
			//console.log(ui);
		},
		// Происходит, когда пользователь оставляет перемещаемый элемент на принимающем элементе
		drop: function(event, ui){
			//const $this = $(this),
			//	distance = $this.outerWidth(true),
			//	drag = ui.draggable,
			//	context = drag.context,
			//	offset = {
			//		top: context.offsetTop,
			//		left: context.offsetLeft
			//	},
			//	position = {
			//		top: ui.position.top,
			//		left: ui.position.left
			//	};
			//console.log(ui);
			//console.log('Расстояние: ' + distance);
			//console.log(offset.top);
			//console.log(offset.left);
			//console.log(offset.top - ui.position.top);
			//console.log(offset.left - ui.position.left);
			setArgs(this, ui);
			console.log({
				distance,
				drag,
				context,
				offset,
				position
			});
		},
		// Происходит, когда пользователь начинает перетаскивать перемещаемый элемент
		activate: function(event, ui){
			$(this).css({
				border: "medium double green"
			});
			const $this = $(this),
				distance = $this.outerWidth(true),
				drag = ui.draggable,
				offset = {
					top: ui.draggable.context.offsetTop,
					left: ui.draggable.context.offsetLeft
				};
		},
		// Происходит, когда пользователь прекращает перетаскивать перемещаемый элемент
		deactivate: function(){
			$(this).css("border", "").css("background-color", "");
		},
		// Происходит, когда пользователь перетаскивает перемещаемый элемент над принимающим элементом(но при условии, что кнопка мыши еще не была отпущена)
		over: function(){
			$(this).css({
				border: "medium double red"
			});
		},
		// Происходит, когда пользователь перетаскивает перемещаемый элемент за пределы принимающего элемента
		out: function(event, ui){
			$(this).css("border", "").css("background-color", "");
		},
		accept: accept // Ограничение допустимых перемещаемых элементов
	});
}

function setArgs($this, ui){
	var $this = $($this);
	distance = $this.outerWidth(true);
	drag = ui.draggable;
	context = drag.context;
	offset = {
		top: context.offsetTop,
		left: context.offsetLeft
	},
	position = {
		top: ui.position.top,
		left: ui.position.left
	};
	console.log(ui);
	console.log('Расстояние: ' + distance);
	console.log(offset.top);
	console.log(offset.left);
	console.log(offset.top - ui.position.top);
	console.log(offset.left - ui.position.left);
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
				top: -60
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
