<?php

namespace ishop\base;

abstract class Upload {

	public static array $result; // хранение результата
	public static string $json; // хранение json

	// Зададим ограничения для картинок
	public static array $formats = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
	public static int $limitBytes = 10 * 1048576;
	public static int $limitWidth  = 2000;
	public static int $limitHeight = 1500;

	public static string $upload_dir = WWW . '/images/'; // директория для загрузки изображений
	// массив допустимых расширений
	public static array $types = array("image/gif", "image/png", "image/jpeg", "image/pjpeg", "image/x-png");

	// Массив с названиями ошибок
	public static array $errorMessages = array(
		UPLOAD_ERR_INI_SIZE   => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP.',
		UPLOAD_ERR_FORM_SIZE  => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме.',
		UPLOAD_ERR_PARTIAL    => 'Загружаемый файл был получен только частично.',
		UPLOAD_ERR_NO_FILE    => 'Файл не был загружен.',
		UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
		UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
		UPLOAD_ERR_EXTENSION  => 'PHP-расширение остановило загрузку файла.',
	);

	public static function canUpload(array $file) {
		if ($file['name'] == '') return 'Вы не выбрали файл.'; // если имя пустое, значит файл не выбран
		// если размер файла 0, значит его не пропустили настройки сервера из-за того, что он слишком большой
		if ($file['size'] == 0) return 'Файл слишком большой или пустой.';
		if ($error = self::checkMime($file['tmp_name'])) return $error; // Проверим MIME-тип
		return true;
	}

	public static function makeUpload(array $file, string $name): void {
		copy($file['tmp_name'], WWW.'img/' . $name);
	}

	public static function getHandler(): string {
		return self::$json = json_encode(self::handler()); // Конвертируем полученный массив данных в json формат
	}

	/**
	 * Загрузка картинки из формы
	 * @see https://denisyuk.by/all/polnoe-rukovodstvo-po-zagruzke-izobrazheniy-na-php/#paragraph-6
	 */
	public static function handler(): array {
		$done_files = array(); // Массив для хранения данных об результате загрузки файлов
		if (!array_key_exists(0, $_FILES)) sort($_FILES);
		// Загружаем все картинки по порядку
		foreach ($_FILES as $k => $v) $done_files[] = self::load($_FILES[$k]['tmp_name'], $_FILES[$k]['error']);
		// Запишем в переменную ассоциативный массив с результатом загрузки файла
		return self::$result = $done_files ? array('files' => $_FILES, 'info' => $done_files) : array('error' => 'Ошибка загрузки файлов');
	}

	// функция для обработки загрузки файла в единичном экземляре
	protected static function load(string $filePath, string $errorCode, string $uploadPath = 'uploads') {
		$uploadPath = WWW . "/$uploadPath";
		$name = self::validateImg($filePath, $errorCode, $uploadPath);
		// Переместим картинку с новым именем и расширением в папку /uploads
		if (@move_uploaded_file($filePath, WWW . "/$uploadPath/$name")) {
			//self::saveSession($name, $new_name, $name == 'gallery');
			//self::resize($upload_file, $upload_file, $wmax, $hmax, $ext); // изменяем размер картинки
			//exit(json_encode(array("file" => $name)));
			return self::msg($uploadPath, $name);
		}
		else return self::error('При записи изображения на диск произошла ошибка.');
	}

	// Функция, возвращающая ассоциативный массив с сообщением об успешной записи файла и сопутствующие данные
	protected static function msg(string $uploadPath, string $name): array {
		return array(
			'status' => 'Sucess!',
			'path' => __DIR__ . "/$uploadPath/$name",
			'dir' => __DIR__,
			'folder' => $uploadPath,
			'name' => $name
		);
	}

	// Функция, возвращающая ассоциативный массив с сообщением об ошибке
	protected static function error(string $error, bool $outArray = true) {
		return $outArray ? array('error' => $error) : $error;
	}

	protected static function getMime(string $filePath): string {
		$fi = finfo_open(FILEINFO_MIME_TYPE); // Создадим ресурс FileInfo
		$mime = (string) finfo_file($fi, $filePath); // Получим MIME-тип
		finfo_close($fi); // Закроем ресурс
		return $mime;
	}

	protected static function checkMime(string $filePath) {
		$mime = self::getMime($filePath); // Получим MIME-тип
		// Проверим ключевое слово image (image/jpeg, image/png и т. д.)
		if (strpos($mime, 'image') === false) return 'Можно загружать только изображения.';
		$str = implode(", ", self::$formats); // Проверим форматы image (jpeg, png и т. д.)
		if (strpos($str, explode('/', $mime)[1]) === false) return "Можно загружать только изображения в форматах: $str";
		return false;
	}

