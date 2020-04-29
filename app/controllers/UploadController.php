<?php

namespace app\controllers;

use ishop\base\Upload;

class UploadController extends AppController {

	public function fileAction(){
		// если была произведена отправка формы
		if(isset($_FILES['file']) && isset($_FILES['file-2'])){
			// проверяем, можно ли загружать изображение
			$check = Upload::canUpload($_FILES['file']);
			$check_2 = Upload::canUpload($_FILES['file-2']);

			// запись в файл
			date_default_timezone_set('Europe/Moscow');
			$today = date('Y-m-d H:i:s');
			$dir = WWW.'img';
			$filename = $dir.'/file-1.txt';
			$surname = $_POST['surname'];
			$name = $_POST['name'];
			$patronymic =  $_POST['patronymic'];
			$date_of_birth =  $_POST['date_of_birth'];
			$rang = $_POST['rang'];

			if(!empty($name) && !empty($surname) && !empty($rang)){
				if(!is_dir($dir)) mkdir($dir, 0777); // Создадим директорию, если она отсутсвует
				if(!is_file($filename)){
					$file = fopen($filename, 'a');
					//fwrite($file, "{\n}");
					fclose($file);
				}
				// проверка, что файл существует и доступен для записи.
				if(is_writable($filename)){
					$file = fopen($filename, 'a');
					//$file = fopen($filename, 'r+');
					if(!$file) die("Не могу открыть файл ($filename)");
					//fseek($file, -2, SEEK_END); // поместим указатель в конец со смещением на 1 символ назад
					// Записываем в открытый файл данные
					$msg = "";
					if(file_get_contents($filename) != '') $msg .= ",\n";
					$msg .= "array('Фамилия' => $surname, 'Имя' => $name, 'Отчество' => $patronymic, 'Дата рождения' => $date_of_birth, 'Спортивный разряд' => $rang, 'Дата/время регистрации' => $today)";
					//$msg .= "\n}";
					if(fwrite($file, $msg) === FALSE) die("Не могу произвести запись в файл ($filename)");
					echo 'Ура! Данные отправлены.' . '<br>';
					fclose($file);
			
					$str = file_get_contents($filename);
					$split = preg_split("/\n/", $str);
					/* unset($split[0], $split[count ($split)]);
					sort($split); */
					$json = json_encode(array('lines' => $split));
					/* echo '<pre>';
					print_r($split);
					echo '</pre>';
					echo $json . '<br>'; */
				}
				else echo "Файл $filename недоступен для записи";
			}
			else echo 'Заполните форму!' . '<br>';

			// загружаем изображение на сервер
			if($check === true && $check_2 === true){
				$today = date('d.m.Y_H-i-s', strtotime($today));
				$filename = $today . '_' . $_FILES['file']['name'];
				echo $filename . '<br>';
				Upload::makeUpload($_FILES['file'], $filename);
				$filename = $today.'_'.md5_file($_FILES['file']['tmp_name']).'.'.explode('/', $_FILES['file-2']['type'])[1];
				echo $filename . '<br>';
				Upload::makeUpload($_FILES['file-2'], $filename);
				echo '<strong>Файл успешно загружен!</strong>';
				header('Refresh: 1;'.$_SERVER['HTTP_REFERER']);
			}
			else echo "<strong>$check<br>$check_2</strong>"; // выводим сообщение об ошибке
		}
		$this->setMeta('Загрузка одного файла'); // устанавливаем мета-данные
		$this->setStyle('css/upload-file');
		$this->setScript('js/_upload', 'js/upload-file');
	}

	public function multyAction(){
		if($this->isAjax()) exit(Upload::getHandler());
		$this->setMeta('Загрузка нескольких файлов'); // устанавливаем мета-данные
		$this->setStyle('css/upload-multy');
		$this->setScript('js/_upload', 'js/upload-multy');
	}

}
