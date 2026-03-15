<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Test\Dto\Collection;

use Matraux\XmlOrm\Collection\Collection;
use Matraux\XmlOrm\Test\Dto\Entity\ItemEntity;

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
