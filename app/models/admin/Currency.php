<?php

namespace app\models\admin;

use app\models\AppModel;
use ishop\App; // подключаем класс базовый приложения

class Currency extends AppModel{

	// переопределяем аттрибуты родительской модели
	public $attributes = [
		'title' => '',
		'code' => '',
		'symbol_left' => '',
		'symbol_right' => '',
		'value' => '',
		'course' => '',
		'base' => '',
	];

	// переопределяем правила валидации формы родительской модели
	public $rules = [
		'required' => [
			['title'],
			['code'],
			['course'],
		],
		'numeric' => [
			['course'],
		],
	];

	public function __construct($data = [], $attrs = [], $action = 'save'){
		$data['base'] = $data['base'] ? '1' : '0'; // конвертируем значения флага базовой валюты для записи в БД
		$data['value'] = self::getValue($data['course']); // значение курса валюты для пересчета цен
		// вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
		parent::__construct($data, $attrs, $action);
		// сохраняем валюту в БД
		if($this->id){
			$_SESSION['success'] = $action == 'update' ? 'Изменения сохранены' : 'Валюта добавлена';
		}
	}

	// получает общее число валют
	public static function getCount(){
		return \R::count('currency');
	}

	// получаем список валют
	public static function getAll(){
		return \R::findAll('currency');
	}

	// получаем из БД валюту по id
	public static function getById($id){
		return \R::load('currency', $id);
	}

	// удаляет категорию
	public static function delete($id){
		\R::trash(self::getById($id)); // удаляем валюту из БД
		$_SESSION['success'] = "Валюта удалена";
		redirect();
	}

	// возвращает список курсов по кодам переданных валют
	public static function updateCourse($changeTitle = false){
		$currencies = self::getAll();// получаем список валют
		$courses = self::getCoursesByCode(self::getCodeList()); // получаем список курсов для активных валют
		foreach ($currencies as $currency){
			// если валюта является небазовой, сравниваем ее значение с текущим курсом
			if ($currency->base == '0'){
				$course = $courses[$currency->code]; // текущий курс данной валюты
				$value = self::getValue($course['Value']); // значение курса валюты для пересчета цен
				// для небазовых валют, присутствующих в списке курсов валют (code является одним из ключей массива $courses)
				// приверяем разницу между текущим курсом валюты и данным значением в БД
				if ($course['Value'] != $currency->course || $value != $currency->value){
					$sql_part = 'value = ?, course = ?';
					$arr = [$value, $course['Value'], $currency->code];
					if($changeTitle && $course['Name'] != $currency->title){
						$sql_part = 'value = ?, course = ?, title = ?';
						$arr = [$value, $course['Value'], $course['Name'], $currency->code];
					}
					if(\R::exec("UPDATE currency SET $sql_part WHERE code = ?", $arr)){
						$change = true;
					}
				}
			}
			if(!($change ?? false)) $currency->update_at = (new \DateTime($currency->update_at))->format('d-m-Y');
		}
		if($change ?? false) redirect(true);
		return $currencies;
	}

	// метод получения списка с кодами активных валют
	public static function getCodeList(){
		return \R::getCol("SELECT code FROM currency");
	}

	// метод вычисления значения курса валюты для пересчета цен
	public static function getValue($course){
		return round(1 / $course, CURRENCY_ROUND);
	}

	// возвращает список всех курсов на текущую дату (если дата не передана)
	public static function getCourses($date = null){
		// если дата передана, форматируем ее
		$date = $date ? '?date_req=' . (new \DateTime($date))->format('d.m.Y') : ''; // '2020/02/18' => 18.02.2020
		if(!$file = file_get_contents(CURRENCY_API . $date)) return false; // получаем xml файл
		if(!$xml = simplexml_load_string($file)) return false; // получаем содержимое файла в формате xml
		$courses = App::dataDecode($xml); // декодируем xml объект в массив
		$courses = self::newArray($courses['Valute'], 'CharCode', ['Value' => [',', '.', 'floatval']], 'key');
		return $courses ?: false;
	}

