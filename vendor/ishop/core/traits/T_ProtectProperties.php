<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

trait T_ProtectProperties {

	protected $protectProperties = [];

	public function __get($property) {
		if(property_exists($this, $property)){
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if(property_exists($this, $property)){
			$this->$property = $value;
		}
	}

	protected function setProtectProperties($properties){
		$properties = toArray($properties);
		foreach($properties as $property){
			if(!in_array($property, $this->protectProperties)){
				$this->protectProperties[] = $property;
			}
		}
	}

}
