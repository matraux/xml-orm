<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Codec;

use Matraux\XmlORM\Codec\Codec;
use Matraux\XmlORM\Entity\Entity;
use Matraux\XmlORM\Collection\Collection;
use Matraux\XmlORM\Metadata\PropertyMetadata;
use Matraux\XmlORM\Xml\Explorer;

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
