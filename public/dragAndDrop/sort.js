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
		$drop = $drag,
		duration = 288;

	var drop,
		drag,
		distance,
		dist,
		context,
		offset,
		position;

	$drag.draggable({
		containment: containment, // Ограничение допустимой области перемещения элемента
		axis: 'x',
		create: function(event, ui){
			console.log('Drag - create');
		},
		// Происходит в момент начала перетаскивания
		start: function(event, ui){
			console.clear();
			console.log('Drag - start');
			console.log(this);
			dist = $(this).offset().left;
			//$('#draggable').text("Перетаскивание...");
		},
		// Происходит в момент отпускания кнопки мыши в процессе перетаскивания
		stop: function(event, ui){
			console.log('Drag - stop');
			//$('#draggable').text("Перетащи");
		}
	});

	$drop.droppable({
		create: function(event, ui){
			console.log('Drop - create');
		},
		// Происходит, когда пользователь оставляет перемещаемый элемент на принимающем элементе
		drop: function(event, ui){
			console.log('Drag - drop');
			drop = $(this);
			setArgs(ui);
			console.log({
				drop,
				drag,
				distance,
				dist,
				context,
				offset,
				position
			});
			animate($(drag), drop, duration, function(drag, drop, drag_prev, drop_prev, isPair, isDrag){
				if (!isDrag) drag_prev.after(drop);
				else drag_prev.before(drop);

				if (!isPair) drop_prev.after(drag);
			});
		},
		// Происходит, когда пользователь начинает перетаскивать перемещаемый элемент
		activate: function(event, ui){
			console.log('Drop - activate');
			$(this).css({ border: "medium double green" });
		},
		// Происходит, когда пользователь прекращает перетаскивать перемещаемый элемент
		deactivate: function(){
			console.log('Drop - deactivate');
			$(this).css({ border: "", backgroundColor: "" });
		},
		// Происходит, когда пользователь перетаскивает перемещаемый элемент над принимающим элементом(но при условии, что кнопка мыши еще не была отпущена)
		over: function(){
			console.log('Drag - over');
			$(this).css({ border: "medium double red" });
		},
		// Происходит, когда пользователь перетаскивает перемещаемый элемент за пределы принимающего элемента
		out: function(event, ui){
			console.log('Drag - out');
			$(this).css({ border: "", "background-color": "" });
		},
		accept: accept // Ограничение допустимых перемещаемых элементов
	});
}

function setArgs(ui){
	drag = ui.draggable;
	//distance = drag.outerWidth(true);
	distance = getDistance();
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
	console.log('offset.top: ' + offset.top);
	console.log('offset.left: ' + offset.left);
	console.log('distance.top: ' + (offset.top - position.top));
	console.log('distance.left: ' + (offset.left - position.left));
}

function getDistance(){
	console.log('Drop', drop);
	console.log('Drag', drag);
	console.log('drag.left: ' + dist);
	console.log('drop.left: ' + drop.offset().left);
	dist = drop.offset().left - dist;
	console.log('dist: ' + dist);
	return dist;
}

function animate(drag, drop, duration, callback = null){
	callback = callback !== null ? callback : function(){};

	console.log('Drag: ', drag);
	console.log('Drop: ', drop);
	console.log('Расстояние: ' + distance);
	console.log('Задержка: ' + duration);

	var drag_prev = notEmpty(drag.prev()) ? drag.prev() : drag,
		isDrag = drag.is(drag_prev),
		isPair = drag.is(drop.prev()),
		drop_prev = isPair ? drop : drop.prev();

	console.log('drag_prev: ', drag_prev);
	console.log('isDrag: ', isDrag);
	console.log('isPair: ', isPair);
	console.log('drop_prev: ', drop_prev);

	// Окончание анимации привязываем к первому элементу
	$(drag)
		.animate({
			left: distance
		})
		.animate({
			left: distance
		}, {
			duration: duration,
			complete: function(){
				console.log('Анимация выполнена.');

				drop.removeAttr('style');
				drag.removeAttr('style');

				callback.call(this, drag, drop, drag_prev, drop_prev, isPair, isDrag);
			}
		});

	// Второй элемент у нас всегда двигается только влево
	// При этом делаем задержку `delay`
	$(drop)
		.delay(duration)
		.animate({
			left: -distance
		}, duration);
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
