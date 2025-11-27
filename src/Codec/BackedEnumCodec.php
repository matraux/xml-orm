<?php declare(strict_types = 1);

namespace Matraux\XmlOrm\Codec;

use BackedEnum;
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

final class BackedEnumCodec implements Codec
{

	public function encode(mixed $value, PropertyMetadata $property): null|int|string
	{
		return $value instanceof BackedEnum ? $value->value : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $property): ?BackedEnum
	{
		$type = $property->type;
		if (!$type || !is_subclass_of($type, BackedEnum::class)) {
			return null;
		}

		$value = $explorer[$property->index]->value;

		/** @var class-string<BackedEnum> $type */
		return $value ? $type::tryFrom($value) : null;
	}

}
