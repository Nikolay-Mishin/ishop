<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

trait T_Instance {

    use T_Static;

    private static ?self $instance = null; // хранит экземпляр класса

    public static function instance(?string $class = null): self {
		$class ??= self::_class();
		return self::$instance instanceof $class ? self::$instance : self::$instance = new $class;
	}

}
