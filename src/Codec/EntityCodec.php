<?php declare(strict_types = 1);

namespace Matraux\XmlOrm\Codec;

use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

final class EntityCodec implements Codec
{

	public function encode(mixed $value, PropertyMetadata $property): ?Entity
	{
		return $value instanceof Entity ? $value : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $property): ?Entity
	{
		$type = $property->type;
		if (!$type || !is_subclass_of($type, Entity::class)) {
			return null;
		}

		/** @var class-string<Entity> $type */
		return $type::fromExplorer($explorer->withNamespace($property->namespace)->withIndex($property->index));
	}

}