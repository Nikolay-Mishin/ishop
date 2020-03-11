<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

use \Exception;
use \Closure;

trait T_Protect {

	protected $protectProperties = ['protectProperties']; // ['protectProperties', 'properties', 'protectMethods', 'methods']
	protected $properties = [];
	protected $protectMethods = ['getProtectAttrs', 'getPrivateAttrs']; // ['getProtectAttrs', 'getPrivateAttrs']
	protected $methods = [];

	public function __get($property){
		return $this->exist($property, function($obj, $property){
			return $obj->$property;
		});
	}

	public function __set($property, $value){
		$this->exist($property, function($obj, $property, $const, $error, $class) use ($value) {
			if(!$const) $obj->$property = $value;
			else $this->getException(500, "Изменение значения свойства $class::$property");
		});
	}

	public function __call($method, array $attrs){
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

		//return $this->exist($method, function($obj, $method){
		//    debug(['class' => getClassName($obj), 'method' => $method]);
		//}, 'protectMethods');

		//if(isCallable($obj, $method)){
		//    if($isBean) return call_user_func_array([$obj, $method], $attrs);
		//    $method = getReflector($obj)->getMethod($method);
		//    $method->setAccessible(true);
		//    return $method->invoke($obj);
		//}
	}

	public function getProtectProperties(){
		return $this->protectProperties;
	}

	public function getProperties(){
		return (object) $this->properties;
	}

	public function getProtectMethods(){
		return $this->protectMethods;
	}

	public function getMethods(){
		return (object) $this->methods;
	}

	protected function setProtectProperties(...$protectProperties){
		$this->protectProperties = [];
		$this->addProtectProperties($protectMethods);
	}

	protected function addProtectProperties(...$protectProperties){
		$this->structuredProtectProperties($protectProperties);
	}

	protected function setProtectMethods(...$protectMethods){
		$this->protectMethods = [];
		$this->addProtectMethods($protectMethods);
	}

	protected function addProtectMethods(...$protectMethods){
		$this->structuredProtectProperties($protectMethods, 'protectMethods');
	}

	private function structuredProtectProperties($protectProperties, $protectList = 'protectProperties', $before = true){
		if($before) $this->structuredProtectProperties($this->$protectList, $protectList, false);
		foreach(toArray($protectProperties) as $property => $mod){
			//debug([$property, $mod, $before, array_key_exists($property, $this->$protectList)]);
			$condition = !$before ?: !array_key_exists($property, $this->$protectList);
			$this->reverseProtectProperty($property, $mod, $condition, $protectList, $before);
		}
	}

	private function reverseProtectProperty($property, $mod, $condition, $protectList, $before){
		//debug([$property, $mod, $condition, $protectList]);
		if($condition){
			list($key, $isConst) = [$property, gettype($property) == 'integer'];
			list($property, $mod) = [$isConst ? $mod : $property, !$isConst && $mod == 'set' ? 'set' : 'get'];
			$this->$protectList[$property] = $mod;
			$key = !property_exists($this, $property) ? $property : $key;
			if($isConst && !$before || !property_exists($this, $property)) arrayUnset($this->$protectList, $key);
		}
		//debug([$property, $mod, $condition, $protectList]);
	}

	private function exist($property, Closure $callback, $protectList = 'protectProperties'){
		debug($this->$protectList);
		foreach(array_keys($this->$protectList) as $key){
			if(gettype($key) == 'integer'){
				$this->structuredProtectProperties($this->$protectList, $protectList, false);
				break;
			}
		}
		debug($this->$protectList);

		$class = getClassName($this);
		$error = "Свойство $class::$property";

		list($_property, $inObj, $inProperty) = $this->propertyExist($this, $property, $callback, $error);

		if(!$_property){
			$this->getException(500, $error, $inObj, $inProperty);
		}

		return $_property;
	}

	private function propertyExist($obj, $property, Closure $callback, $error, $inProtectProperty = false){
		if(gettype($obj) !== 'object') return false;

		list($inBean, $isBean, $inObj, $inProperty) = [preg_match('/^_(.*)$/', $property), $this->isBean($obj), false, false];
		$beanExist = $this->isBean($obj->bean);

		$obj = $inBean && !$isBean && $beanExist ? $obj->bean : $obj;
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
		list($context, $exist) = [getContext(), $inObj || $inProperty];
		$msg = $exist ? "$context->class::$context->function (строка $context->line)" : '';
		$msg = $msg ? "недоступно в области видимости $msg" : "отсутствует в объекте";
		throw new Exception("$error $msg", 500);
	}

}
