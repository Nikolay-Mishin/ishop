<?php
// базовый класс модели фреймворка - описывает базовые свойства и методы, которые будут наследоваться всеми моделями
// необходим для работы с БД

namespace ishop\base;

use ishop\Db; // класс БД
use Valitron\Validator; // класс Валидатора

abstract class Model extends Sql {

	use \ishop\traits\T_setProperties;
	use \ishop\traits\T_ProtectProperties;

	public $attributes = []; // массив свойств модели (идентичен полям в таблицах БД - автозагрузка данных из форм в модель)
	public $errors = []; // хранение ошибок
	public $rules = []; // правила валидации данных
	public $id = null; // id последней сохраненной записи (метод save())
	protected $bean;

	public function __construct($data = [], $attrs = [], $action = 'save', $valid = []){
		Db::instance(); // создаем объект класса БД
		// если в конструктор модели переданы данные, то загружаем их в свойство $attributes модели и сохраняем в БД
		//debug(['__construct', get_called_class(), $data, $attrs, $action, $valid]);
		if($data){
			$this->setReturnProtect('bean');
			list($data, $attrs, $valid) = toArray([$data, $attrs, $valid], true);
			$this->load($data); // загружаем полученные данные в модель
			// валидируем данные
			// in_array(,,true) - при поиске будет использовано строгое сравнение (проверит соответствие типов)
			if(!$this->validate($data) || in_array(false, validateAttrs($this, $valid), true)){
				$this->getErrors(); // получаем список ошибок
				if($action == 'save') $_SESSION['form_data'] = $data;
				redirect();
			}
			$this->table = self::getTable(); // имя таблицы в БД
			// сохраняем/обновляем данные в БД и получаем id последней сохраненной записи
			// $this->save();
			// $this->$action($attrs);
			// Вызывает callback-функцию `callback`, с параметрами из массива `param_arr`
			call_user_func_array([$this, $action], $attrs);
		}
	}

	// метод автозагрузки данных из формы
	// защищает от получения данных, которых нет в форме (будут заполнены только данные, которые есть в аттрибутах модели)
	/*
	'login' => '',
	'password' => '',
	// данного поля нет в свойстве $attributes
	'login1' => '', // поле, которого не было в форме (если со стороны клиенты, например будет попытка подмены формы)
	*/
	public function load($data){
		foreach($this->attributes as $name => $value){
			// если в данных есть поле, соответствующее полю в $attributes, то записываем значение из данных в аттрибуты
			if(isset($data[$name])){
				$this->attributes[$name] = $data[$name];
			}
		}
	}

	// сохраняем данные в таблицу в БД
	public function save(){
		// если имя таблицы валидно, используем метод dispense, иначе xdispense
		// '_' в имени запрещено для RedBeanPHP => attributeValue вместо attribute_value)
		// производим 1 из операций CRUD - Create Update Delete
		// создаем бин (bean) - новую строку записи для сохранения данных в таблицу в БД
		$tbl = !preg_match('/_/', $this->table) ? \R::dispense($this->table) : \R::xdispense($this->table);
		//// в каждое поле таблицы записываем соответствуещее значение из списка аттрибутов модели
		//foreach($this->attributes as $name => $value){
		//    $tbl->$name = $value;
		//}
		//// сохраняем сформированные данные в БД и возвращаем результат сохранения (id записи либо 0)
		//return $this->id = \R::store($this->bean = $tbl);
		return $this->saveBean($tbl);
	}

	// метод обновления (перезаписи) данных в БД
	public function update($id){
		$bean = \R::load($this->table, $id); // получаем бин записи из БД (структуру объекта)
		//// для каждого аттрибута модели заполняем поля записи в БД
		//foreach($this->attributes as $name => $value){
		//    $bean->$name = $value;
		//}
		//// сохраняем сформированные данные в БД и возвращаем результат сохранения (id записи либо 0)
		//return $this->id = \R::store($this->bean = $bean);
		return $this->saveBean($bean);
	}

	// метод сохранения бина (представления таблицы) в БД
	protected function saveBean($bean){
		// для каждого аттрибута модели заполняем поля записи в БД
		foreach($this->attributes as $name => $value){
			$bean->$name = $value;
		}
		$this->bean = $bean;
		// сохраняем сформированные данные в БД и возвращаем результат сохранения (id записи либо 0)
		//return $this->id = \R::store($this->bean = $bean);
	}

	// метод валидации данных
	public function validate($data){
		Validator::langDir(WWW . '/validator/lang'); // указываем Валидатору директорию для языков
		Validator::lang('ru'); // устанавливаем язык Валидатора
		$v = new Validator($data); // объект Валидатора (передаем данные в конструктор)
		$v->rules($this->rules); // передаем в Валидатор набор правил валидации
		// если валидация прошла успешно, возвращаем true
		if($v->validate()){
			return true;
		}
		$this->errors = $v->errors(); // записываем ошибки валидации в свойство модели
		return false;
	}

	// получает ошибки валидации
	public function getErrors(){
		// формируем список ошибок
		$errors = '<ul>';
		foreach($this->errors as $error){
			foreach($error as $item){
				$errors .= "<li>$item</li>";
			}
		}
		$errors .= '</ul>';
		$_SESSION['error'] = $errors; // записываем список ошибок в сессию
	}

	protected function setAttributes($attributes){
		$this->attributes = [];
		$this->addAttributes($attributes);
	}

	protected function addAttributes($attributes){
		$attributes = toArray($attributes);
		foreach($attributes as $name => $value){
			$this->attributes[$name] = $value;
		}
	}

	protected function delAttributes($attributes = []){
		$attributes = !empty($attributes) ? toArray($attributes) : array_keys($this->attributes);
		arrayUnset($this->attributes, $attributes);
	}

	protected function setRequired($required){
		$this->rules['required'] = [];
		$this->addRequired($required);
	}

	protected function addRequired($required){
		$required = toArray($required);
		foreach($required as $require){
			$this->rules['required'][] = toArray($require);
		}
	}

}
