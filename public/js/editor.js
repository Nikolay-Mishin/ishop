// CK Editor
var editors = $('.editor');

if(notEmpty(editors)){
	for (var i = 0; i < editors.length; i++){
		var editor = $(editors[i]);
		if (editors.length > 1) editor.prop('id', editor.prop('id') + '_' + (i + 1));
		editor.ckeditor();
	}
	editors = CKEDITOR.instances;
	console.log(editors);
	for (var editor in editors){
		editorOnChange(editor);
	}
}

//function getEditor(target, find = '.editor', prop = 'id'){
//	return editors ? editors[typeof target == 'object' ? target.find(find).prop(prop) : target] : null;
//}

//function editorOnChange(target, callback = function(){}){
//	var editor = getEditor(target),
//		value;
//	if(!editor) return false;
//	editor.on('change', function(){
//		this.updateElement();
//		callback(this._.data, $(this.element.$), this);
//	});
//	return value;
//}
