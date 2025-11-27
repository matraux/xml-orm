<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Xml;

use Attribute;

#[Attribute]
final readonly class XmlAttribute
{

	public function __construct(public string $name)
	{
	}

}
