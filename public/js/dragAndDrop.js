/* dragAndDrop */

var dragAndDrop = $('#dragAndDrop');

if (notEmpty(dragAndDrop)) {
	var dragElement = $('.dragElement');
	dragElement.draggable({
		containment: "parent"
	}).filter('#dragH').draggable("option", "axis", "x");
}

/* // dragAndDrop */
