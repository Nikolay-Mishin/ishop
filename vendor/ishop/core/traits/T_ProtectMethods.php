<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

use \Exception;

use ishop\App;

trait T_ProtectMethods {

    //protected $properties = [];
    //protected $returnProperties = [];

    //public function getProperties(){
    //    return $this->properties;
    //}

    //protected function setReturnProtect($properties){
    //    foreach(toArray($properties) as $property => $mod){
    //        if(!array_key_exists($property, $this->properties)){
    //            $isConst = gettype($property) == 'integer';
    //            list($property, $mod) = [$isConst ? $mod : $property, !$isConst && $mod == 'set' ? 'set' : 'get'];
    //            $this->properties[$property] = $mod;
    //        }
    //    }
    //}

    //public function __get($property){
    //    return $this->propertyExist($this, $property, function($obj, $property){
    //        return $obj->$property;
    //    });
    //}

    //public function __set($property, $value){
    //    $this->propertyExist($this, $property, function($obj, $property, $const) use ($value) {
    //        if(!$const) $obj->$property = $value;
    //    });
    //}

    //protected function propertyExist($obj, $property, $callback = null, $protected = true){
    //    $condition = $protected !== true ?: array_key_exists($property, $this->properties);
    //    $callback = in_array($callback, [null, true], true) ? function(){} : $callback;
    //    debug([getClassName($obj), $property, property_exists($obj, $property), $condition, $obj instanceof \RedBeanPHP\OODBBean]);
    //    $propertyExist = property_exists($obj, $property);
    //    if($obj instanceof \RedBeanPHP\OODBBean){
    //        $propertyExist = array_key_exists($property = preg_replace('/^_(.*)$/', '$1', $property), $obj->getProperties());
    //    }
    //    $propertyExist = $obj instanceof \RedBeanPHP\OODBBean ? array_key_exists($property, $obj->getProperties()) : property_exists($obj, $property);
    //    if($propertyExist && $condition){
    //        $propertyExist = $callback($obj, $property, $this->properties[$property] ?? null == 'get');
    //        if($propertyExist) $this->returnProperties[$property] = $propertyExist;
    //        return $propertyExist;
    //    }elseif(!$condition){
    //        foreach($this->properties as $protect => $mod){
    //            $propertyExist = $this->propertyExist($this->$protect, $property, $callback, false);
    //        }
    //        if(!$propertyExist ?? false){
    //            list($obj, $controller, $action) = [getClassName($obj), App::$controller, App::$action];
    //            throw new Exception("Свойство $property объекта $obj недоступно в области видимости $controller::$action", 500);
    //        }
    //    }
    //    debug([App::$controller, App::$action, $this->returnProperties]);
    //}

}
