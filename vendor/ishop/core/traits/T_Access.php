<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

trait T_Set {

    protected array $properties = ['get' => [], 'set' => []];

    public function __get(string $property): mixed {
        $exist = property_exists($this, $property);
        $inSet = in_array($property, $this->properties['set']);
        $inGet = in_array($property, $this->properties['get']);
        return $exist && ($inGet || $inSet) ? $this->$property : null;
    }

    public function __set(string $property, mixed $value): mixed {
        $exist = property_exists($this, $property);
        $inSet = in_array($property, $this->properties['set']);
        return $exist && $inSet ? $this->$property = $value : null;
    }

}
