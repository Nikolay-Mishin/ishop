<?php
// Контроллер коллекций

namespace app\controllers;

use ishop\libs\collection\CollectionFactory;
use app\models\collection\BookStore;
use app\models\collection\Book;
use app\models\collection\Magazine;
use app\models\Breadcrumbs;

class CollectionController extends AppController {

	public function indexAction(): void {
		$collectionFactory = CollectionFactory::instance();
		debug(['CollectionFactory' => $collectionFactory]);
		debug(['create' => $collectionFactory->_create(new Book(1))]);

		$booksTest = CollectionFactory::create(new Book(1));
		debug(['Book' => Book::class]);
		debug(['$booksTest' => $booksTest]);

		// Создаём коллекцию
		$books = CollectionFactory::create(Book::class);

		// Добавим объектов в коллекцию:
		$books->add(new Book(1), new Book(2));
		$books->add(new Book(3))->add(new Book(4));
		$books[] = new Book(5);

		//$books->add(new Magazine(1)); // Ошибка (неверный тип)

		$magazines = CollectionFactory::create(Magazine::class);
		$magazines->add(new Magazine(1));


		$bookStore = new BookStore();
		$bookStore->addBooks($books); // Всё в порядке
		//$bookStore->addBooks($magazines); // Ошибка (неверный тип)
		$bookStore->addMagazines($magazines); // Всё в порядке
		$bookStore->addGoods($books); // Всё в порядке
		$bookStore->addGoods($magazines); // Всё в порядке

		$iteratorBooks = $books->getIterator();

		debug(['get_class($books)' => get_class($books)]); // BookCollection
		debug(['count($books)' => count($books)]); // 5
		foreach ($books as $book) {
			debug(['book->id' => $book->id]);
		}
		// 12345
		debug(['bookStore'=> $bookStore]);
		debug(['iteratorBooks' => $iteratorBooks]);
		debug(['iteratorBooks->count()' => $iteratorBooks->count()]);
		//debug(['$books->curr()' => $books->curr()]); // Ошибка (неверный метод)
		debug(['$books->getArrayCopy()' => $books->getArrayCopy()]);

		$breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Чат'); // хлебные крошки;

		$this->setMeta('Коллекции');
		$this->set(compact('breadcrumbs'));
	}

}
