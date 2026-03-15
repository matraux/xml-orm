<?php declare(strict_types=1);

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
		return self::$cache[$entityClass] ??= new ReflectionClass(EntityMetadata::class)
			->newLazyGhost(function (EntityMetadata $metadata) use ($entityClass): void {
				$metadata->__construct(new ReflectionClass($entityClass));
			});
	}
}
