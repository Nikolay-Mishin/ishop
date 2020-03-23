/*
function asdf() {
	this.dsa = function() {
		alert(123);
	}
}
let Asdf = new asdf();
Asdf.dsa();
*/

function delegate(selector, event, callback = function(){}, delegate = document){
	$(delegate).on(event, selector, function(e){
		callback.call(this, e, $(selector), $(delegate), event);
	});
}

function isset(target, selector = 'div'){
	return $(target[0] ? target[0].localName : selector).is(target);
}

function notEmpty(obj){
	return !isEmpty(obj);
}

function isEmpty(obj){
	// Speed up calls to hasOwnProperty
	var hasOwnProperty = Object.prototype.hasOwnProperty;

	// null and undefined are "empty"
	if(obj == null) return true;

	// Assume if it has a length property with a non-zero value
	// that that property is correct.
	if(obj.length > 0) return false;
	if(obj.length === 0) return true;

	// If it isn't an object at this point
	// it is empty, but it can't be anything *but* empty
	// Is it empty?  Depends on your application.
	if(typeof obj !== "object") return true;

	// Otherwise, does it have any properties of its own?
	// Note that this doesn't handle
	// toString and valueOf enumeration bugs in IE < 9
	for(var key in obj){
		if(hasOwnProperty.call(obj, key)) return false;
	}

	return true;

	/*
	isEmpty(""), // true
	isEmpty(33), // true (arguably could be a TypeError)
	isEmpty([]), // true
	isEmpty({}), // true
	isEmpty({length: 0, custom_property: []}), // true

	isEmpty("Hello"), // false
	isEmpty([1,2,3]), // false
	isEmpty({test: 1}), // false
	isEmpty({length: 3, custom_property: [1,2,3]}) // false
	*/
}

function freeze(obj){
	// Заморозить сам объект obj (ничего не произойдёт, если он уже заморожен)
	return Object.freeze(obj);
}

// Чтобы сделать объект obj полностью неизменяемым, замораживаем каждый объект в объекте obj.
// Для этого воспользуемся этой функцией.
function deepFreeze(obj){
	// Получаем имена свойств из объекта obj
	var propNames = Object.getOwnPropertyNames(obj);

	// Замораживаем свойства для заморозки самого объекта
	propNames.forEach(function(name){
		var prop = obj[name];
		// Заморозка свойства prop, если оно объект
		if(typeof prop === 'object' && prop !== null)
			deepFreeze(prop);
	});

	// Заморозить сам объект obj (ничего не произойдёт, если он уже заморожен)
	return freeze(obj);
}

function getKeys(obj){
	return Object.keys(obj);
};

function objectToArray(obj){
	return Object.entries(obj);
};

function filterObject(obj, search, searchKey = '', strict = true){
	return objectToArray(obj).filter(obj => {
		obj = obj[1];
		return searchKey ? searchInObject(obj[searchKey], search, strict) : searchInObject(obj, search, strict);
	});
}

function searchInObject(obj, search, strict = true){
	if(obj.__proto__.jquery) obj = obj.selector;
	return typeof obj === 'string' ? includes(obj, search, strict) : getKeys(obj).some(key => {
		return typeof obj[key] === 'object' ? searchInObject(obj[key], search) : includes(obj[key], search, strict);
	});
}

function includes(str, search, strict = true){
	return strict ? str == search : str.includes(search);
}
