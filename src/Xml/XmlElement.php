<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Xml;

use Attribute;

#[Attribute]
final readonly class XmlElement
{

	public function __construct(public string $name)
	{
	}

}
