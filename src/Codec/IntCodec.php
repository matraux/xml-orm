<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Codec;

use Matraux\JsonOrm\Exception\CodecException;
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

final readonly class IntCodec implements Codec
{
	/**
	 * @throws CodecException
	 */
	public function encode(mixed $value, PropertyMetadata $metadata): int
	{
		if (!is_int($value)) {
			throw new CodecException(sprintf('%s::$%s expects int, %s given.', $metadata->class, $metadata->name, get_debug_type($value)));
		}

		return $value;
	}

	public function decode(Explorer $explorer, PropertyMetadata $metadata): ?int
	{
		$value = $explorer[$metadata->index]->value;

		return filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
	}
}
