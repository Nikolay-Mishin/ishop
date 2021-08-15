<?php
// класс Реестр - хранит все параметры приложения
// реализует паттерн Одиночка (Singletone)

namespace ishop;

class Registry {

    use traits\T_Singletone; // подключаем трейт Singletone с помощью директивы (служебного слова) use

    protected static array $properties = [];

    // возвращает контейнер (свойство) $properties
    public function getProperties(): array {
        return self::$properties;
    }

    // сеттер - инициализирует (устанавливает) свойство реестра (ключ - значение)
    public function setProperty(string $name, mixed $value): mixed {
        return self::$properties[$name] = $value;
    }

    // геттер - получает свойство из реестра по имени
    public function getProperty(string $name): mixed {
        // если значение есть в реестре возвращаем его, иначе null
        if (isset(self::$properties[$name])) {
            return self::$properties[$name];
        }
        return null;
    }

    public function deleteProperty(string $name): void {
        unset(self::$properties[$name]);
    }

    public function addInProperty(string $name, string $key, mixed $value): mixed {
        // если значение есть в реестре возвращаем его, иначе null
        $property = self::getProperty($name) ?: self::setProperty($name, []);
        $property[$key] = $value;
        self::setProperty($name, $property);
        return $value;
    }

    public function deleteInProperty(string $name, string $key): void {
        if (is_array(self::$properties[$name])) unset(self::$properties[$name][$key]);
    }

}
