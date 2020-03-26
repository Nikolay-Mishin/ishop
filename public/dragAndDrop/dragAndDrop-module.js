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
			create: function(){
				console.log('Drag - create');
			},
			// Происходит в момент начала перетаскивания
			start: function(){
				console.clear();
				console.log('Drag - start');
				
				$this.distance = $(this).offset().left;
			},
			// Происходит в момент отпускания кнопки мыши в процессе перетаскивания
			stop: function(){
				console.log('Drag - stop');
			}
		});

		this.$drop.droppable({
			create: function(){
				console.log('Drop - create');
			},
			// Происходит, когда пользователь оставляет перемещаемый элемент на принимающем элементе
			drop: function(event, ui){
				console.log('Drag - drop');
				
				$this.setArgs(ui, $(this));
				$this.animate(function(isPair, isDrag){
					console.log({
						drop: this.drop,
						drag: this.drag,
						drag_prev: this.drag_prev,
						drop_prev: this.drop_prev,
						distance: this.distance,
					});

					if(!isDrag) this.drag_prev.after(this.drop);
					else this.drag_prev.before(this.drop);

					if(!isPair) this.drop_prev.after(this.drag);
				});
			},
			// Происходит, когда пользователь начинает перетаскивать перемещаемый элемент
			activate: function(){
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
			out: function(){
				console.log('Drag - out');
				$(this).css({ border: "", "background-color": "" });
			},
			accept: this.accept // Ограничение допустимых перемещаемых элементов
		});

		this.setArgs = function(ui, drop){
			this.drag = ui.draggable;
			this.drop = drop;
			this.setDistance();
		};

		this.setDistance = function(){
			this.distance = this.drop.offset().left - this.distance;
		};

		this.animate = function(callback){
			this.drag_prev = notEmpty(this.drag.prev()) ? this.drag.prev() : this.drag;
			const isDrag = this.drag.is(this.drag_prev),
				isPair = this.drag.is(this.drop.prev());
			this.drop_prev = isPair ? this.drop : this.drop.prev();

			this.setAnimate(this.drag, false, callback, isPair, isDrag);
			this.setAnimate(this.drop, true);
		};

		this.setAnimate = function(target, isDrop, callback, isPair, isDrag){
			// Второй элемент у нас всегда двигается только влево
			// При этом делаем задержку `delay`
			if(isDrop) $this.drop.delay(this.duration);
			target.animate(this.getAnimate(isDrop), this.duration);
			// Окончание анимации привязываем к первому элементу
			if(!isDrop) this.drag.animate(this.getAnimate(isDrop), {
				complete: function(){
					console.log('Анимация выполнена');
					$this.removeStyle();
					callback.call($this, isPair, isDrag);
				}
			});
		};

		this.getAnimate = function(isDrop = false){
			const animate = {};
			animate[this.axis === 'x' ? 'left' : 'top'] = !isDrop ? this.distance : -this.distance;
			return animate;
		};

		this.removeStyle = function(){
			$this.drop.removeAttr('style');
			$this.drag.removeAttr('style');
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
