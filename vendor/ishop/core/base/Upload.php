<?php

namespace ishop\base;

abstract class Upload {

	public static $result; // хранение результата
	public static $json; // хранение json

	// Зададим ограничения для картинок
	public static $formats = array('jpg', 'jpeg', 'png', 'gif');
	public static $limitBytes = 10 * 1048576;
	public static $limitWidth  = 2000;
	public static $limitHeight = 1500;

	public static $upload_dir = WWW . '/images/'; // директория для загрузки изображений
	// массив допустимых расширений
	public static $types = array("image/gif", "image/png", "image/jpeg", "image/pjpeg", "image/x-png");

	// Массив с названиями ошибок
	public static $errorMessages = array(
		UPLOAD_ERR_INI_SIZE   => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP.',
		UPLOAD_ERR_FORM_SIZE  => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме.',
		UPLOAD_ERR_PARTIAL    => 'Загружаемый файл был получен только частично.',
		UPLOAD_ERR_NO_FILE    => 'Файл не был загружен.',
		UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
		UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
		UPLOAD_ERR_EXTENSION  => 'PHP-расширение остановило загрузку файла.',
	);

	/**
	 * Загрузка картинки из формы
	 * @see https://denisyuk.by/all/polnoe-rukovodstvo-po-zagruzke-izobrazheniy-na-php/#paragraph-6
	 */
	public static function handler(){
		$done_files = array(); // Массив для хранения данных об результате загрузки файлов
		if(!array_key_exists(0, $_FILES)) sort($_FILES);
		// Загружаем все картинки по порядку
		foreach($_FILES as $k => $v) $done_files[] = self::load($_FILES[$k]['tmp_name'], $_FILES[$k]['error']);
		// Запишем в переменную ассоциативный массив с результатом загрузки файла
		self::$result = $done_files ? array('files' => $_FILES, 'info' => $done_files) : array('error' => 'Ошибка загрузки файлов');
		self::$json = json_encode(self::$result); // Конвертируем полученный массив данных в json формат
		return self::$result;
	}

	// функция для обработки загрузки файла в единичном экземляре
	protected static function load($filePath, $errorCode, $uploadPath = 'uploads'){
		$uploadPath = WWW . "/$uploadPath";
		$name = self::validateImg($filePath, $errorCode, $uploadPath);
		// Переместим картинку с новым именем и расширением в папку /uploads
		if(@move_uploaded_file($filePath, WWW . "/$uploadPath/$name")) return self::msg($uploadPath, $name);
		else return self::error('При записи изображения на диск произошла ошибка.');
	}

	// Функция, возвращающая ассоциативный массив с сообщением об успешной записи файла и сопутствующие данные
	protected static function msg($uploadPath, $name){
		return array(
			'status' => 'Sucess!',
			'path' => __DIR__ . "/$uploadPath/$name",
			'dir' => __DIR__,
			'folder' => $uploadPath,
			'name' => $name
		);
	}

	// Функция, возвращающая ассоциативный массив с сообщением об ошибке
	protected static function error($error){
		return array('error' => $error);
	}

	protected static function validateImg($filePath, $errorCode, $uploadPath){
		if(!is_dir($uploadPath)) mkdir($uploadPath, 0777); // Создадим директорию, если она отсутсвует

		// Проверим на ошибки
		if($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file($filePath)){
			$unknownMessage = 'При загрузке файла произошла неизвестная ошибка.'; // Зададим неизвестную ошибку
			// Если в массиве нет кода ошибки, скажем, что ошибка неизвестна
			return self::error(self::$errorMessages[$errorCode] ?? $unknownMessage); // Выведем название ошибки
		}
	
		$fi = finfo_open(FILEINFO_MIME_TYPE); // Создадим ресурс FileInfo
		$mime = (string) finfo_file($fi, $filePath); // Получим MIME-тип
		finfo_close($fi); // Закроем ресурс

		// Проверим ключевое слово image (image/jpeg, image/png и т. д.)
		if(strpos($mime, 'image') === false) return self::error('Можно загружать только изображения.');
		$str = implode(", ", self::$formats); // Проверим форматы image (jpeg, png и т. д.)
		if(strpos($str, explode('/', $mime)[1]) === false) return self::error("Можно загружать только изображения в форматах: $str");

		// Проверим нужные параметры
		if(filesize($filePath) > self::$limitBytes) return self::error("Размер изображения не должен превышать " . self::$limitBytes . " Мбайт.");

		$image = getimagesize($filePath); // Результат функции запишем в переменную
		//if($image[0] > self::$limitWidth) return self::error("Ширина изображения не должна превышать " . self::$limitWidth . " точек.");
		//if($image[1] > self::$limitHeight) return self::error("Высота изображения не должна превышать " . self::$limitHeight . " точек.");
		$ext = image_type_to_extension($image[2]); // Сгенерируем расширение файла на основе типа картинки
		$ext = str_replace('jpeg', 'jpg', $ext); // Сократим .jpeg до .jpg

		return md5_file($filePath).$ext; // Сгенерируем новое имя файла на основе MD5-хеша
	}

	public static function uploadImg($name, $wmax, $hmax){
		if($_FILES[$name]['size'] > 1048576){
			exit(json_encode(array("error" => "Ошибка! Максимальный вес файла - 1 Мб!")));
		}
		if($_FILES[$name]['error']){
			exit(json_encode(array("error" => "Ошибка! Возможно, файл слишком большой.")));
		}
		if(!in_array($_FILES[$name]['type'], self::$types)){
			exit(json_encode(array("error" => "Допустимые расширения - .gif, .jpg, .png")));
		}

		$ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $_FILES[$name]['name'])); // расширение картинки
		$new_name = md5(time()).".$ext"; // формируем новое имя файла
		$upload_file = self::$upload_dir.$new_name; // полное имя картинки (с учетом директории загрузки)
		// записываем в сессию имена загруженных файлов
		// move_uploaded_file - перемещает загруженный файл в указанную директорию
		// @ - игнорирует ошобки, возникающие при работе функции/метода
		if(@move_uploaded_file($_FILES[$name]['tmp_name'], $upload_file)){
			self::saveSession($name, $new_name);
			self::resize($upload_file, $upload_file, $wmax, $hmax, $ext); // изменяем размер картинки
			exit(json_encode(array("file" => $new_name)));
		}
	}

	protected static function saveSession($name, $new_name){
		if($name == 'single'){
			$_SESSION['single'] = $new_name;
		}else{
			$_SESSION['gallery'][] = $new_name;
		}
	}

	/**
	 * Статичный метод для изменения размера изображения
	 *
	 * @param string $target путь к оригинальному файлу
	 * @param string $dest путь сохранения обработанного файла
	 * @param string $wmax максимальная ширина
	 * @param string $hmax максимальная высота
	 * @param string $ext расширение файла
	 */
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
