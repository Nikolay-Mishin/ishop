<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

use \Exception;
use \Closure;

trait T_Protect {

	protected $protectProperties = ['protectProperties']; // ['protectProperties', 'properties', 'protectMethods', 'methods']
	protected $protectMethods = ['getProtectAttrs', 'getPrivateAttrs']; // ['getProtectAttrs', 'getPrivateAttrs']

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

		//$exist = $this->exist($method, function($obj, $method){
		//    debug(['class' => getClassName($obj), 'method' => $method]);
		//}, 'protectMethods');

		if($isBean && isCallable($obj, $method)) return call_user_func_array([$obj, $method], $args);
		//return callPrivateMethod($obj, $method, $args);
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
		$this->structuredProtectProperties($protectProperties);
		debug($this->protectProperties);
	}

	protected function setProtectMethods(...$protectMethods){
		$this->protectMethods = [];
		$this->addProtectMethods($protectMethods);
	}

	protected function addProtectMethods(...$protectMethods){
		$this->structuredProtectProperties($protectMethods, 'protectMethods');
		debug(['protectMethods' => $this->protectMethods]);
	}

	private function structuredProtectProperties($protectProperties, $protectList = 'protectProperties', $new = true){
		if($new) $this->structuredProtectProperties($this->$protectList, $protectList, false);
        debug(['protectProperties' => $protectProperties]);
		foreach(toArray($protectProperties) as $property => $mod){
			debug([$property => $mod]);
			$this->reverseProtectProperty($property, $mod, $protectList, $new);
			debug([$property => $mod]);
		}
	}

	private function reverseProtectProperty($property, $mod, $protectList, $new){
		$isConst = gettype($property) == 'integer';
        $reverse = $new ? $isConst && !array_key_exists($property, $this->$protectList) : $isConst;
		if($reverse){
			list($key, $property) = [$property, $isConst ? $mod : $property];

			$property = preg_replace('/=>|=/', ',', str_replace(' ', '', $property));
			$explode = explode(',', $property);
			list($property, $mod) = [$explode[0], isset($explode[1]) && $explode[1] == 'set' ? 'set' : 'get'];

			$isMethod = preg_match('/()$/', $property);
			$property = $isMethod ? str_replace('()', '', $property) : $property;
			$exist = $protectList == 'protectMethods' ? 'method_exists' : 'property_exists';
			list($exist, $isMethod) = [$exist($this, $property), $protectList == 'protectMethods'];

            if($exist){
                $isMethod ? $this->$protectList[$property] = $mod : $this->$protectList[] = $property;
            }

			$key = !$exist ? $property : $key;
			if(!$new) arrayUnset($this->$protectList, $key);
			debug(['isMethod' => $isMethod, 'key' => $key, 'exist' => $exist, 'del' => !$new]);
		}
		debug(['new' => $new, 'reverse' => $reverse, 'property' => $property, 'mod' => $mod, 'protectList' => $protectList]);
	}

	private function exist($property, Closure $callback, $protectList = 'protectProperties'){
		debug(['protectList' => $this->$protectList]);
        //foreach(array_keys($this->$protectList) as $key){
        //    if(gettype($key) == 'integer'){
        //        $this->structuredProtectProperties($this->$protectList, $protectList, false);
        //        break;
        //    }
        //}
        $this->structuredProtectProperties($this->$protectList, $protectList, false);
		debug(['protectList' => $this->$protectList]);

		$class = getClassName($this);
		$error = "Свойство $class::$property";
		list($_property, $exist) = $this->propertyExist($this, $property, $callback, $protectList);
		if(!$_property){
			$this->getException(500, $error, $exist);
		}
		return $_property;
	}

	private function propertyExist($obj, $property, Closure $callback, $protectList = 'protectProperties', $inProperty = false){
		if(gettype($obj) !== 'object') return false;

		list($inBean, $isBean, $beanExist) = [preg_match('/^_(.*)$/', $property), $this->isBean($obj), $this->isBean($obj->bean)];
		$obj = $inBean && !$isBean && $beanExist ? $obj->bean : $obj;
		list($property, $isBean) = [preg_replace('/^_(.*)$/', '$1', $property), $this->isBean($obj)];
        $propertyExist = ($isBean && array_key_exists($property, $obj->getProperties())) || property_exists($obj, $property);
		$access = $isBean ?: array_key_exists($property, $this->$protectList);

		if($propertyExist && $access){
			$isConst = ($this->$protectList[$property] ?? 'get') === 'get';
			$_property = $callback($obj, $property, $isConst, getClassName($obj));
		}elseif(!$access && !$isBean && !$inProperty){
			foreach($this->$protectList as $protect => $mod){
				if($_property = $this->propertyExist($this->$protect, $property, $callback, $protectList, true)[0]) break;
			}
		}

		return [$_property ?? null, $propertyExist];
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
