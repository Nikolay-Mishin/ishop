// CK Editor
var editors = $('.editor');

if(notEmpty(editors)){
	console.log({ editors: editors });
	for(var editor of editors){
		console.log({ editor: editor });
	}
	for(var i = 0; i < editors.length; i++){
		var editor = $(editors[i]);
		if (editors.length > 1) editor.prop('id', editor.prop('id') + '_' + (i + 1));
		editor.ckeditor();
	}
	editors = getEditors();
	for(var editor in editors){
		editorOnChange(editor);
		editor = editors[editor];
		console.log({ editor: editor });
		console.log({ id: editor.id, element: editor.element, $: editor.element.$ });
	}
}
