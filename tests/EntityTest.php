<?php declare(strict_types = 1);

namespace Matraux\XmlORMTest;

use Matraux\XmlORM\Xml\SimpleXmlExplorer;
use Matraux\XmlORMTest\Collection\ItemCollection;
use Matraux\XmlORMTest\Entity\DataEntity;
use Matraux\XmlORMTest\Entity\MainEntity;
use Matraux\XmlORMTest\Entity\ResponseEntity;
use Matraux\XmlORMTest\Xml\GeneralXmlNamespace;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::tester();

/**
 * @testCase
 */
final class EntityTest extends TestCase
{

	public function testCreate(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleXmlExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withIndex('data', $xmlNamespace);

		Assert::type(DataEntity::class, DataEntity::create());
		Assert::type(DataEntity::class, DataEntity::fromExplorer($explorer));
	}

	public function testPropertyAssign(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleXmlExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withIndex('data', $xmlNamespace);
		$entity = DataEntity::fromExplorer($explorer);

		Assert::type(MainEntity::class, $entity->main);
		Assert::type(ItemCollection::class, $entity->main->items);
		Assert::equal('Some custom name', $entity->main->name);
		Assert::equal('Some custom surname', $entity->main->surname);
		Assert::equal('Custom note', $entity->main->note);
		Assert::equal('1.3.1', $entity->main->version);
	}

	public function testAsXml(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$response = ResponseEntity::create();
		$data = $response->data = DataEntity::create();
		$main = $data->main = MainEntity::create();
		$items = $main->items = ItemCollection::create();
		$items->createEntity()->id = 1;
		$items->createEntity()->id = 2;

		Assert::matchFile(Bootstrap::Assets . 'match.xml', (string) $response);
	}

}

(new EntityTest())->run();
