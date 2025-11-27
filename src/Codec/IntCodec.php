<?php declare(strict_types = 1);

namespace Matraux\XmlOrm\Codec;

use BackedEnum;
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

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
