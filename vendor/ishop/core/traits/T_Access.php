<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

trait T_Access {

    protected array $properties = ['get' => [], 'set' => []];

    public function __get(string $property): mixed {
        return $this->verifyAccess('get', $property);
    }

    public function __set(string $property, mixed $value): mixed {
        return $this->verifyAccess('set', $property, $value);
    }

    public function verifyAccess(string $type, string $property, mixed $value = null): mixed {
        $exist = property_exists($this, $property);
        $inGet = !$this->properties['get'] ?: in_array($property, $this->properties['get']);
        $inSet = !$this->properties['set'] ?: in_array($property, $this->properties['set']);
        return $exist && ($inSet || $inGet) ? ($type != 'set' ? $this->$property : $this->$property = $value) : null;
    }

}
