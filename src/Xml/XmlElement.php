<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Xml;

use Attribute;

/**
 * Mapper for XML elements
 */
#[Attribute]
final class XmlElement
{

	public function __construct(public readonly string $name)
	{
	}

}
