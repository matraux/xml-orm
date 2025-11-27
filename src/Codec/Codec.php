<?php declare(strict_types = 1);

namespace Matraux\XmlOrm\Codec;

use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

interface Codec
{

	public function encode(mixed $value, PropertyMetadata $property): mixed;

	public function decode(Explorer $explorer, PropertyMetadata $property): mixed;

}
