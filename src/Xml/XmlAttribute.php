<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Xml;

use Attribute;

/**
 * Mapper for attributes of XML elements
 */
#[Attribute]
final readonly class XmlAttribute
{

	public function __construct(public string $name)
	{
	}

}
