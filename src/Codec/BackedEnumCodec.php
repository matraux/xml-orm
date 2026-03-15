<?php declare(strict_types = 1);

namespace Matraux\XmlOrm\Codec;

use BackedEnum;
use Matraux\JsonOrm\Exception\CodecException;
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;
use TypeError;
use ValueError;

final readonly class BackedEnumCodec implements Codec
{

	/**
	 * @param class-string<BackedEnum> $class
	 */
	public function __construct(protected string $class) {}

	/**
	 * @throws CodecException
	 */
	public function encode(mixed $value, PropertyMetadata $metadata): int|string|null
	{
		if ($value !== null && !$value instanceof $this->class) {
			throw new CodecException(sprintf('%s::$%s expects %s, %s given.', $metadata->class, $metadata->name, $this->class, get_debug_type($value)));
		}

		/** @var ?BackedEnum $value */
		return $value?->value;
	}

	/**
	 * @throws CodecException
	 * @throws ValueError
	 * @throws TypeError
	 */
	public function decode(Explorer $explorer, PropertyMetadata $metadata): ?BackedEnum
	{
		$value = $explorer[$metadata->index]->value;

		return $value !== null ? $this->class::from($value) : null;
	}

}
