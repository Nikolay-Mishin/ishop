<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

use \Exception;
use \Closure;

use ishop\App;

trait T_ProtectProperties {

	protected $protectProperties = ['protectProperties', 'returnProperties'];
	protected $returnProperties = [];

	public function __get($property){
		return $this->propertyExist($this, $property, function($obj, $property){
			return $obj->$property;
		});
	}

	public function __set($property, $value){
		$this->propertyExist($this, $property, function($obj, $property, $const) use ($value) {
			if(!$const) return $obj->$property = $value;
		});
	}

	public function getProtectProperties(){
		return $this->protectProperties;
	}

	public function getReturnProperties(){
		return (object) $this->returnProperties;
	}

	protected function setProtectProperties($protectProperties){
		$this->protectProperties = [];
		$this->addProtectProperties($protectProperties);
	}

	protected function addProtectProperties($protectProperties){
		$this->structuredProtectProperties($protectProperties);
	}

	private function structuredProtectProperties($protectProperties, $structuredBefore = true){
		if($structuredBefore) $this->structuredProtectProperties($this->protectProperties, false);
		foreach(toArray($protectProperties) as $property => $mod){
			$condition = !$structuredBefore ?: !array_key_exists($property, $this->protectProperties);
			$this->reverseProtectProperty($property, $mod, $condition, $structuredBefore);
		}
	}

	private function reverseProtectProperty($property, $mod, $condition, $structuredBefore){
		if($condition){
			list($key, $isConst) = [$property, gettype($property) == 'integer'];
			list($property, $mod) = [$isConst ? $mod : $property, !$isConst && $mod == 'set' ? 'set' : 'get'];
			$this->protectProperties[$property] = $mod;
			if($isConst && !$structuredBefore) arrayUnset($this->protectProperties, $key);
		}
	}

	private function propertyExist($obj, $property, Closure $callback, $protected = true){
		if(gettype($obj) !== 'object') return false;
		list($propertyExist, $inObj, $inProperty) = [property_exists($obj, $property), false, false];
		if($propertyExist) $inObj = true;
		if($isBean = $obj instanceof \RedBeanPHP\OODBBean){
			$propertyExist = array_key_exists($property = preg_replace('/^_(.*)$/', '$1', $property), $obj->getProperties());
			if($propertyExist) $inProperty = true;
		}
		$condition = $protected !== true ?: array_key_exists($property, $this->protectProperties);
		//debug([getClassName($obj), $property, property_exists($obj, $property), [$propertyExist, $condition], $isBean]);

		if($propertyExist && $condition){
			$_property = $callback($obj, $property, $this->protectProperties[$property] ?? null == 'get');
			$this->returnProperties[$property] = $_property;
			if(!$isBean) $inObj = true;
		}elseif(!$condition){
			foreach($this->protectProperties as $protect => $mod){
				//debug([$property, $protect, gettype($this->$protect)]);
				$_property = $this->propertyExist($this->$protect, $property, $callback, false);
				if($_property){
					$inProperty = true;
					break;
				}
			}
			if(!$_property){
				list($obj, $controller, $action) = [getClassName($obj), App::$controller, App::$action];
				$this->getException(500, $inObj, $inProperty, $obj, $property, $controller, $action);
			}
		}
		//debug([$inObj, $inProperty]);
		return $_property ?? null;
	}

	private function getException($code, $inObj, $inProperty, $obj, $property, $controller, $action){
		$msg = $inObj || $inProperty ? "недоступно в области видимости $controller::$action" : "отсутствует в объекте";
		throw new Exception("Свойство $obj::$property $msg", 500);
	}

}
