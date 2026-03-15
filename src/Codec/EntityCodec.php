<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Codec;

use Matraux\JsonOrm\Exception\CodecException;
use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

final readonly class EntityCodec implements Codec
{
	/**
	 * @param class-string<Entity> $class
	 */
	public function __construct(protected string $class) {}

	/**
	 * @throws CodecException
	 */
	public function encode(mixed $value, PropertyMetadata $metadata): ?Entity
	{
		if ($value !== null && !$value instanceof $this->class) {
			throw new CodecException(sprintf('%s::$%s expects %s, %s given.', $metadata->class, $metadata->name, $this->class, get_debug_type($value)));
		}

		/** @var ?Entity $value */
		return $value;
	}

	public function decode(Explorer $explorer, PropertyMetadata $metadata): ?Entity
	{
		$value = $explorer[$metadata->index]->value;

		return $value !== null ? $this->class::fromExplorer($explorer->withNamespace($metadata->namespace)->withIndex($metadata->index)) : null;
	}
}
