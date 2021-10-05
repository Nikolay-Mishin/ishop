<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

trait T_Static {

    protected static ?string $class = null; // хранит имя класса

    public static function _class(): string {
        return self::$class == static::class ? self::$class : self::$class = static::class;
    }

}
