<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Xml;

/**
 * Mapper for XML namespaces
 * Define #[Attribute] for extended class
 */
abstract class XmlNamespace
{

	abstract public static function getName(): string;

	abstract public static function getSource(): string;

	final public function __construct()
	{
	}

}
