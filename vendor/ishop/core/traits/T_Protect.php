<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

use \Exception;
use \Closure;

trait T_Protect {

	protected $protectProperties = [];
	protected $protectMethods = ['getProtectAttrs', 'getPrivateAttrs'];

	public function __get($property){
		return $this->exist($property, function($obj, $property){
			return $obj->$property;
		});
	}

	public function __set($property, $value){
		$this->exist($property, function($obj, $property, $const, $class) use ($value) {
			if(!$const) $obj->$property = $value;
			else $this->getException(500, "Изменение значения свойства $class::$property");
		});
	}

	public function __call($method, array $args){
		// Call to protected method app\models\Product::getProtectAttrs() from context 'app\controllers\ProductController'
		list($inBean, $beanExist) = [preg_match('/^_(.*)$/', $method), $this->isBean($this->bean)];
		$isBean = $inBean && $beanExist;
		list($method, $obj) = [preg_replace('/^_(.*)$/', '$1', $method), $isBean ? $this->bean : $this];
		//debug([
		//    'class' => getClassName($obj), 'method' => $method, 'inBean' => $inBean, 'beanExist' => $beanExist,
		//    'isBean' => $isBean, 'beanExist' => $this->isBean($obj),
		//    'exists' => method_exists($obj, $method), 'isCallable' => isCallable($obj, $method),
		//    //'beanExist' => $this->isBean($obj),
		//    //'caller' => getCaller(), 'context' => getContext(), 'trace' => getTrace()
		//]);

		$exist = $this->exist($method, function($obj, $method){
			debug(['class' => getClassName($obj), 'method' => $method]);
		}, 'protectMethods');

		if($isBean && isCallable($obj, $method)){
			$result = call_user_func_array([$obj, $method], $args);
		}
		else{
			$result = callPrivateMethod($obj, $method, $args);
		}
		//debug(['method' => $method, 'exist' => method_exists($obj, $method), 'callable' => isCallable($obj, $method)]);
		return $result;
	}

	public function getProtectProperties(){
		return $this->protectProperties;
	}

	public function getProtectMethods(){
		return $this->protectMethods;
	}

	protected function setProtectProperties(...$protectProperties){
		$this->protectProperties = [];
		$this->addProtectProperties($protectMethods);
	}

	protected function addProtectProperties(...$protectProperties){
		$this->structuredProtect($protectProperties);
	}

	protected function setProtectMethods(...$protectMethods){
		$this->protectMethods = [];
		$this->addProtectMethods($protectMethods);
	}

	protected function addProtectMethods(...$protectMethods){
		$this->structuredProtect($protectMethods, 'protectMethods');
	}

	private function structuredProtect($protectProperties, $protectList = 'protectProperties', $new = true){
		if($new) $this->structuredProtect($this->$protectList, $protectList, false);
		//debug(['protectProperties' => $protectProperties]);
		foreach(toArray($protectProperties) as $property => $mod){
			//debug([$property => $mod]);
			$this->reverseProtectProperty($property, $mod, $protectList, $new);
		}
		//debug(['protectProperties' => $protectProperties]);
	}

	private function reverseProtectProperty($property, $mod, $protectList, $new){
		$isConst = gettype($property) == 'integer';
		$reverse = $new ? $isConst && !array_key_exists($property, $this->$protectList) : $isConst;
		if($reverse){
			list($key, $property) = [$property, $isConst ? $mod : $property];

			$property = preg_replace('/=>|=/', ',', str_replace(' ', '', $property));
			$explode = explode(',', $property);
			list($property, $mod) = [$explode[0], isset($explode[1]) && $explode[1] == 'set' ? 'set' : 'get'];

			if($exist = $this->getExists($protectList, $property)){
				//!$this->isMethod($protectList) ? $this->$protectList[$property] = $mod : $this->$protectList[] = $property;
				$this->$protectList[$property] = $mod;
			}

			$key = !$exist ? $property : $key;
			if(!$new) arrayUnset($this->$protectList, $key);
			//debug(['isMethod' => $isMethod, 'key' => $key, 'exist' => $exist, 'del' => !$new]);
		}
		//debug(['new' => $new, 'reverse' => $reverse, 'property' => $property, 'mod' => $mod, 'protectList' => $protectList]);
	}

	private function isMethod($protectList){
		return $protectList == 'protectMethods';
	}

	private function getExists($protectList, $property, $obj = null){
		$exist = $this->isMethod($protectList) ? 'isCallable' : 'property_exists';
		return call_user_func_array($exist, [$obj ?? $this, $property]);
	}

	private function exist($property, Closure $callback, $protectList = 'protectProperties'){
		$this->structuredProtect($this->$protectList, $protectList, false);
		$class = getClassName($this);
		$error = ($this->isMethod($protectList) ? 'Метод' : 'Свойство') . " $class::$property";
		list($_property, $exist, $access) = $this->propertyExist($this, $property, $callback, $protectList);
		debug(['_property' => $_property, 'exist' => $exist, 'access' => $access]);
		if(!$_property && !$access){
			$this->getException(500, $error, $exist && !$access, $this->isMethod($protectList));
		}
		return $_property;
	}

	private function propertyExist($obj, $property, Closure $callback, $protectList = 'protectProperties', $inProperty = false){
		if(gettype($obj) !== 'object') return;

		debug(['property' => $property]);

		list($isBean, $beanExist) = [$this->isBean($obj), $this->isBean($obj->bean)];
		list($obj, $isMethod) = [!$isBean && $beanExist ? $obj->bean : $obj, $this->isMethod($protectList)];
		list($obj, $exist) = [!$isBean && $beanExist ? $obj->bean : $obj, $this->getExists($protectList, $property, $obj)];
		$isBean = $this->isBean($obj);
		$propertyExist = $exist || ($isBean && array_key_exists($property, $obj->getProperties()));
		$access = $isBean || $inProperty ?: array_key_exists($property, $this->$protectList);

		debug([
			'class' => getClassName($obj), 'property' => $property, 'inProperty' => $inProperty, 'protectList' => $protectList,
			'isMethod' => $isMethod, 'isBean' => $isBean, 'beanExist' => $beanExist,
			'propertyExist' => $propertyExist, 'access' => $access, 'else' => !$access && !$isBean && !$inProperty
		]);

		if($propertyExist && $access){
			$isConst = ($this->$protectList[$property] ?? 'get') === 'get';
			$_property = $callback($obj, $property, $isConst, getClassName($obj));
		}elseif(!$access && !$isBean && !$inProperty){
			debug(['protectProperties' => $this->protectProperties, 'protectList' => $this->$protectList]);
			foreach($this->protectProperties as $protect => $mod){
				//$protect = $isMethod ? $mod : $protect;
				debug([$protect => $mod]);
				$_property = $this->propertyExist($this->$protect, $property, $callback, $protectList, true);
				if($_property = $_property ?: $_property[0]) break;
			}
		}
		return [$_property ?? null, $propertyExist, $access];
	}

	private function isBean($obj){
		return $obj instanceof \RedBeanPHP\OODBBean;
	}


	private function getException($code, $error, $access = true, $isMethod){
		$context = getContext();
		$msg = $access ? "$context->class::$context->function (строка $context->line)" : '';
		$msg = $msg ? "недоступ" . ($isMethod ? 'ен' : 'но') . " в области видимости $msg" : "отсутствует в объекте";
		throw new Exception("$error $msg", 500);
	}

}