	/*
	$data = ['USD', 'EUR'];
	return [
		'USD' => 25.00000,
		'EUR' => 27.00000,
	]
	*/
	// возвращает список курсов по кодам переданных валют
	public static function getCoursesByCode($codeList){
		$courses = self::getCourses(); // получаем список всех курсов на текущую дату
		if (!$courses) return false;

		$courses_curr = [];
		foreach ($courses as $code => $cours){
			// если валюта есть в переданном массиве - возьмем ее
			if(in_array($code, $codeList)){
				$courses_curr[$code] = $cours;
			}
		}
		return $courses_curr;
	}

	// создает новый массив на из переданного ассоциативного массива и ключа, по значению которого необходимо сформировать новые ключи
	// сортирует массив в по ключам/значениям в зависимости от переданного типа $sort_key ('key'/'value')
	// $sort_flag - флаги сортировки
	public static function newArray($array, $key = '', $patterns = [], $sort_key = '', $sort_flag = SORT_NATURAL){
		// формируем массив
		$newArray = []; // новый массив
		foreach ($array as $k => $v){
			// если переданы паттерны для замены значений, производим замену для каждого переданного паттерна
			if($patterns){
				foreach ($patterns as $key_p => $pattern){
					// если передан массив паттернов, работаем с ним
					if(is_array($patterns)){
						// $pattern[0] - паттерн для поиска совпадения
						// $pattern[1] - паттерн для замены совпадения
						// $pattern[2] - если передана не пустая строка, вызывает указанную пользовательскую функцию
						// опеределяем вызываемую функцию на основе типа паттерна (regExp/string)
						$func = self::isRegex($pattern[0]) ? 'preg_replace' : 'str_replace';
						// call_user_func_array - Вызывает callback-функцию с массивом параметров
						$value = call_user_func_array($func, [$pattern[0], $pattern[1], $v[$key_p]]);
						// call_user_func - Вызывает callback-функцию, заданную в первом параметре
						$value = !isset($pattern[2]) ? $value : call_user_func($pattern[2], $value);
					}
					elseif(is_string($pattern)){
						$value = call_user_func($pattern, $value);
					}
					$v[$key_p] = $value;
				}
			}
			// если передан ключ для задания новых значений ключей для исходного массива массива, берем эти значения
			// иначе используем ключи из исходного массива
			$newArray[$key ? $v[$key] : $k] = $v;
		}

		// сортируем массив
		switch($sort_key){
			case 'key':
				ksort($newArray, $sort_flag); // Сортирует массив по ключам
			break;
			case 'value':
				sort($newArray, $sort_flag); // Сортирует массив
			break;
		}

		return $newArray;

		/* $key = 'CharCode'
		Исходный массив
		[0] => Array
		(
			[CharCode] => EUR
			[Value] => 68.7710
		)

		Массив с новыми ключами
		[EUR] => Array
		(
			[CharCode] => EUR
			[Value] => 68.7710
		)
		*/
		
		/* флаги сортировки
		 * SORT_REGULAR - обычное сравнение элементов;
		 * SORT_NUMERIC - числовое сравнение элементов
		 * SORT_STRING - строковое сравнение элементов
		 * SORT_LOCALE_STRING - сравнивает элементы как строки с учетом текущей локали. Используется локаль, которую можно изменять с помощью функции setlocale()
		 * SORT_NATURAL - сравнение элементов как строк, используя естественное упорядочение, как в функции natsort()
		 * SORT_FLAG_CASE - может быть объединен (побитовое ИЛИ) с SORT_STRING или SORT_NATURAL для сортировки строк без учета регистра.
		 */
		// natsort() - Эта функция реализует алгоритм сортировки, при котором порядок буквенно-цифровых строк будет привычным для человека. Такой алгоритм называется "natural ordering"
		/*
		Обычная сортировка
		Array
		(
			[3] => img1.png
			[1] => img10.png
			[0] => img12.png
			[2] => img2.png
		)

		Сортировка natural order
		Array
		(
			[3] => img1.png
			[2] => img2.png
			[1] => img10.png
			[0] => img12.png
		)
		*/
	}

	// определяет является ли переданная строка регулярным выраженияем
	// This doesn't test validity; but it looks like the question is Is there a good way of test if a string is a regex or normal string in PHP? and it does do that.
	public static  function isRegex($str){
		return preg_match("/^\/[\s\S]+\/$/", $str);
	}

}
