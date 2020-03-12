<?php
// базовый класс вида фреймворка - описывает базовые свойства и методы, которые будут наследоваться всеми видами
// работает с видами - получение данных из контроллера и отображение контента страницы на основе шаблона и полученных данных

namespace ishop\base;

use \Exception;

class View {

	use \ishop\traits\T_SetProperties;

	public $route;
	public $controller;
	public $model;
	public $view;
	public $prefix;
	public $layout;
	public $data = [];
	public $meta = []; // массив с мета-тегами, заданными через метод setMeta() в базовом контроллере
	public $canonical;
	public $style = [];
	public $script = [];

	public function __construct($route, $layout = '', $view = '', $meta = '', $controller = null){
		// $layout - шаблон для отображения (обертка над видом - статичные части сайта - меню, сайдбар, футер и тд)
		// $view - вид для отображения

		//$this->route = $route;
		//$this->controller = $route['controller'];
		//$this->view = $view;
		//$this->model = $route['controller'];
		//$this->prefix = $route['prefix'];
		//$this->meta = $meta;
		//// если жёстко передано значение false (подключение шаблона выключено - например, когда контент передан ajax-запросом)
		//if($layout === false){
		//    $this->layout = false;
		//}else{
		//    // если передан какой-то шаблон, то берем его, иначе значение константы LAYOUT
		//    $this->layout = $layout ?: LAYOUT;
		//}

		//$this->setProperties(objectUnset(clone $controller, 'data'), function($controller, $k){
		$this->setProperties(objectUnset(clone $controller, 'data'), function($k) use ($controller) {
			// если жёстко передано значение false (подключение шаблона выключено - например, когда контент передан ajax-запросом)
			if($k === 'layout'){
				if($controller->$k === false){
					$this->$k = false;
				}else{
					// если передан какой-то шаблон, то берем его, иначе значение константы LAYOUT
					$this->$k = $controller->$k ?: LAYOUT;
				}
			}
		});
	}

	// получает опции
	//protected function setOptions($controller, $callback = null){
	//    $callback = $callback ?? function(){};
	//    // если в свойствах класс существует ключ из переданных настроек, то заполняем данное свойство переданным значением
	//    foreach($controller as $k => $v){
	//        // проверяем существет ли такое свойство у класса
	//        if(property_exists($this, $k)){
	//            $this->$k = $v;
	//            $callback($controller, $k);
	//        }
	//    }
	//}

	// рендерит (формирует) страницу для пользователя на основе полученных данных
	public function render($data){
		// если $data - массив, то извлечем данные из массива и сформируем из них соответствующие переменные
		if(is_array($data)) extract($data);
		// prefix - имя префикса (админки)
		// controller - имя папки, в которой лежат соответствующие вызванному контроллеру виды
		// view - имя вида, который должен быть отображен
		$this->prefix = str_replace('\\', '/', $this->prefix);
		$viewFile = APP . "/views/{$this->prefix}{$this->controller}/{$this->view}.php";
		// если такой файл существует - подключаем его, иначе выбрасываем исключение - такой вид не найден
		if(is_file($viewFile)){
			ob_start(); // включаем буферизацию, чтобы вид не выводился
			require_once $viewFile; // подключаем файл вида
			$content = ob_get_clean(); // возвращаем все данные из буфера в переменную и одновременно очистим буфер
		}else{
			throw new Exception("Не найден вид {$viewFile}", 500);
		}
		// если свойство layout не равно false (подключение шаблона включено)
		if(false !== $this->layout){
			$layoutFile = APP . "/views/layouts/{$this->layout}.php"; // путь к файлу шаблона
			// если такой файл существует - подключаем его, иначе выбрасываем исключение - такой шаблон не найден
			if(is_file($layoutFile)){
				require_once $layoutFile;
			}else{
				throw new Exception("Не найден шаблон {$this->layout}", 500);
			}
		}
	}

	// возвращает готовую разметку (или массив) с мета-тегами (title, description, keywords)
	public function getMeta(){
		// разметку для вывода в шаблон запишем в переменную и вернем ее
		$output = '<title>' . $this->meta['title'] . '</title>' . PHP_EOL; // PHP_EOL - перенос строк
		$output .= '<meta name="description" content="' . $this->meta['desc'] . '">' . PHP_EOL;
		$output .= '<meta name="keywords" content="' . $this->meta['keywords'] . '">' . PHP_EOL;
		return $output;
	}

	// возвращает готовую разметку с канонической ссылкой
	public function getCanonical(){
		// разметку для вывода в шаблон запишем в переменную и вернем ее
		// PHP_EOL - перенос строк
		return $this->canonical ? '<link rel="canonical" href="' . $this->canonical . '">' : '';
	}

	// возвращает готовую разметку (или массив) со стилями
	public function getStyles(){
		return $this->getFilesList($this->style, 'style');
	}

	// возвращает готовую разметку (или массив) со стилями
	public function getScripts(){
		return $this->getFilesList($this->script, 'script');
	}

	protected function getFilesList($files, $type_file){
		$files_list = '';
		foreach($files as $type => $file_list){
			$files_list .= "<!-- $type -->" . PHP_EOL;
			$file_list = toArray($file_list);
			foreach($file_list as $file){
				if($type_file == 'style'){
					$file = "<link " . 'href="' . $file . '.css" rel="stylesheet" type="text/css" media="all" />';
				}
				else{
					if($type === 'init' && preg_match('/^(@\s+)(.+)$/', $file, $match)){
						$file = (require_once $match[2] ?? $file);
					}else{
						$file = "<$type_file " . 'src="' . $file . '.js">' . "</$type_file>";
					}
				}
				$files_list .= $file . PHP_EOL;
			}
		}
		return $files_list;
	}

}
