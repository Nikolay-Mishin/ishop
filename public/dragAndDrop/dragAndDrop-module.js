/* dragAndDrop Module */

const dragAndDrop = (function(){
	// Переменные модуля
	const instances = {},
		wrapper = '#dragAndDrop';

	// Методы модуля
	const init = function(args = {}){
			return typeof args === 'object' ? new instance(args) : null;
		},

		getInstances = function(){
			return instances;
		},

		getInstance = function(instance){
			return instances[instance];
		},

		instance = function(args){
			this.wrapper = args.wrapper || wrapper;
			this.id = args.id || this.wrapper;
			this.$dragAndDrop = $(this.wrapper);

			if(isEmpty(this.$dragAndDrop)) return null;

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

					$this.animate($this.drag, $this.drop, $this.duration, function(drag, drop, drag_prev, drop_prev, isPair, isDrag){
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
				accept: this.accept // Ограничение допустимых перемещаемых элементов
			});

			this.setArgs = function(ui){
				console.log('setArgs');
				console.log($this);

				$this.drag = ui.draggable;
				$this.setDistance();
				$this.context = $this.drag.context;
				$this.offset = {
					top: $this.context.offsetTop,
					left: $this.context.offsetLeft
				};
				$this.position = {
					top: ui.position.top,
					left: ui.position.left
				};

				console.log(ui);
				console.log('Расстояние: ' + $this.distance);
				console.log('offset.top: ' + $this.offset.top);
				console.log('offset.left: ' + $this.offset.left);
				console.log('distance.top: ' + ($this.offset.top - $this.position.top));
				console.log('distance.left: ' + ($this.offset.left - $this.position.left));
			};

			this.setDistance = function(){
				console.log('setDistance');
				console.log($this);

				console.log('Drop', $this.drop);
				console.log('Drag', $this.drag);
				console.log('drag.left: ' + $this.distance);
				console.log('drop.left: ' + $this.drop.offset().left);

				$this.distance = $this.drop.offset().left - $this.distance;

				console.log('distance: ' + $this.distance);
			};

			this.animate = function(drag, drop, duration, callback = null){
				console.log('animate');
				console.log($this);

				callback = callback !== null ? callback : function () { };

				console.log('Drag: ', $this.drag);
				console.log('Drop: ', $this.drop);
				console.log('Расстояние: ' + $this.distance);
				console.log('Задержка: ' + $this.duration);

				const drag_prev = notEmpty($this.drag.prev()) ? this.drag.prev() : $this.drag,
					isDrag = $this.drag.is(drag_prev),
					isPair = $this.drag.is($this.drop.prev()),
					drop_prev = isPair ? $this.drop : $this.drop.prev();

				console.log('drag_prev: ', drag_prev);
				console.log('isDrag: ', isDrag);
				console.log('isPair: ', isPair);
				console.log('drop_prev: ', drop_prev);

				// Окончание анимации привязываем к первому элементу
				$this.drag
					.animate({
						left: $this.distance
					})
					.animate({
						left: $this.distance
					}, {
						duration: $this.duration,
						complete: function(){
							console.log('Анимация выполнена');

							$this.drop.removeAttr('style');
							$this.drag.removeAttr('style');

							callback.call($this, $this.drag, $this.drop, drag_prev, drop_prev, isPair, isDrag);
						}
					});

				// Второй элемент у нас всегда двигается только влево
				// При этом делаем задержку `delay`
				$this.drop
					.delay($this.duration)
					.animate({
						left: -$this.distance
					}, $this.duration);
			};
		};

	return {
		init,
		getInstances,
		getInstance
	};
})();

dragAndDrop.init();

console.log('dragAndDrop \n', dragAndDrop);
console.log('dragAndDrop.getInstances \n', dragAndDrop.getInstances());
console.log('dragAndDrop.getInstance("#dragAndDrop") \n', dragAndDrop.getInstance('#dragAndDrop-2'));

/* // dragAndDrop Module */
