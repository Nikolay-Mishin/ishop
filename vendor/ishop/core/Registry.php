<?php
// класс Реестр - хранит все параметры приложения
// реализует паттерн Одиночка (Singletone)

namespace ishop;

class Registry {

    use \ishop\traits\T_Singletone; // подключаем трейт Singletone с помощью директивы (служебного слова) use

    protected static array $properties = [];

    // сеттер - инициализирует (устанавливает) свойство реестра (ключ - значение)
    public function setProperty(string $name, $value): void {
        self::$properties[$name] = $value;
    }

    // геттер - получает свойство из реестра по имени
    public function getProperty(string $name) {
        // если значение есть в реестре возвращаем его, иначе null
        if (isset(self::$properties[$name])) {
            return self::$properties[$name];
        }
        return null;
    }

    // возвращает контейнер (свойство) $properties
    public function getProperties(): array {
        return self::$properties;
    }

}
