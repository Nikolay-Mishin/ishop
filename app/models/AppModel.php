<?php
// базовый класс Моделей приложения, который наследуют остальные модели

namespace app\models;

use ishop\base\Model;

class AppModel extends Model {

	// обновляет алиас
	public static function updateAlias(string $table, string $title, int $id, string $col = 'alias'): void {
		$alias = self::createAlias($table, $col, $title, $id); // создаем алиас на основе ее названия и id
		// static::class = get_called_class — Имя класса, полученное с помощью позднего статического связывания
		$model = (static::class)::getById($id); // загружаем из БД бин (bean - структура/свойства объекта)
		$model->alias = $alias; // записываем алиас для данной модели
		\R::store($model); // сохраняем алиас в БД
	}

	// статичный метод для создания алиаса - уникальное траслитерированное название строки (продукта/категории и тд)
	// категория электронные => elektronnye
	public static function createAlias(string $table, string $field, string $str, int $id): string {
		// $table - таблица в БД
		// $field - поле в данной таблице
		$str = self::str2url($str); // формируем алиас - преобразуем полученную строку в url (транслитерированная строка)
		$res = \R::findOne($table, "$field = ?", [$str]); // находим совпадение сформированного алиаса с уже имеющимися в таблице БД
		// если такой алиас уже есть в БД, добавляем к нему id
		if ($res) {
			$str = "{$str}-{$id}"; // добавляем id к строке
			$res = \R::count($table, "$field = ?", [$str]); // ищем совпадение данной строки с уже имеющимися алиасами
			// если совпадение найдено, рекурсивно вызываем метод создания алиаса
			if ($res) {
				$str = self::createAlias($table, $field, $str, $id);
			}
		}
		return $str; // возвращаем строку с сформированным алиасом
	}

	// статичный метод для преобразования строки в url
	public static function str2url(string $str): string {
		// переводим в транслит
		$str = self::rus2translit($str);
		// в нижний регистр
		$str = strtolower($str);
		// заменям все ненужное нам на "-"
		$str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
		// удаляем начальные и конечные '-'
		$str = trim($str, "-");
		return $str;
	}

	// статичный метод для перевода строки в символьный ряд на основе массива соответствий (ключ => значение)
	public static function rus2translit(string $string): string {
		// массив (ключ => значение) для перевода строки в заданное символьное соответствие
		$converter = [
			'а' => 'a',   'б' => 'b',   'в' => 'v',

			'г' => 'g',   'д' => 'd',   'е' => 'e',

			'ё' => 'e',   'ж' => 'zh',  'з' => 'z',

			'и' => 'i',   'й' => 'y',   'к' => 'k',

			'л' => 'l',   'м' => 'm',   'н' => 'n',

			'о' => 'o',   'п' => 'p',   'р' => 'r',

			'с' => 's',   'т' => 't',   'у' => 'u',

			'ф' => 'f',   'х' => 'h',   'ц' => 'c',

			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',

			'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',

			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',


			'А' => 'A',   'Б' => 'B',   'В' => 'V',

			'Г' => 'G',   'Д' => 'D',   'Е' => 'E',

			'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',

			'И' => 'I',   'Й' => 'Y',   'К' => 'K',

			'Л' => 'L',   'М' => 'M',   'Н' => 'N',

			'О' => 'O',   'П' => 'P',   'Р' => 'R',

			'С' => 'S',   'Т' => 'T',   'У' => 'U',

			'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',

			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',

			'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',

			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		];

		// переводит строку в символьный ряд на основе переданного массива соответствий (ключ => значение)
		return strtr($string, $converter);
	}
}
