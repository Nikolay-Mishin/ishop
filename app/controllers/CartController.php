<?php
// Контроллер корзины
/** добавлять в корзину можно
 * с главной (базовый)
 * из карточки товара (базовый/модификация/количество)
 * из выбранной категории
 * из поиска
 * из фильтра (асинхронно) / добавлять товар динамично (товар, которого изначально на странице не было / делегировать событие)
 */
// за счет data-атрибута (data-id) с id продукта через JS идет добавление в корзину (без перезагрузки страницы)
// если JS отключен, то добавление идет за счет ссылки href="cart/add?id=1"

namespace app\controllers;

use app\models\Breadcrumbs; // модель хлебных крошек
use app\models\Cart; // модель корзины
use app\models\Modification; // модель модификаторов товара
use app\models\Product; // модель модификаторов товара
use app\models\Order; // модель заказов
use app\models\User; // модель пользователя
use ishop\App;

class CartController extends AppController {

	// отображает вид корзины при переходе к оформлению заказа
	public function viewAction(): void {
		$breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Корзина'); // хлебные крошки
		$this->setMeta('Корзина');
		$this->set(compact('breadcrumbs'));
	}

	// отображает вид корзины
	public function showAction(): void {
		$this->loadView('cart_modal'); // метод фреймворка (Controller) для загрузки отдельного вида
	}

	// добавляет товары в корзину
	public function addAction(): void {
		// (int) - приводим к числу
		$id = !empty($_GET['id']) ? (int) $_GET['id'] : null; // id товара
		$qty = !empty($_GET['qty']) ? (int) $_GET['qty'] : null; // количество товара
		$mod_id = !empty($_GET['mod']) ? (int) $_GET['mod'] : null; // id модификатора товара
		// если id получен, получаем товар
		if ($id) {
			// если товар не получен, возвращаем false
			if (!$product = Product::getById($id)) {
				throw new Exception("Страница не найдена", 404);
			}
			// если передан id модификатора, получаем информацию по данному модификатору
			$mod = Modification::getModByProductId($mod_id, $id);
			$cart = new Cart(); // объект корзины
			$cart->addToCart($product, $qty, $mod); // вызываем метод для добавления в корзину
		}
		$this->show(); // отображаем вид корзины
	}

	// удаляет товар из корзины
	public function deleteAction(): void {
		$id = !empty($_GET['id']) ? $_GET['id'] : null; // id удаляемого товара
		if (isset($_SESSION['cart'][$id])) {
			$cart = new Cart(); // объект корзины
			$cart->deleteItem($id); // удаляем данный элемент из корзины
		}
		$this->show(); // отображаем вид корзины
	}

	// очищает корзину
	public function clearAction(): void {
		// удаляем элементы корзины из сессии
		unset($_SESSION['cart']);
		unset($_SESSION['cart.qty']);
		unset($_SESSION['cart.sum']);
		unset($_SESSION['cart.currency']);
		$this->showAction(); // отображаем вид корзины
	}

	// статичный метод для пересчета корзины
	public function recalcAction(): void {
		// $curr - массив новой валюты, в которую нужно пересчитать корзину
		if (isset($_GET['productsChange']) && isset($_SESSION['cart.currency'])) {
			$qtyChange = 0;
			$priceChange = 0;
			// пересчитываем измененные товары в корзине
			foreach ($_GET['productsChange'] as $k => $v) {
				$_SESSION['cart'][$k]['qty'] += $v;
				$qtyChange += $v;
				$priceChange += $v * $_SESSION['cart'][$k]['price'];
			}
			$_SESSION['cart.qty'] += $qtyChange; // переданное количество товара прибавляем к уже имеющемуся
			$_SESSION['cart.sum'] += $priceChange; // количесство, умноженное на цену в активной валюте, прибавляем к уже имеющемуся
		}
		$this->show(); // отображаем вид корзины
	}

	// отображает вид корзины, если запрос пришел через ajax, или перенаправляет пользователя на предыдущую страницу
	protected function show(): void {
		// если запрос пришел асинхронно (ajax), загружаем вид корзины
		if ($this->isAjax()) {
			$this->showAction();
		}
		redirect(); // перезапрашиваем страницу, если данные пришли не ajax
	}

	// обрабатывает данные формы офрмления заказа
	public function checkoutAction(): void {
		if (!empty($_POST)) {
			// регистрация пользователя
			if (!User::checkAuth()) {
				$user = new User('signup', $_POST); // объект модели пользователя
			}
			// сохранение заказа
			// сохраняем id пользователя (только что зарегистрированного или авторизованного)
			$data['user_id'] = isset($user->id) ? $user->id : $_SESSION['user']['id'];
			$data['currency'] = $_SESSION['cart.currency']['code']; // валюта заказа
			$data['sum'] = $_SESSION['cart.sum']; // сумма заказа
			$data['note'] = !empty($_POST['note']) ? $_POST['note'] : ''; // примечание к заказу
			$data['pay'] = !empty($_POST['pay']) ? $_POST['pay'] : false; // checkbox хочет ли пользователь сразу оплатить заказ
			// email пользователя получаем из сессии (для авторизованного) или из данных формы регистрации
			// $user_email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : $_POST['email'];
			$data['user_email'] = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : $_POST['email'];
			new Order($data); // если в конструктор переданы данные, загружаем их в свойство $attributes и сохраняем в БД
			// вариант 1 - статичные методы
			/* $order_id = Order::saveOrder($data); // сохраняем заказ и получаем его id
			Order::saveOrderProduct($order_id); // сохраняет продукты данного заказа
			Order::mailOrder($order_id, $user_email); // отправляем письмо с информацией о заказе клиенту и администратору */
			// вариант 2 - создание объекта и использование методов загрузки и созранения базовой модели
			/* $order = new Order(); // объект заказа
			$order->load($data); // загружаем полученные данные в модель
			$order_id = $order->save(); // сохраняем заказ и получаем его id */
		}
		redirect(); // перезапрашиваем страницу
	}

	// экшен оплаты заказа
	public function paymentAction(): void {
		// если данные не пришли, прекращаем работу скрипта
		if (empty($_POST)) {
			die;
		}

		$dataSet = $_POST; // записываем в переменную массив полученных данных

		unset($dataSet['ik_sign']); // удаляем из данных строку подписи
		ksort($dataSet, SORT_STRING); // сортируем по ключам в алфавитном порядке элементы массива
		array_push($dataSet, App::$app->getProperty('ik_key')); // добавляем в конец массива 'секретный ключ'
		$signString = implode(':', $dataSet); // конкатенируем значения через символ ':'
		$sign = base64_encode(md5($signString, true)); // берем MD5 хэш в бинарном виде и по сформированной строке и кодирем в BASE64

		$order = Order::getById($dataSet['ik_pm_no']); // получаем данные заказа по его номеру
		if (!$order) die; // если заказ не получе, завершаем работу скрипта

		// валидируем полученные данные
		// ik_co_id - id кассы
		// ik_inv_st - состояние платежа
		// ik_am - сумма заказа
		// ik_sign - цифровая подпись
		if ($dataSet['ik_co_id'] != App::$app->getProperty('ik_id') || $dataSet['ik_inv_st'] != 'success' || $dataSet['ik_am'] != $order->sum || $dataSet['ik_cur'] != $order->currency || $_POST['ik_sign'] != $sign) {
			die;
		}

		$order->status = '2'; // меняем статус заказа на 'Оплачен'
		\R::store($order); // сохраняем заказ
		die; // прекращаем работу скрипта
	}

}
