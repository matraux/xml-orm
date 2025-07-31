<?php declare(strict_types = 1);

namespace Matraux\XmlORMTest;

use Tester\Assert;
use Tester\TestCase;
use OutOfRangeException;
use UnexpectedValueException;
use Matraux\XmlORM\Xml\SimpleXmlExplorer;
use Matraux\XmlORMTest\Entity\ItemEntity;
use Matraux\XmlORMTest\Entity\ResponseEntity;
use Matraux\XmlORMTest\Xml\GeneralXmlNamespace;
use Matraux\XmlORMTest\Collection\ItemCollection;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::tester();

/**
 * @testCase
 */
final class CollectionTest extends TestCase
{

	public function testCreate(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleXmlExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withIndex('data', $xmlNamespace)
			->withIndex('main', $xmlNamespace)
			->withIndex('item', $xmlNamespace);

		Assert::type(ItemCollection::class, ItemCollection::create());
		Assert::type(ItemCollection::class, ItemCollection::create($explorer));
	}

	public function testCreateEntity(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$collection = ItemCollection::create();

		Assert::type(ItemEntity::class, $collection->createEntity());
	}

	public function testCountable(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleXmlExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withIndex('data', $xmlNamespace)
			->withIndex('main', $xmlNamespace)
			->withIndex('item', $xmlNamespace);

		Assert::count(20000, ItemCollection::create($explorer));
	}

	public function testArrayAccess(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleXmlExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withIndex('data', $xmlNamespace)
			->withIndex('main', $xmlNamespace)
			->withIndex('item', $xmlNamespace);

		$collection = ItemCollection::create($explorer);

		Assert::exception(function()use($collection): void {
			isset($collection['test']);
		}, UnexpectedValueException::class);

		Assert::exception(function()use($collection): void {
			isset($collection[-1]);
		}, UnexpectedValueException::class);

		Assert::equal(true, isset($collection[0]));

		Assert::exception(function()use($collection): void {
			$collection[20000];
		}, OutOfRangeException::class);

		Assert::type(ItemEntity::class, $collection[0]);
	}

	public function testIterator(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleXmlExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withIndex('data', $xmlNamespace)
			->withIndex('main', $xmlNamespace)
			->withIndex('item', $xmlNamespace);

		$collection = ItemCollection::create($explorer);
		foreach($collection as $index => $itemEntity) {
			Assert::type('int', $index);
			Assert::type(ItemEntity::class, $itemEntity);
		}
	}

}

(new CollectionTest())->run();
