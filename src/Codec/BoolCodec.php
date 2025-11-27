<?php declare(strict_types = 1);

namespace Matraux\XmlOrm\Codec;

use BackedEnum;
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

final class BoolCodec implements Codec
{

	public function encode(mixed $value, PropertyMetadata $property): ?bool
	{
		return is_bool($value) ? $value : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $property): ?bool
	{
		$type = $property->type;
		if($type !== 'bool') {
			return null;
		}

		$value = $explorer[$property->index]->value;

		return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
	}

}
