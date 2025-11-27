<?php declare(strict_types = 1);

namespace Matraux\XmlOrm\Codec;

use Matraux\XmlOrm\Codec\Codec;
use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Collection\Collection;
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

final class CollectionCodec implements Codec
{

	/**
	 * @return Collection<Entity>|null
	 */
	public function encode(mixed $value, PropertyMetadata $property): ?Collection
	{
		return $value instanceof Collection ? $value : null;
	}

	/**
	 * @return Collection<Entity>|null
	 */
	public function decode(Explorer $explorer, PropertyMetadata $property): ?Collection
	{
		$type = $property->type;
		if (!$type || !is_subclass_of($type, Collection::class)) {
			return null;
		}

		/** @var class-string<Collection<Entity>> $type */
		return $type::fromExplorer($explorer->withNamespace($property->namespace)->withIndex($property->index));
	}

}
