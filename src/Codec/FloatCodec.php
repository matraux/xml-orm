<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Codec;

use Matraux\JsonOrm\Exception\CodecException;
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

final readonly class FloatCodec implements Codec
{
	/**
	 * @throws CodecException
	 */
	public function encode(mixed $value, PropertyMetadata $metadata): float
	{
		if (!is_float($value)) {
			throw new CodecException(sprintf('%s::$%s expects float, %s given.', $metadata->class, $metadata->name, get_debug_type($value)));
		}

		return $value;
	}

	public function decode(Explorer $explorer, PropertyMetadata $metadata): ?float
	{
		$value = $explorer[$metadata->index]->value;

		return filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
	}
}
