<?php

namespace app\models\admin;

use app\models\AppModel;

class Product extends AppModel {

	// переопределяем аттрибуты родительской модели
	public $attributes = [
		'title' => '',
		'category_id' => '',
		'keywords' => '',
		'description' => '',
		'price' => '',
		'old_price' => '',
		'content' => '',
		'status' => '',
		'hit' => '',
		'alias' => '',
	];

	// переопределяем правила валидации формы родительской модели
	public $rules = [
		'required' => [
			['title'],
			['category_id'],
			['price'],
		],
		'integer' => [
			['category_id'],
		],
	];

	// метод изменения товара
	public function editAttrs($id, $data, $table, $condition, $attr_id){
		// получаем аттрибуты товара
		$dataAttrs = \R::getCol("SELECT $attr_id FROM $table WHERE $condition = ?", [$id]);
		// debug(["SELECT $attr_id FROM $table WHERE $condition = ?", [$id]]);

		// если менеджер убрал связанные товары - удаляем их
		if(empty($data) && !empty($dataAttrs)){
			$this->deleteAttrs($id, $table, $condition); // удаляем связанные товары продукта
			return;
		}

		// если добавляются связанные товары
		if(empty($dataAttrs) && !empty($data)){
			debug([$dataAttrs, $data]);
			$this->addAttrs($id, $data, $table, $condition, $attr_id); // добавляем товар в БД
			return;
		}

		// если изменились связанные товары - удалим и запишем новые
		if(!empty($data)){
			$result = array_diff($dataAttrs, $data); // возвращает разницу между массивами
			// debug([$result, $dataAttrs, $data]);
			// если есть разница между массивами, удаляем имеющиеся аттрибуты товара и добавляем новые
			if(!empty($result) || count($dataAttrs) != count($data)){
				$this->deleteAttrs($id, $table, $condition); // удаляем аттрибуты товара
				$this->addAttrs($id, $data, $table, $condition, $attr_id); // добавляем товар в БД
			}
		}
	}

	// метод удаления товара
	private function deleteAttrs($id, $table, $condition){
		\R::exec("DELETE FROM $table WHERE $condition = ?", [$id]); // выполняем sql-запрос
	}

	// метод добавления товара
	private function addAttrs($id, $data, $table, $condition, $attr_id){
		// если $attr_id - массив, преобразуем его в строку по разделителю
		$attr_id = is_array($attr_id) ? implode(', ', $attr_id) : $attr_id; // [attr_id, title] => 'attr_id, title'

		$sql_part = ''; // часть sql-запроса
		// формируем sql-запрос
		foreach($data as $v){
			// если строка со значением является числом, приводим ее к числу
			// иначе оборачиваем строку в '' для корректности sql-запроса
			$v = ($v === (string)(int)$v) ? (int)$v : "'$v'";
			$sql_part .= "($id, $v),";
		}
		$sql_part = rtrim($sql_part, ','); // удаляем конечную ','
		// debug("INSERT INTO $table ($condition, $attr_id) VALUES $sql_part");
		\R::exec("INSERT INTO $table ($condition, $attr_id) VALUES $sql_part"); // выполняем sql-запрос
	}

	// метод получения основной картинки
	public function getImg(){
		// если загружена базовая картинка, то в аттрибут 'img' записываем ее и удаляем из сессии
		if(!empty($_SESSION['single'])){
			$this->attributes['img'] = $_SESSION['single'];
			unset($_SESSION['single']);
		}
	}

	// метод сохранения галлереи
	public function saveGallery($id){
		// если загружены картинки галлереи, то в аттрибут 'img' записываем их и удаляем из сессии
		if(!empty($_SESSION['multi'])){
			/*
			$sql_part = ''; // часть sql-запроса
			// формируем sql-запрос
			foreach($_SESSION['multi'] as $v){
				$sql_part .= "('$v', $id),";
			}
			$sql_part = rtrim($sql_part, ','); // удаляем конечную ','
			\R::exec("INSERT INTO gallery (img, product_id) VALUES $sql_part"); // выполняем sql-запрос
			*/
			$this->addAttrs($id, $_SESSION['multi'], 'gallery', 'product_id', 'img');
			unset($_SESSION['multi']);
		}
	}

	public function uploadImg($name, $wmax, $hmax){
		$uploaddir = WWW . '/images/'; // директория для загрузки изображений
		$ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $_FILES[$name]['name'])); // расширение картинки
		$types = array("image/gif", "image/png", "image/jpeg", "image/pjpeg", "image/x-png"); // массив допустимых расширений
		if($_FILES[$name]['size'] > 1048576){
			$res = array("error" => "Ошибка! Максимальный вес файла - 1 Мб!");
			exit(json_encode($res));
		}
		if($_FILES[$name]['error']){
			$res = array("error" => "Ошибка! Возможно, файл слишком большой.");
			exit(json_encode($res));
		}
		if(!in_array($_FILES[$name]['type'], $types)){
			$res = array("error" => "Допустимые расширения - .gif, .jpg, .png");
			exit(json_encode($res));
		}
		$new_name = md5(time()).".$ext"; // формируем новое имя файла
		$uploadfile = $uploaddir.$new_name; // полное имя картинки (с учетом директории загрузки)
		// записываем в сессию имена загруженных файлов
		// move_uploaded_file - перемещает загруженный файл в указанную директорию
		// @ - игнорирует ошобки, возникающие при работе функции/метода
		if(@move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile)){
			if($name == 'single'){
				$_SESSION['single'] = $new_name;
			}else{
				$_SESSION['multi'][] = $new_name;
			}
			self::resize($uploadfile, $uploadfile, $wmax, $hmax, $ext); // изменяем размер картинки
			$res = array("file" => $new_name); // массив с результом работы метода
			exit(json_encode($res));
		}
	}

	/**
	 * @param string $target путь к оригинальному файлу
	 * @param string $dest путь сохранения обработанного файла
	 * @param string $wmax максимальная ширина
	 * @param string $hmax максимальная высота
	 * @param string $ext расширение файла
	 */
	// статичный метод для изменения размера изображения
	public static function resize($target, $dest, $wmax, $hmax, $ext){
		list($w_orig, $h_orig) = getimagesize($target); // записываем в переменные ширину и высоту изображения
		$ratio = $w_orig / $h_orig; // ориентация расположения изображения (=1 - квадрат, <1 - альбомная, >1 - книжная)

		if(($wmax / $hmax) > $ratio){
			$wmax = $hmax * $ratio;
		}else{
			$hmax = $wmax / $ratio;
		}

		$img = "";
		// imagecreatefromjpeg | imagecreatefromgif | imagecreatefrompng
		switch($ext){
			case("gif"):
				$img = imagecreatefromgif($target);
				break;
			case("png"):
				$img = imagecreatefrompng($target);
				break;
			default:
				$img = imagecreatefromjpeg($target);
		}
		$newImg = imagecreatetruecolor($wmax, $hmax); // создаем оболочку для новой картинки

		if($ext == "png"){
			imagesavealpha($newImg, true); // сохранение альфа канала
			$transPng = imagecolorallocatealpha($newImg,0,0,0,127); // добавляем прозрачность
			imagefill($newImg, 0, 0, $transPng); // заливка
		}

		imagecopyresampled($newImg, $img, 0, 0, 0, 0, $wmax, $hmax, $w_orig, $h_orig); // копируем и ресайзим изображение
		switch($ext){
			case("gif"):
				imagegif($newImg, $dest);
				break;
			case("png"):
				imagepng($newImg, $dest);
				break;
			default:
				imagejpeg($newImg, $dest);
		}
		imagedestroy($newImg);
	}

}
