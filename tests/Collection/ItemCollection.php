<?php declare(strict_types = 1);

namespace Matraux\XmlORMTest\Collection;

use Matraux\XmlORM\Collection\Collection;
use Matraux\XmlORMTest\Entity\ItemEntity;

/**
 * @extends Collection<ItemEntity>
 */
final class ItemCollection extends Collection
{

	protected static function getEntityClass(): string
	{
		return ItemEntity::class;
	}

}
