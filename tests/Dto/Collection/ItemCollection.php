<?php declare(strict_types = 1);

namespace Matraux\XmlOrmTest\Dto\Collection;

use Matraux\XmlOrm\Collection\Collection;
use Matraux\XmlOrmTest\Dto\Entity\ItemEntity;

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
