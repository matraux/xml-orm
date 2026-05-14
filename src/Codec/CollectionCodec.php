<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Codec;

use Matraux\XmlOrm\Collection\Collection;
use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Exception\CodecException;
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

final readonly class CollectionCodec implements Codec
{
	/**
	 * @param class-string<Collection<Entity>> $class
	 */
	public function __construct(protected string $class) {}

	/**
	 * @return ?Collection<Entity>
	 * @throws CodecException
	 */
	public function encode(mixed $value, PropertyMetadata $metadata): ?Collection
	{
		if ($value !== null && !$value instanceof $this->class) {
			throw new CodecException(sprintf('%s::$%s expects %s, %s given.', $metadata->class, $metadata->name, $this->class, get_debug_type($value)));
		}

		/** @var ?Collection<Entity> $value */
		return $value;
	}

	/**
	 * @return ?Collection<Entity>
	 */
	public function decode(Explorer $explorer, PropertyMetadata $metadata): ?Collection
	{
		$explorer = $explorer->withNamespace($metadata->namespace)->withIndex($metadata->index);

		return $explorer->value !== null ? $this->class::fromExplorer($explorer) : null;
	}
}
