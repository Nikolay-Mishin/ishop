/* dragAndDrop Module */

const dragAndDrop = (function(){
	// Переменные модуля

	const instances = {},
		wrapper = 'dragAndDrop',
		drag_wrapper = '.draggable',
		drop_wrapper = '.droppable',
		containment = 'parent', // false
		axis = 'x', // false
		tolerance = 'intersect',
		duration = 288;

	var id,
		length = 0;

	// Методы модуля

	const dragAndDrop = function(args = {}){
		return new init(args);
	};

	const get = dragAndDrop.get = function(instance = ''){
		return !instance ? instances : (instances[instance] || filterInstances(`#${instance}`));
	};

	const filterInstances = function(instance, searchKey = 'wrapper'){
		var filter = filterObject(instances, instance, searchKey);
		return filter.length ? filter[0] : {};
	};

	const getInstancesKeys = function(){
		return getKeys(instances);
	};

	const getInstancesLength = function(){
		var matches = [];
		for(var property of getInstancesKeys()){
			var match = property.match(new RegExp(wrapper));
			if(match) matches.push(match);
		}
		length = matches.length;
	};
	
	const init = function(args = {}){
		if(typeof args !== 'object') return null;
		getInstancesLength();
		id = `${wrapper}-${length + 1}`;
		return new instance(args);
	};

	const instance = function(args){
		this.id = args.id || id;
		this.wrapper = args.wrapper || isEmpty($(`#${this.id}`)) ? `#${wrapper}` : `#${this.id}`;
		this.$dragAndDrop = $(this.wrapper);

		if(isEmpty(this.$dragAndDrop)) return null;

		this.drag_wrapper = args.drag || drag_wrapper;
		this.drop_wrapper = args.drop === 'drag' ? this.drag_wrapper : (args.drop || drop_wrapper);
		this.$drag = this.$dragAndDrop.find(this.drag_wrapper);
		this.$drop = this.$dragAndDrop.find(this.drop_wrapper);

		if(isEmpty(this.$drag) || isEmpty(this.$drop)) return null;

		this.containment = args.containment || containment;
		this.axis = args.axis || axis;
		this.tolerance = args.tolerance || tolerance;
		this.accept = args.accept || this.drag_wrapper; // '*'
		this.duration = args.duration || duration;

		instances[this.id] = this;

		const $this = this;

		this.drop;
		this.drag;
		this.drag_prev;
		this.drop_prev;
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
					drag_prev: $this.drag_prev,
					drop_prev: $this.drop_prev,
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

			this.drag = ui.draggable;
			this.setDistance();
			this.context = this.drag.context;
			this.offset = {
				top: this.context.offsetTop,
				left: this.context.offsetLeft
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

			console.log('Drop', this.drop);
			console.log('Drag', this.drag);
			console.log('drag.left: ' + this.distance);
			console.log('drop.left: ' + this.drop.offset().left);

			this.distance = this.drop.offset().left - this.distance;

			console.log('distance: ' + this.distance);
		};

		this.animate = function(drag, drop, duration, callback = null){
			console.log('animate');

			callback = callback !== null ? callback : function(){};

			console.log('Drag: ', this.drag);
			console.log('Drop: ', this.drop);
			console.log('Расстояние: ' + this.distance);
			console.log('Задержка: ' + this.duration);

			this.drag_prev = notEmpty(this.drag.prev()) ? this.drag.prev() : this.drag;
			const isDrag = this.drag.is(this.drag_prev),
				isPair = this.drag.is(this.drop.prev());
			this.drop_prev = isPair ? this.drop : this.drop.prev();

			console.log('drag_prev: ', this.drag_prev);
			console.log('isDrag: ', isDrag);
			console.log('isPair: ', isPair);
			console.log('drop_prev: ', this.drop_prev);

			console.log('animate(): ', this.getAnimate());
			console.log('animate(true): ', this.getAnimate(true));

			this.setAnimate(this.drag, false, callback, isPair, isDrag);
			this.setAnimate(this.drop, true);
		};

		this.setAnimate = function(target, isDrop, callback, isPair, isDrag){
			console.log('setAnimate');

			// Второй элемент у нас всегда двигается только влево
			// При этом делаем задержку `delay`
			if(isDrop) target.delay(this.duration);
			target.animate(this.getAnimate(isDrop), this.duration);
			// Окончание анимации привязываем к первому элементу
			if(!isDrop) target.animate(this.getAnimate(isDrop), {
				complete: function(){
					console.log('Анимация выполнена');

					$this.drop.removeAttr('style');
					target.removeAttr('style');

					callback.call($this, target, $this.drop, $this.drag_prev, $this.drop_prev, isPair, isDrag);
				}
			});
		};

		this.getAnimate = function(isDrop = false){
			console.log('getAnimate');

			const animate = {};
			animate[this.axis === 'x' ? 'left' : 'top'] = !isDrop ? this.distance : -this.distance;
			return animate;
		};
	};

	return dragAndDrop;
})();

dragAndDrop({ drop: 'drag' });
dragAndDrop({ drop: 'drag' });

console.log('dragAndDrop() \n', dragAndDrop.get());
console.log('dragAndDrop("#dragAndDrop") \n', dragAndDrop.get('dragAndDrop'));
console.log('dragAndDrop("#dragAndDrop-2") \n', dragAndDrop.get('dragAndDrop-2'));

/* // dragAndDrop Module */
