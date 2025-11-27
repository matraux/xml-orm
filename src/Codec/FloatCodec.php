<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Codec;

use BackedEnum;
use Matraux\XmlORM\Metadata\PropertyMetadata;
use Matraux\XmlORM\Xml\Explorer;

final class FloatCodec implements Codec
{

	public function encode(mixed $value, PropertyMetadata $property): ?float
	{
		return is_float($value) ? $value : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $property): ?float
	{
		$type = $property->type;
		if($type !== 'float') {
			return null;
		}

		$value = $explorer[$property->index]->value;

		return filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
	}

}
