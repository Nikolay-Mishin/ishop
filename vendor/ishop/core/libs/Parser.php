<?php

namespace ishop\libs;

abstract class Parser {

	public static function _namespace(string|object $namespace): array {
		$namespace = is_object($namespace) ? get_class($namespace) : $namespace;
		preg_match('/(.*)(\W)(\w+)$/', $namespace, $match);
		$class = $match[3] ?? $namespace;
		$namespace = $match[1] ?? '';
		return compact('class', 'namespace');
	}

}
