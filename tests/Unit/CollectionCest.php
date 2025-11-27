<?php declare(strict_types = 1);

namespace Matraux\XmlOrmTest;

use Matraux\XmlOrm\Xml\Explorer;
use Matraux\XmlOrm\Xml\SimpleExplorer;
use Matraux\XmlOrmTest\Dto\Collection\ItemCollection;
use Matraux\XmlOrmTest\Dto\Entity\ItemEntity;
use Matraux\XmlOrmTest\Dto\Xml\GeneralXmlNamespace;
use Matraux\XmlOrmTest\FileSystem\Folder;
use Matraux\XmlOrmTest\Support\UnitTester;
use OutOfRangeException;
use Tester\Assert;
use Tester\TestCase;
use UnexpectedValueException;

final class CollectionCest
{

	protected static function createExplorer(): Explorer
	{
		return SimpleExplorer::fromFile(Folder::create()->data . 'general.xml')
			->withNamespace(new GeneralXmlNamespace())
			->withIndex('data')
			->withIndex('main')
			->withIndex('item');
	}

	public function testCreate(UnitTester $tester): void
	{
		$explorer = static::createExplorer();
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
		$explorer = static::createExplorer();
		$tester->assertCount(20000, ItemCollection::fromExplorer($explorer));
	}

	public function testArrayAccess(UnitTester $tester): void
	{
		$explorer = static::createExplorer();
		$collection = ItemCollection::fromExplorer($explorer);

		$tester->assertEquals(true, isset($collection[0]));

		$tester->assertInstanceOf(ItemEntity::class, $collection[0]);

		$tester->expectThrowable(UnexpectedValueException::class, function() use($collection){
			$collection[-1];
		});

		$tester->expectThrowable(UnexpectedValueException::class, function() use($collection){
			$collection['first'];
		});

		$tester->expectThrowable(OutOfRangeException::class, function() use($collection){
			$collection[20000];
		});
	}

	public function testIterator(UnitTester $tester): void
	{
		$explorer = static::createExplorer();
		$collection = ItemCollection::fromExplorer($explorer);
		foreach ($collection as $index => $itemEntity) {
			$tester->assertIsInt($index);
			$tester->assertInstanceOf(ItemEntity::class, $itemEntity);
		}
	}

}