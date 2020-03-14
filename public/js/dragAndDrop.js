/* dragAndDrop */

// https://api.jqueryui.com/draggable/
// https://professorweb.ru/my/javascript/jquery/level4/4_12.php

var dragAndDrop = $('#dragAndDrop');

if(notEmpty(dragAndDrop)){
	var containment = '.content';

	/* Ограничение допустимой области перемещения элемента */
	// Селектор			Если указан селектор, перетаскивание перемещаемого элемента ограничивается областью,
	//						занимаемой первым подходящим элементом
	// HTML - элемент	Перетаскивание ограничивается областью, занимаемой указанным элементом
	// Строка			Для ограничения области перетаскивания можно указать строку, содержащую одно из следующих значений:
	//						parent, document, window
	// Числовой массив	Область перетаскивания можно ограничить, определив ее числовым массивом координат формата [x1, y1, x2, y2]

	$('.dragElement').draggable({
		containment: "parent" // Ограничение допустимой области перемещения элемента
	}).filter('#dragH').draggable("option", "axis", "x"); // Ограничение направлений перемещения

	/* Использование событий взаимодействия Draggable */
	// create	Происходит в момент применения взаимодействия Draggable к элементу
	// start	Происходит в момент начала перетаскивания
	// drag		Происходит при каждом перемещении мыши в процессе перетаскивания элемента
	// stop		Происходит в момент отпускания кнопки мыши в процессе перетаскивания

	$('#draggable').draggable({
		containment: containment, // Ограничение допустимой области перемещения элемента
		// Происходит в момент начала перетаскивания
		start: function(event, ui){
			$('#draggable').text("Перетаскивание...");
		},
		// Происходит в момент отпускания кнопки мыши в процессе перетаскивания
		stop: function(event, ui){
			$('#draggable').text("Перетащи");
		}
	});

	/* Использование взаимодействия Droppable - Обработка перекрывания элементов */
	// create		Происходит в момент применения взаимодействия Droppable к элементу
	// activate		Происходит, когда пользователь начинает перетаскивать перемещаемый элемент
	// deactivate	Происходит, когда пользователь прекращает перетаскивать перемещаемый элемент
	// over			Происходит, когда пользователь перетаскивает перемещаемый элемент над принимающим элементом
	//					(но при условии, что кнопка мыши еще не была отпущена)
	// out			Происходит, когда пользователь перетаскивает перемещаемый элемент за пределы принимающего элемента
	// drop			Происходит, когда пользователь оставляет перемещаемый элемент на принимающем элементе

	$('#droppable').droppable({
		// Происходит, когда пользователь оставляет перемещаемый элемент на принимающем элементе
		drop: function(event, ui){
			ui.draggable.text("Оставлено");
		},
		// Происходит, когда пользователь начинает перетаскивать перемещаемый элемент
		activate: function(){
			$('#droppable').css({
				border: "medium double green",
				backgroundColor: "lightGreen"
			});
		},
		// Происходит, когда пользователь прекращает перетаскивать перемещаемый элемент
		deactivate: function(){
			$('#droppable').css("border", "").css("background-color", "");
		},
		// Происходит, когда пользователь перетаскивает перемещаемый элемент над принимающим элементом(но при условии, что кнопка мыши еще не была отпущена)
		over: function(){
			$('#droppable').css({
				border: "medium double red",
				backgroundColor: "red"
			});
		},
		// Происходит, когда пользователь перетаскивает перемещаемый элемент за пределы принимающего элемента
		out: function(event, ui){
			$('#droppable').css("border", "").css("background-color", "");
		},
		accept: '#draggable' // Ограничение допустимых перемещаемых элементов
	});

	/* Настройка взаимодействия Droppable */
	// disabled		Если эта опция равна true, то функциональность взаимодействия Droppable первоначально отключена.
	//				Значение по умолчанию — false
	// accept		Сужает множество перемещаемых элементов, на которые будет реагировать принимающий элемент.
	//					Значение по умолчанию — *, ему соответствует любой элемент
	// activeClass	Определяет класс, который будет присваиваться в ответ на событие activate
	//					и удаляться в ответ на событие deactivate
	// hoverClass	Определяет класс, который будет присваиваться в ответ на событие over и удаляться в ответ на событие out
	// tolerance	Определяет минимальную степень перекрывания, при которой происходит событие over

	/* Изменение порога перекрывания - tolerance */
	// fit			Перетаскиваемый элемент должен полностью находиться в области принимающего элемента
	// intersect	Перетаскиваемый элемент должен перекрываться с принимающим элементом по крайней мере наполовину.
	//					Это значение используется по умолчанию
	// pointer		Указатель мыши должен находиться в области принимающего элемента, независимо от того,
	//					где именно перетаскиваемый элемент был захвачен пользователем
	// touch		Означает любую степень перекрывания перетаскиваемого и принимающего элементов

	$('.draggable').draggable({
		containment: containment, // Ограничение допустимой области перемещения элемента
		start: function(event, ui){
			$('.draggable').text("Перетаскивание...");
		},
		stop: function(event, ui){
			$('.draggable').text("Fit, Touch");
		}
	});

	$('.droppable').droppable({
		drop: function(event, ui){
			ui.draggable.text("Оставлено");
		},
		// Сужает множество перемещаемых элементов, на которые будет реагировать принимающий элемент
		accept: '.draggable', // Ограничение допустимых перемещаемых элементов
		// activate/deactivate - Определяет класс, который будет присваиваться в ответ на событие activate и удаляться в ответ на событие deactivate
		activeClass: "active",
		// over/out - Определяет класс, который будет присваиваться в ответ на событие over и удаляться в ответ на событие out
		hoverClass: "hover",
		// Определяет минимальную степень перекрывания, при которой происходит событие over
		tolerance: "fit" // Перетаскиваемый элемент должен полностью находиться в области принимающего элемента
	});

	// Означает любую степень перекрывания перетаскиваемого и принимающего элементов
	$('#touchDrop').droppable("option", "tolerance", "touch");
}

/* // dragAndDrop */