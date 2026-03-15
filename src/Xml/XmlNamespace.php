<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Xml;

abstract class XmlNamespace
{
	final public function __construct() {}

	abstract public static function getName(): string;

	abstract public static function getSource(): string;
}
