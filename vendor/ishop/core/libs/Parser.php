<?php

namespace ishop\libs;

abstract class Parser {

	public static function _namespace(string|object $class, string $postfix = ''): array {
		$type = is_object($class) ? get_class($class) : $class;
		$class = $type.$postfix;
		$namespace = getNamespace($type);
		$className = getClassName($type).$postfix;
		return compact('type', 'class', 'namespace', 'className');
	}

}
