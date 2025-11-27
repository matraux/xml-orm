<?php declare(strict_types = 1);

namespace Matraux\XmlOrmTest;

use Tester\Assert;
use Tester\TestCase;
use Matraux\XmlOrm\Xml\Explorer;
use Matraux\XmlOrm\Xml\SimpleExplorer;
use Matraux\XmlOrmTest\FileSystem\Folder;
use Matraux\XmlOrmTest\Support\UnitTester;
use Matraux\XmlOrmTest\Dto\Entity\DataEntity;
use Matraux\XmlOrmTest\Dto\Entity\MainEntity;
use Matraux\XmlOrmTest\Dto\Entity\Enum\Active;
use Matraux\XmlOrmTest\Dto\Entity\ResponseEntity;
use Matraux\XmlOrmTest\Dto\Xml\GeneralXmlNamespace;
use Matraux\XmlOrmTest\Dto\Collection\ItemCollection;

final class EntityCest
{

	protected static function createExplorer(): Explorer
	{
		return SimpleExplorer::fromFile(Folder::create()->data . 'general.xml')
			->withNamespace(new GeneralXmlNamespace())
			->withIndex('data');
	}

	public function testCreate(UnitTester $tester): void
	{
		$explorer = static::createExplorer();
		$tester->assertInstanceOf(DataEntity::class, DataEntity::create());
		$tester->assertInstanceOf(DataEntity::class, DataEntity::fromExplorer($explorer));
	}

	public function testPropertyAssign(UnitTester $tester): void
	{
		$explorer = static::createExplorer();
		$entity = DataEntity::fromExplorer($explorer);

		$tester->assertInstanceOf(MainEntity::class, $entity->main);
		$tester->assertInstanceOf(ItemCollection::class, $entity->main->items);
		$tester->assertEquals(Active::False, $entity->main->items[0]->active);
		$tester->assertEquals(Active::True, $entity->main->items[2]->active);
		$tester->assertEquals('Some custom name', $entity->main->name);
		$tester->assertEquals('Some custom surname', $entity->main->surname);
		$tester->assertEquals('Custom note', $entity->main->note);
		$tester->assertEquals('1.3.1', $entity->main->version);
	}

	public function testAsXml(UnitTester $tester): void
	{
		$response = ResponseEntity::create();
		$data = $response->data = DataEntity::create();
		$main = $data->main = MainEntity::create();
		$items = $main->items = ItemCollection::create();
		$items->createEntity()->id = 1;
		$items->createEntity()->id = 2;

		$tester->assertStringEqualsFile(Folder::create()->data . 'match.xml', (string) $response);
	}

}
