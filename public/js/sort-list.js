/* Sort List */

var sort = $('.sort-elements');

if(notEmpty(sort)){
	sort.click(function(){
		var $elements = $('.elements li');
		var $target = $('.sorting ul');

		$elements.sort(function(a, b){
			var an = $(a).text(),
				bn = $(b).text();

			if(an && bn){
				return an.toUpperCase().localeCompare(bn.toUpperCase());
			}

			return 0;
		});

		$elements.detach().appendTo($target);
	});
}

/* // Sort List */