	protected static function checkSize(string $filePath, array $image) {
		// Проверим нужные параметры
		if (filesize($filePath) > self::$limitBytes) return "Размер изображения не должен превышать " . self::$limitBytes . " Мбайт.";
		//if($image[0] > self::$limitWidth) return "Ширина изображения не должна превышать " . self::$limitWidth . " точек.";
		//if($image[1] > self::$limitHeight) return "Высота изображения не должна превышать " . self::$limitHeight . " точек.";
		return false;
	}

	protected static function getExt(array $image): string {
		// Сгенерируем расширение файла на основе типа картинки и сократим .jpeg до .jpg
		return str_replace('jpeg', 'jpg', image_type_to_extension($image[2]));
	}

	protected static function validateImg(string $filePath, string $errorCode, string $uploadPath) {
		if (!is_dir($uploadPath)) mkdir($uploadPath, 0777); // Создадим директорию, если она отсутсвует

		// Проверим на ошибки
		if ($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file($filePath)) {
			$unknownMessage = 'При загрузке файла произошла неизвестная ошибка.'; // Зададим неизвестную ошибку
			// Если в массиве нет кода ошибки, скажем, что ошибка неизвестна
			return self::error(self::$errorMessages[$errorCode] ?? $unknownMessage); // Выведем название ошибки
		}
		
		if ($error = self::checkMime($filePath)) return self::error($error); // Проверим MIME-тип
		$image = getimagesize($filePath); // Результат функции запишем в переменную
		if ($error = checkSize($filePath, $image)) return self::error($error); // Проверим размер

		return md5_file($filePath).self::getExt($image); // Сгенерируем новое имя файла на основе MD5-хеша
	}

	public static function uploadImg(string $name, int $wmax, int $hmax): void {
		if ($_FILES[$name]['size'] > 1048576) {
			exit(json_encode(array("error" => "Ошибка! Максимальный вес файла - 1 Мб!")));
		}
		if ($_FILES[$name]['error']) {
			exit(json_encode(array("error" => "Ошибка! Возможно, файл слишком большой.")));
		}
		if (!in_array($_FILES[$name]['type'], self::$types)) {
			exit(json_encode(array("error" => "Допустимые расширения - .gif, .jpg, .png")));
		}

		$ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $_FILES[$name]['name'])); // расширение картинки
		$new_name = md5(time()).".$ext"; // формируем новое имя файла
		$upload_file = self::$upload_dir.$new_name; // полное имя картинки (с учетом директории загрузки)
		// записываем в сессию имена загруженных файлов
		// move_uploaded_file - перемещает загруженный файл в указанную директорию
		// @ - игнорирует ошобки, возникающие при работе функции/метода
		if (@move_uploaded_file($_FILES[$name]['tmp_name'], $upload_file)) {
			self::saveSession($name, $new_name, $name == 'gallery');
			self::resize($upload_file, $upload_file, $wmax, $hmax, $ext); // изменяем размер картинки
			exit(json_encode(array("file" => $new_name)));
		}
	}

	protected static function saveSession(string $name, string $new_name, bool $multy = false): void {
		if (!$multy) {
			$_SESSION[$name] = $new_name;
		} else {
			$_SESSION[$name][] = $new_name;
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
	public static function resize(string $target, string $dest, string $wmax, string $hmax, string $ext): void {
		list($w_orig, $h_orig) = getimagesize($target); // записываем в переменные ширину и высоту изображения
		$ratio = $w_orig / $h_orig; // ориентация расположения изображения (=1 - квадрат, <1 - альбомная, >1 - книжная)

		if (($wmax / $hmax) > $ratio) {
			$wmax = $hmax * $ratio;
		} else {
			$hmax = $wmax / $ratio;
		}

		$img = "";
		// imagecreatefromjpeg | imagecreatefromgif | imagecreatefrompng
		switch ($ext) {
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

		if ($ext == "png") {
			imagesavealpha($newImg, true); // сохранение альфа канала
			$transPng = imagecolorallocatealpha($newImg,0,0,0,127); // добавляем прозрачность
			imagefill($newImg, 0, 0, $transPng); // заливка
		}

		imagecopyresampled($newImg, $img, 0, 0, 0, 0, $wmax, $hmax, $w_orig, $h_orig); // копируем и ресайзим изображение
		switch ($ext) {
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
