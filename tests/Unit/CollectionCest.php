<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Test;

use Codeception\Configuration;
use Matraux\XmlOrm\Xml\Explorer;
use Matraux\XmlOrm\Xml\SimpleExplorer;
use Matraux\XmlOrm\Test\Dto\Collection\ItemCollection;
use Matraux\XmlOrm\Test\Dto\Entity\ItemEntity;
use Matraux\XmlOrm\Test\Dto\Xml\GeneralXmlNamespace;
use Matraux\XmlOrm\Test\Support\UnitTester;
use OutOfRangeException;
use UnexpectedValueException;

final class CollectionCest
{
	public function testCreate(UnitTester $tester): void
	{
		$explorer = self::createExplorer();
		$tester->assertInstanceOf(ItemCollection::class, ItemCollection::create());
		$tester->assertInstanceOf(ItemCollection::class, ItemCollection::fromExplorer($explorer));
	}

	public function testCreateEntity(UnitTester $tester): void
	{
		$collection = ItemCollection::create();
		$tester->assertInstanceOf(ItemEntity::class, $collection->createEntity());
	}

	public function testCountable(UnitTester $tester): void
	{
		$explorer = self::createExplorer();
		$tester->assertCount(20000, ItemCollection::fromExplorer($explorer));
	}

	public function testArrayAccess(UnitTester $tester): void
	{
		$explorer = self::createExplorer();
		$collection = ItemCollection::fromExplorer($explorer);

		$tester->assertTrue(isset($collection[0]));

		$tester->assertInstanceOf(ItemEntity::class, $collection[0]);

		$tester->expectThrowable(UnexpectedValueException::class, fn(): mixed => $collection[-1]);

		$tester->expectThrowable(OutOfRangeException::class, fn(): mixed => $collection[20000]);
	}

	public function testIterator(UnitTester $tester): void
	{
		$explorer = self::createExplorer();
		$collection = ItemCollection::fromExplorer($explorer);
		foreach ($collection as $itemEntity) {
			$tester->assertInstanceOf(ItemEntity::class, $itemEntity);
		}
	}

	protected static function createExplorer(): Explorer
	{
		return SimpleExplorer::fromFile(Configuration::dataDir() . 'general.xml')
			->withNamespace(new GeneralXmlNamespace())
			->withIndex('data')
			->withIndex('main')
			->withIndex('item');
	}
}
