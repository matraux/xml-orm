<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Codec;

use BackedEnum;
use Matraux\XmlORM\Metadata\PropertyMetadata;
use Matraux\XmlORM\Xml\Explorer;

final class IntCodec implements Codec
{

	public function encode(mixed $value, PropertyMetadata $property): ?int
	{
		return is_int($value) ? $value : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $property): ?int
	{
		$type = $property->type;
		if($type !== 'int') {
			return null;
		}

		$value = $explorer[$property->index]->value;

		return filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
	}

}
