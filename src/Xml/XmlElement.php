<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Xml;

use Attribute;

#[Attribute]
final readonly class XmlElement
{
	public function __construct(public string $name) {}
}
