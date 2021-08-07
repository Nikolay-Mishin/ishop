<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

use \Exception;
use \Closure;

trait T_Protect {

	protected array $protectProperties = [];
	protected array $protectMethods = [];

	public function __get($property) {
		return $this->exist($property, function($obj, $property) {
			return $obj->$property;
		});
	}

	public function __set($property, $value) {
		$this->exist($property, function($obj, $property, $const, $class) use($value) {
			!$const ? $obj->$property = $value : $this->getException(500, "Изменение значения свойства $class::$property");
		});
	}

	public function __call($method, array $args) {
		return $this->exist($method, function($obj, $method, $const, $class, $isBean, $exist) use($args) {
			//debug(['class' => getClassName($obj), 'method' => $method, 'isBean' => $isBean, 'exist' => $exist]);
			return $isBean && $exist ? call_user_func_array([$obj, $method], $args) : callPrivateMethod($obj, $method, $args);
		}, 'protectMethods');
	}

	public function getProtectProperties() {
		return $this->protectProperties;
	}

	public function getProtectMethods() {
		return $this->protectMethods;
	}

	protected function setProtectProperties(string ...$protectProperties) {
		$this->protectProperties = [];
		$this->addProtectProperties($protectMethods);
	}

	protected function addProtectProperties(string ...$protectProperties) {
		$this->structuredProtect($protectProperties);
	}

	protected function setProtectMethods(string ...$protectMethods) {
		$this->protectMethods = [];
		$this->addProtectMethods($protectMethods);
	}

	protected function addProtectMethods(string ...$protectMethods) {
		$this->structuredProtect($protectMethods, 'protectMethods');
	}

	private function structuredProtect($protectProperties, $protectList = 'protectProperties', $new = true) {
		if($new) $this->structuredProtect($this->$protectList, $protectList, false);
		//debug(["$protectList - start" => $this->$protectList]);
		foreach (toArray($protectProperties) as $property => $mod) {
			//debug(['class' => getClassName($this), $property => $mod]);
			$this->reverseProtectProperty($property, $mod, $protectList, $new);
		}
		//debug(["$protectList - end" => $this->$protectList]);
	}

	private function reverseProtectProperty($property, $mod, $protectList, $new) {
		list($isConst, $isMethod) = [gettype($property) == 'integer', $this->isMethod($protectList)];
		$reverse = $new ? !$isMethod && $isConst && !array_key_exists($property, $this->$protectList) : !$isMethod && $isConst;
		list($key, $property) = [$property, $isConst ? $mod : $property];
		//debug(['new' => $new, 'reverse' => $reverse, 'property' => $property, 'mod' => $mod, 'protectList' => $protectList]);
		if ($reverse) {
			$property = preg_replace('/=>|=/', ',', str_replace(' ', '', $property));
			$explode = explode(',', $property);
			list($property, $mod) = [$explode[0], isset($explode[1]) && $explode[1] == 'set' ? 'set' : 'get'];
		}
		if ($exist = $this->getExists($protectList, $property)) {
			if(!$isMethod) $this->$protectList[$property] = $mod;
			if($isMethod && $new) $this->$protectList[] = $property;
		}
		$key = !$exist && !$isMethod ? $property : $key;
		if (!$new && !$exist) arrayUnset($this->$protectList, $key);
		//debug([$property => $mod, 'isMethod' => $isMethod, 'key' => $key, 'exist' => $exist, 'del' => !$new && !$exist]);
	}

	private function isMethod($protectList) {
		return $protectList == 'protectMethods';
	}

	private function getExists($protectList, $property, $obj = null) {
		$exist = $this->isMethod($protectList) ? 'isCallable' : 'property_exists';
		return call_user_func_array($exist, [$obj ?? $this, $property]);
	}

	private function exist($property, Closure $callback, $protectList = 'protectProperties') {
		$this->structuredProtect($this->$protectList, $protectList, false);
		$class = getClassName($this);
		$error = ($this->isMethod($protectList) ? 'Метод' : 'Свойство') . " $class::$property";
		list($_property, $exist, $access) = $this->propertyExist($this, $property, $callback, $protectList);
		//debug(['_property' => $_property, 'exist' => $exist, 'access' => $access, 'type' => gettype($_property)]);
		if (!$exist || !$access) {
			$this->getException(500, $error, $exist, $access, $this->isMethod($protectList));
		}
		return $_property;
	}

	private function propertyExist($obj, $property, Closure $callback, $protectList = 'protectProperties', $inProperty = false) {
		if(gettype($obj) !== 'object') return;

		$exist = $this->getExists($protectList, $property, $obj);
		list($isBean, $isMethod) = [$this->isBean($obj), $this->isMethod($protectList)];
		$propertyExist = $exist || ($isBean && array_key_exists($property, $obj->getProperties()));
		$inProtectList = $isMethod ? 'in_array' : 'array_key_exists';
		$access = $isBean || $inProperty ?: $inProtectList($property, $this->$protectList);

		//debug([
		//    'class' => getClassName($obj), 'property' => $property, 'inProperty' => $inProperty, 'protectList' => $protectList,
		//    'isMethod' => $isMethod, 'isBean' => $isBean, 'List' => $this->$protectList, 
		//    'propertyExist' => $propertyExist, 'access' => $access, 'else' => !$access && !$isBean && !$inProperty
		//]);

		if ($propertyExist && $access) {
			$isConst = ($this->$protectList[$property] ?? 'get') === 'get';
			$_property = $callback($obj, $property, $isConst, getClassName($obj), $isBean, $exist);
		} elseif (!$access && !$isBean && !$inProperty) {
			//debug(['protectProperties' => $this->protectProperties]);
			foreach ($this->protectProperties as $protect => $mod) {
				//debug([$protect => $mod]);
				$result = $this->propertyExist($this->$protect, $property, $callback, $protectList, true);
				$_property = $result[0];
				if ($result[1]) list($propertyExist, $access) = [$result[1], $result[2]];
				//debug(['propertyExist' => $propertyExist, 'access' => $access, 'result' => $result]);
				if ($propertyExist && $access) break;
			}
		}
		return [$_property ?? null, $propertyExist, $access];
	}

	private function isBean($obj) {
		return $obj instanceof \RedBeanPHP\OODBBean;
	}


	private function getException($code, $error, $exist = true, $access = true, $isMethod) {
		$context = getContext();
		$msg = $exist && !$access ? "$context->class::$context->function (строка $context->line)" : '';
		$msg = $msg ? "недоступ" . ($isMethod ? 'ен' : 'но') . " в области видимости $msg" : "отсутствует в объекте";
		throw new Exception("$error $msg", 500);
	}

}
