<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Codec;

use Matraux\JsonOrm\Exception\CodecException;
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

final readonly class BoolCodec implements Codec
{
	/**
	 * @throws CodecException
	 */
	public function encode(mixed $value, PropertyMetadata $metadata): bool
	{
		if (!is_bool($value)) {
			throw new CodecException(sprintf('%s::$%s expects bool, %s given.', $metadata->class, $metadata->name, get_debug_type($value)));
		}

		return $value;
	}

	public function decode(Explorer $explorer, PropertyMetadata $metadata): ?bool
	{
		$value = $explorer[$metadata->index]->value;

		return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
	}
}
