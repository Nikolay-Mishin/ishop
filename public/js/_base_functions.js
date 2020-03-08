function isset(target, selector = 'div'){
	return $(target[0] ? target[0].localName : selector).is(target);
}
