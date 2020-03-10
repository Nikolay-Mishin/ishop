<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

use \Exception;
use \Closure;

use ishop\App;

trait T_Protect {

	protected $protectProperties = ['protectProperties', 'properties'];
	protected $properties = [];

	public function __get($property){
		return $this->exist($this, $property, function($obj, $property){
			return $obj->$property;
		});
	}

	public function __set($property, $value){
		$this->exist($this, $property, function($obj, $property, $const, $error, $class) use ($value) {
			if(!$const) $obj->$property = $value;
			else $this->getException(500, "Изменение значения свойства $class::$property");
		});
	}

	public function getProtectProperties(){
		return $this->protectProperties;
	}

	public function getReturnProperties(){
		return (object) $this->properties;
	}

	protected function setProtectProperties($protectProperties){
		$this->protectProperties = [];
		$this->addProtectProperties($protectProperties);
	}

	protected function addProtectProperties(...$protectProperties){
		$this->structuredProtectProperties($protectProperties);
	}

	private function structuredProtectProperties($protectProperties, $structuredBefore = true){
		if($structuredBefore) $this->structuredProtectProperties($this->protectProperties, false);
		foreach(toArray($protectProperties) as $property => $mod){
			$condition = !$structuredBefore ?: !array_key_exists($property, $this->protectProperties);
			$this->reverseProtectProperty($property, $mod, $condition, $structuredBefore);
		}
	}

	private function reverseProtectProperty($property, $mod, $condition = true, $structuredBefore = false){
		if($condition){
			list($key, $isConst) = [$property, gettype($property) == 'integer'];
			list($property, $mod) = [$isConst ? $mod : $property, !$isConst && $mod == 'set' ? 'set' : 'get'];
			$this->protectProperties[$property] = $mod;
			$key = !property_exists($this, $property) ? $property : $key;
			if($isConst && !$structuredBefore || !property_exists($this, $property)) arrayUnset($this->protectProperties, $key);
		}
	}

	private function exist($obj, $property, Closure $callback){
		foreach(array_keys($this->protectProperties) as $key){
			if(gettype($key) == 'integer'){
				$this->structuredProtectProperties($this->protectProperties, false);
				break;
			}
		}

		$class = getClassName($obj);
		$error = "Свойство $class::$property";

		list($_property, $inObj, $inProperty) = $this->propertyExist($obj, $property, $callback, $error);

		if(!$_property){
			$this->getException(500, $error, $inObj, $inProperty);
		}

		return $_property;
	}

	private function propertyExist($obj, $property, Closure $callback, $error, $inProtectProperty = false){
		if(gettype($obj) !== 'object') return false;

		list($inBean, $inObj, $inProperty) = [preg_match('/^_(.*)$/', $property), false, false];
		$isBean = $this->isBean($obj);

		$obj = $inBean && !$isBean ? $obj->bean : $obj;
		$property = preg_replace('/^_(.*)$/', '$1', $property);

		if(($isBean = $this->isBean($obj)) && $propertyExist = array_key_exists($property, $obj->getProperties())){
			$inProperty = true;
		}elseif($propertyExist = property_exists($obj, $property)){
			$inObj = true;
		}

		$access = $isBean ?: array_key_exists($property, $this->protectProperties);

		if($propertyExist && $access){
			$isConst = ($this->protectProperties[$property] ?? 'get') === 'get';
			$_property = $callback($obj, $property, $isConst, $error, getClassName($obj));
			$this->properties[$property] = $_property;
		}elseif(!$access && !$isBean && !$inProtectProperty){
			foreach($this->protectProperties as $protect => $mod){
				$_property = $this->propertyExist($this->$protect, $property, $callback, $error, true)[0];
				if($_property) break;
			}
		}

		return [$_property ?? null, $inObj, $inProperty];
	}

	private function isBean($obj){
		return $obj instanceof \RedBeanPHP\OODBBean;
	}


	private function getException($code, $error, $inObj = true, $inProperty = false){
		list($controller, $action) = [App::$controller, App::$action];
		$msg = $inObj || $inProperty ? "недоступно в области видимости $controller::$action" : "отсутствует в объекте";
		throw new Exception("$error $msg", 500);
	}

}
