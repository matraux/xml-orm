<?php declare(strict_types = 1);

namespace Matraux\XmlOrmTest;

use Matraux\XmlOrm\Xml\SimpleExplorer;
use Matraux\XmlOrmTest\Dto\Collection\ItemCollection;
use Matraux\XmlOrmTest\Dto\Entity\ItemEntity;
use Matraux\XmlOrmTest\Dto\Xml\GeneralXmlNamespace;
use OutOfRangeException;
use Tester\Assert;
use Tester\TestCase;
use UnexpectedValueException;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::tester();

/**
 * @testCase
 */
final class CollectionCest extends TestCase
{

	public function testCreate(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withNamespace($xmlNamespace)
			->withIndex('data')
			->withIndex('main')
			->withIndex('item');

		Assert::type(ItemCollection::class, ItemCollection::create());
		Assert::type(ItemCollection::class, ItemCollection::fromExplorer($explorer));
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
		$explorer = SimpleExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withNamespace($xmlNamespace)
			->withIndex('data')
			->withIndex('main')
			->withIndex('item');

		Assert::count(20000, ItemCollection::fromExplorer($explorer));
	}

	public function testArrayAccess(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withNamespace($xmlNamespace)
			->withIndex('data')
			->withIndex('main')
			->withIndex('item');

		$collection = ItemCollection::fromExplorer($explorer);

		Assert::exception(function () use ($collection): void {
			$value = isset($collection[-1]);
		}, UnexpectedValueException::class);

		Assert::equal(true, isset($collection[0]));

		Assert::exception(function () use ($collection): void {
			$value = $collection[20000];
		}, OutOfRangeException::class);

		Assert::type(ItemEntity::class, $collection[0]);
	}

	public function testIterator(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withNamespace($xmlNamespace)
			->withIndex('data')
			->withIndex('main')
			->withIndex('item');

		$collection = ItemCollection::fromExplorer($explorer);
		foreach ($collection as $index => $itemEntity) {
			Assert::type('int', $index);
			Assert::type(ItemEntity::class, $itemEntity);
		}
	}

}

(new CollectionCest())->run();
