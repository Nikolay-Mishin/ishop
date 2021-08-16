<?php

namespace app\models\collection;

use ishop\libs\collection\Collection;

class BookStore {

	function addBooks(BookCollection $books) {
		// реализация
	}

	function addMagazines(MagazineCollection $magazines) {
		// реализация
	}

	// Если тип коллекции не важен, можно указать базовый тип Collection
	function addGoods(Collection $goods) {
		// реализация
	}

}
