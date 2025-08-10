<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Xml;

use Attribute;

/**
 * Mapper for XML elements
 */
#[Attribute]
final readonly class XmlElement
{

	public function __construct(public string $name)
	{
	}

}
