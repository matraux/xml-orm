<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Test\Unit;

use Codeception\Configuration;
use Matraux\XmlOrm\Exception\XmlParsingException;
use Matraux\XmlOrm\Xml\Explorer;
use Matraux\XmlOrm\Xml\SimpleExplorer;
use Matraux\XmlOrm\Test\Dto\Xml\GeneralXmlNamespace;
use Matraux\XmlOrm\Test\Support\UnitTester;
use OutOfRangeException;
use UnexpectedValueException;

final class SimpleExplorerCest
{
	public function testFromFile(UnitTester $tester): void
	{
		self::createExplorer();
	}

	public function testWithIndex(UnitTester $tester): void
	{
		$explorer = self::createExplorer();

		$tester->expectThrowable(XmlParsingException::class, function () use ($explorer): void {
			$explorer->withNamespace(new GeneralXmlNamespace())->withIndex('notExists');
		});
		$tester->assertInstanceOf(SimpleExplorer::class, $explorer->withNamespace(new GeneralXmlNamespace())->withIndex('data'));
	}

	public function testCountable(UnitTester $tester): void
	{
		$explorer = self::createExplorer()
			->withNamespace(new GeneralXmlNamespace())
			->withIndex('data')
			->withIndex('main')
			->withIndex('item');

		$tester->assertCount(20000, $explorer);
	}

	public function testIterator(UnitTester $tester): void
	{
		$explorer = self::createExplorer()
			->withNamespace(new GeneralXmlNamespace())
			->withIndex('data')
			->withIndex('main')
			->withIndex('item');

		foreach ($explorer as $index => $subExplorer) {
			$tester->assertIsInt($index);
			$tester->assertInstanceOf(SimpleExplorer::class, $subExplorer);
		}
	}

	public function testArrayAccess(UnitTester $tester): void
	{
		$explorer = self::createExplorer()
			->withNamespace(new GeneralXmlNamespace())
			->withIndex('data')
			->withIndex('main')
			->withIndex('item');

		$tester->expectThrowable(OutOfRangeException::class, fn(): mixed => $explorer['notExists']);

		$tester->expectThrowable(UnexpectedValueException::class, fn(): mixed => $explorer[-1]);

		$tester->assertTrue(isset($explorer[0]));

		$tester->expectThrowable(OutOfRangeException::class, fn(): mixed => $explorer[20000]);

		$tester->assertInstanceOf(SimpleExplorer::class, $explorer[0]);
	}

	public function testAttribute(UnitTester $tester): void
	{
		$explorer = self::createExplorer()
			->withNamespace(new GeneralXmlNamespace())
			->withIndex('data')
			->withIndex('main');

		$tester->assertEquals('1.3.1', $explorer->withNamespace(null)->attribute('program-version'));
		$tester->assertEquals('Custom note', $explorer->withNamespace(null)->attribute('custom-note'));
		$tester->assertEquals(null, $explorer->withNamespace(null)->attribute('notExists'));
		$tester->assertEquals('local', $explorer->attribute('target'));
	}

	protected static function createExplorer(): Explorer
	{
		return SimpleExplorer::fromFile(Configuration::dataDir() . 'general.xml');
	}
}
