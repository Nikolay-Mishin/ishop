<?php

namespace app\models\admin;

use app\models\AppModel;

class Currency extends AppModel {

	// переопределяем аттрибуты родительской модели
	public array $attributes = [
		'title' => '',
		'code' => '',
		'symbol_left' => '',
		'symbol_right' => '',
		'value' => '',
		'course' => '',
		'base' => '',
	];

	// переопределяем правила валидации формы родительской модели
	public array $rules = [
		'required' => [
			['title'],
			['code'],
			['course'],
		],
		'numeric' => [
			['course'],
		],
	];

	public function __construct(array $data = [], array $attrs = [], string $action = 'save') {
		if(!$data) return false;
		$data['base'] = $data['base'] ? '1' : '0'; // конвертируем значения флага базовой валюты для записи в БД
		$data['value'] = self::getValue($data['course']); // значение курса валюты для пересчета цен
		// вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
		parent::__construct($data, $attrs, $action);
		// сохраняем валюту в БД
		if ($this->id) {
			$_SESSION['success'] = $action == 'update' ? 'Изменения сохранены' : 'Валюта добавлена';
		}
	}

	// получает общее число валют
	//public static function getCount(){
	//    return \R::count('currency');
	//}

	// получаем список валют
	//public static function getAll(){
	//    return \R::findAll('currency');
	//}

	// получаем из БД валюту по id
	//public static function getById($id){
	//    return \R::load('currency', $id);
	//}

	// удаляет категорию
	public static function delete(int $id): void  {
		\R::trash(self::getById($id)); // удаляем валюту из БД
		$_SESSION['success'] = "Валюта удалена";
	}

	// возвращает список курсов по кодам переданных валют
	public static function updateCourse(bool $changeTitle = false): array {
		$currencies = self::getAll();// получаем список валют
		$courses = self::getCoursesByCode(self::getCodeList()); // получаем список курсов для активных валют
		foreach ($currencies as $currency) {
			// если валюта является небазовой, сравниваем ее значение с текущим курсом
			if ($currency->base == '0') {
				$course = $courses[$currency->code]; // текущий курс данной валюты
				$value = self::getValue($course['Value']); // значение курса валюты для пересчета цен
				// для небазовых валют, присутствующих в списке курсов валют (code является одним из ключей массива $courses)
				// приверяем разницу между текущим курсом валюты и данным значением в БД
				if ($course['Value'] != $currency->course || $value != $currency->value) {
					$sql_part = 'value = ?, course = ?';
					$arr = [$value, $course['Value'], $currency->code];
					if ($changeTitle && $course['Name'] != $currency->title) {
						$sql_part = 'value = ?, course = ?, title = ?';
						$arr = [$value, $course['Value'], $course['Name'], $currency->code];
					}
					if (\R::exec("UPDATE currency SET $sql_part, update_at = NOW() WHERE code = ?", $arr)) {
						$change = true;
					}
				}
			}
			if (!($change ?? false)) $currency->update_at = (new \DateTime($currency->update_at))->format('d-m-Y');
		}
		if ($change ?? false) redirect(true);
		return $currencies;
	}

	// метод получения списка с кодами активных валют
	public static function getCodeList(): array {
		return \R::getCol("SELECT code FROM currency");
	}

	// метод вычисления значения курса валюты для пересчета цен
	public static function getValue(float $course): float {
		return round(1 / $course, CURRENCY_ROUND);
	}

	// возвращает список всех курсов на текущую дату (если дата не передана)
	public static function getCourses(?string $date = null): ?array {
		// если дата передана, форматируем ее
		$date = $date ? '?date_req=' . (new \DateTime($date))->format('d.m.Y') : ''; // '2020/02/18' => 18.02.2020
		if (!$file = file_get_contents(CURRENCY_API . $date)) return false; // получаем xml файл
		if (!$xml = simplexml_load_string($file)) return false; // получаем содержимое файла в формате xml
		$courses = dataDecode($xml); // декодируем xml объект в массив
		$courses = newArray($courses['Valute'], 'CharCode', ['Value' => [',', '.', 'floatval']]);
		return $courses ?: null;
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

}
