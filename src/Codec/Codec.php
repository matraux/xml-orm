<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Codec;

use Matraux\XmlORM\Metadata\PropertyMetadata;
use Matraux\XmlORM\Xml\Explorer;

interface Codec
{

	public function encode(mixed $value, PropertyMetadata $property): mixed;

	public function decode(Explorer $explorer, PropertyMetadata $property): mixed;

}