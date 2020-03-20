/* dragAndDrop Module */

const dragAndDrop = (function(){
	// Переменные модуля
	const instances = {};

	// Методы модуля
	const init = function(args){
		return typeof args === 'object' ? new instance(args) : null;
		},

		instance = function(args){
			this.id = args.id || args.wrapper;
			this.$dragAndDrop = $(args.wrapper);

			instances[this.id] = this;
		},

		getInstances = function(){
			return instances;
		},

		getInstance = function (instance){
			return instances[instance];
		},

		instance2 = function(args){
			this.wrapper = args.wrapper || '#dragAndDrop';
			this.id = args.id || this.wrapper;
			this.$dragAndDrop = $(this.wrapper);

			if(notEmpty(this.$dragAndDrop)) return null;

			instances[this.id] = this;

			const $this = this;

			console.log(this);

			this.containment = args.containment || 'parent';
			this.axis = args.axis || 'x';
			this.accept = args.accept || '.col';
			this.$drag = args.$drag || this.$dragAndDrop.find(this.accept);
			this.$drop = args.$drop || this.$drag;
			this.duration = args.duration || 288;

			this.drop;
			this.drag;
			this.distance;
			this.context;
			this.offset;
			this.position;

			this.$drag.draggable({
				containment: this.containment, // Ограничение допустимой области перемещения элемента
				axis: this.axis,
				create: function(event, ui){
					console.log('Drag - create');
				},
				// Происходит в момент начала перетаскивания
				start: function(event, ui){
					console.clear();

					console.log('Drag - start');
					console.log(this);

					$this.drag = $(this);
					$this.distance = $this.drag.offset().left;
				},
				// Происходит в момент отпускания кнопки мыши в процессе перетаскивания
				stop: function(event, ui){
					console.log('Drag - stop');
				}
			});

			this.$drop.droppable({
				create: function(event, ui){
					console.log('Drop - create');
				},
				// Происходит, когда пользователь оставляет перемещаемый элемент на принимающем элементе
				drop: function(event, ui){
					console.log('Drag - drop');
					console.log(this);

					$this.drop = $(this);
					$this.setArgs(ui);

					console.log({
						drop: $this.drop,
						drag: $this.drag,
						distance: $this.distance,
						dist: $this.dist,
						context: $this.context,
						offset: $this.offset,
						position: $this.position
					});

					//$this.animate($this.drag, $this.drop, duration, function(drag, drop, drag_prev, drop_prev, isPair, isDrag){
					//	if(!isDrag) drag_prev.after(drop);
					//	else drag_prev.before(drop);

					//	if(!isPair) drop_prev.after(drag);
					//});
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
				accept: this.accept // Ограничение допустимых перемещаемых элементов
			});

			this.setArgs = function(ui){
				console.log('setArgs');
				console.log(this);

				this.drag = ui.draggable;
				setDistance();
				this.context = drag.context;
				this.offset = {
					top: context.offsetTop,
					left: context.offsetLeft
				};
				this.position = {
					top: ui.position.top,
					left: ui.position.left
				};

				console.log(ui);
				console.log('Расстояние: ' + this.distance);
				console.log('offset.top: ' + this.offset.top);
				console.log('offset.left: ' + this.offset.left);
				console.log('distance.top: ' + (this.offset.top - this.position.top));
				console.log('distance.left: ' + (this.offset.left - this.position.left));
			};

			this.setDistance = function(){
				console.log('setDistance');
				console.log(this);

				console.log('Drop', this.drop);
				console.log('Drag', this.drag);
				console.log('drag.left: ' + this.distance);
				console.log('drop.left: ' + this.drop.offset().left);

				this.distance = this.drop.offset().left - this.distance;

				console.log('distance: ' + this.distance);
			};

			this.animate = function(drag, drop, duration, callback = null){
				console.log('animate');
				console.log(this);

				callback = callback !== null ? callback : function () { };

				console.log('Drag: ', this.drag);
				console.log('Drop: ', this.drop);
				console.log('Расстояние: ' + this.distance);
				console.log('Задержка: ' + this.duration);

				const drag_prev = notEmpty(this.drag.prev()) ? this.drag.prev() : this.drag,
					isDrag = this.drag.is(drag_prev),
					isPair = this.drag.is(this.drop.prev()),
					drop_prev = isPair ? this.drop : this.drop.prev();

				console.log('drag_prev: ', drag_prev);
				console.log('isDrag: ', isDrag);
				console.log('isPair: ', isPair);
				console.log('drop_prev: ', drop_prev);

				// Окончание анимации привязываем к первому элементу
				this.drag
					.animate({
						left: distance
					})
					.animate({
						left: distance
					}, {
						duration: duration,
						complete: function(){
							console.log('Анимация выполнена');

							this.drop.removeAttr('style');
							this.drag.removeAttr('style');

							callback.call(this, this.drag, this.drop, drag_prev, drop_prev, isPair, isDrag);
						}
					});

				// Второй элемент у нас всегда двигается только влево
				// При этом делаем задержку `delay`
				this.drop
					.delay(duration)
					.animate({
						left: -distance
					}, duration);
			};
		};

	return {
		init,
		getInstances,
		getInstance,
		//instance: new instance2
	};
})();

dragAndDrop.init({ wrapper: '#dragAndDrop' });

console.log('dragAndDrop \n', dragAndDrop);
console.log('dragAndDrop.getInstances \n', dragAndDrop.getInstances());
console.log('dragAndDrop.getInstance("#dragAndDrop") \n', dragAndDrop.getInstance('#dragAndDrop'));

/* // dragAndDrop Module */

/* dragAndDrop */

var $dragAndDrop = $('#dragAndDrop');

if(notEmpty($dragAndDrop)){
	const containment = 'parent',
		accept = '.col',
		$drag = $dragAndDrop.find('.col'),
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

			drag = $(this);
			distance = drag.offset().left;
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
			console.log(this);

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

			animate(drag, drop, duration, function(drag, drop, drag_prev, drop_prev, isPair, isDrag){
				if(!isDrag) drag_prev.after(drop);
				else drag_prev.before(drop);

				if(!isPair) drop_prev.after(drag);
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
	console.log('setArgs');
	console.log(this);

	drag = ui.draggable;
	//distance = drag.outerWidth(true);
	setDistance();
	context = drag.context;
	offset = {
		top: context.offsetTop,
		left: context.offsetLeft
	};
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

function setDistance(){
	console.log('setDistance');
	console.log(this);

	console.log('Drop', drop);
	console.log('Drag', drag);
	console.log('drag.left: ' + distance);
	console.log('drop.left: ' + drop.offset().left);

	distance = drop.offset().left - distance;

	console.log('distance: ' + distance);
}

function animate(drag, drop, duration, callback = null){
	console.log('animate');
	console.log(this);

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
	drag
		.animate({
			left: distance
		})
		.animate({
			left: distance
		}, {
			duration: duration,
			complete: function(){
				console.log('Анимация выполнена');

				drop.removeAttr('style');
				drag.removeAttr('style');

				callback.call(this, drag, drop, drag_prev, drop_prev, isPair, isDrag);
			}
		});

	// Второй элемент у нас всегда двигается только влево
	// При этом делаем задержку `delay`
	drop
		.delay(duration)
		.animate({
			left: -distance
		}, duration);
}

/* // dragAndDrop */

/* Sort */

var sort = $('#sort');

if (notEmpty(sort)) {
	$('button[name="random"]').on('click', randomeSort); // Рандомно перемешаем блоки

	$('button[name="start"]').on('click', function (event) {
		console.clear();

		$('button[name="random"]').attr('disabled', 'disabled');
		$('button[name="start"]').attr('disabled', 'disabled');

		bubbleSort(0, 1); // Отсортируем элементы по `data-id`
	});
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
