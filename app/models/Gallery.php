<?php

namespace app\models;

//use app\models\admin\Product;
use app\models\AppModel;

class Gallery extends AppModel {

	// метод удаления базовой картинки из БД и с сервера
	public static function deleteSingle($id, $src, $data){
		//$data = Product::getById($id); // загружаем данные товара из БД
		$data->img = 'no_image.jpg'; // записываем путь к заглушке
		return \R::store($data) ? deleteImg($src) : 0; // удаляем картинку из БД
	}

	// метод удаления картинок галлереи из БД и с сервера
	public static function deleteGallery($id, $src, $table = 'gallery', $idCol = 'product_id', $srcCol = 'img'){
		// удаляем картинку из БД
		return \R::exec("DELETE FROM $table WHERE $idCol = ? AND $srcCol = ?", [$id, $src]) ? deleteImg($src) : 0;
	}

	// метод удаления картинок галлереи из БД и с сервера
	public static function deleteImg($src){
		@unlink(WWW . "/images/$src"); // удаляем картинку с сервера (@ - заглушка ошибок с правами и тд)
		return 1; // в качестве ответа отправляем '1'
	}

	//public static function uploadImg($name, $wmax, $hmax){
	//    $uploaddir = WWW . '/images/'; // директория для загрузки изображений
	//    $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $_FILES[$name]['name'])); // расширение картинки
	//    $types = array("image/gif", "image/png", "image/jpeg", "image/pjpeg", "image/x-png"); // массив допустимых расширений
	//    if($_FILES[$name]['size'] > 1048576){
	//        $res = array("error" => "Ошибка! Максимальный вес файла - 1 Мб!");
	//        exit(json_encode($res));
	//    }
	//    if($_FILES[$name]['error']){
	//        $res = array("error" => "Ошибка! Возможно, файл слишком большой.");
	//        exit(json_encode($res));
	//    }
	//    if(!in_array($_FILES[$name]['type'], $types)){
	//        $res = array("error" => "Допустимые расширения - .gif, .jpg, .png");
	//        exit(json_encode($res));
	//    }
	//    $new_name = md5(time()).".$ext"; // формируем новое имя файла
	//    $uploadfile = $uploaddir.$new_name; // полное имя картинки (с учетом директории загрузки)
	//    // записываем в сессию имена загруженных файлов
	//    // move_uploaded_file - перемещает загруженный файл в указанную директорию
	//    // @ - игнорирует ошобки, возникающие при работе функции/метода
	//    if(@move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile)){
	//        if($name == 'single'){
	//            $_SESSION['single'] = $new_name;
	//        }else{
	//            $_SESSION['gallery'][] = $new_name;
	//        }
	//        self::resize($uploadfile, $uploadfile, $wmax, $hmax, $ext); // изменяем размер картинки
	//        $res = array("file" => $new_name); // массив с результом работы метода
	//        exit(json_encode($res));
	//    }
	//}

	///**
	// * Статичный метод для изменения размера изображения
	// *
	// * @param string $target путь к оригинальному файлу
	// * @param string $dest путь сохранения обработанного файла
	// * @param string $wmax максимальная ширина
	// * @param string $hmax максимальная высота
	// * @param string $ext расширение файла
	// */
	//public static function resize($target, $dest, $wmax, $hmax, $ext){
	//    list($w_orig, $h_orig) = getimagesize($target); // записываем в переменные ширину и высоту изображения
	//    $ratio = $w_orig / $h_orig; // ориентация расположения изображения (=1 - квадрат, <1 - альбомная, >1 - книжная)

	//    if(($wmax / $hmax) > $ratio){
	//        $wmax = $hmax * $ratio;
	//    }else{
	//        $hmax = $wmax / $ratio;
	//    }

	//    $img = "";
	//    // imagecreatefromjpeg | imagecreatefromgif | imagecreatefrompng
	//    switch($ext){
	//        case("gif"):
	//            $img = imagecreatefromgif($target);
	//            break;
	//        case("png"):
	//            $img = imagecreatefrompng($target);
	//            break;
	//        default:
	//            $img = imagecreatefromjpeg($target);
	//    }
	//    $newImg = imagecreatetruecolor($wmax, $hmax); // создаем оболочку для новой картинки

	//    if($ext == "png"){
	//        imagesavealpha($newImg, true); // сохранение альфа канала
	//        $transPng = imagecolorallocatealpha($newImg,0,0,0,127); // добавляем прозрачность
	//        imagefill($newImg, 0, 0, $transPng); // заливка
	//    }

	//    imagecopyresampled($newImg, $img, 0, 0, 0, 0, $wmax, $hmax, $w_orig, $h_orig); // копируем и ресайзим изображение
	//    switch($ext){
	//        case("gif"):
	//            imagegif($newImg, $dest);
	//            break;
	//        case("png"):
	//            imagepng($newImg, $dest);
	//            break;
	//        default:
	//            imagejpeg($newImg, $dest);
	//    }
	//    imagedestroy($newImg);
	//}

}
