<?php declare(strict_types = 1);

namespace Matraux\XmlOrm\Metadata;

use Matraux\XmlOrm\Entity\Entity;
use ReflectionClass;

final class EntityMetadataFactory
{

	/** @var array<class-string<Entity>,EntityMetadata> */
	protected static array $cache;

	/**
	 * @param class-string<Entity> $entityClass
	 */
	public static function create(string $entityClass): EntityMetadata
	{
		return self::$cache[$entityClass] ??= EntityMetadata::create(new ReflectionClass($entityClass));
	}

}
