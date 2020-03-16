<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

trait T_Set {

    protected $properties = ['get' => [], 'set' => []];

    public function get($property){
        $exist = property_exists($this, $property);
        $inSet = in_array($property, $this->properties['set']);
        $inGet = in_array($property, $this->properties['get']);
        return $inSet && $exist || $inGet && $exist ? $this->$property : null;
    }

    public function set($property, $value){
        $exist = property_exists($this, $property);
        $inSet = in_array($property, $this->properties['set']);
        return $inSet && $exist ? $this->$property = $value : null;
    }

}
