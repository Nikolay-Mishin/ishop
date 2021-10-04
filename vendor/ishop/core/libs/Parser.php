<?php

namespace ishop\libs;

abstract class Parser {

	public static function _namespace(string|object $class, string $postfix = ''): array {
		$type = is_object($class) ? get_class($class) : $class;
		$class = $type.$postfix;
		preg_match('/(.*)(\W)(\w+)$/', $class, $match);
		$namespace = $match[1] ?? '';
		$className = $match[3] ?? $class;
		return compact('type', 'class', 'namespace', 'className');
	}

}
