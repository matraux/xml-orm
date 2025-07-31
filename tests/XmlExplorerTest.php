<?php declare(strict_types = 1);

namespace Matraux\XmlORMTest;

use Matraux\XmlORM\Exception\XmlParsingException;
use Matraux\XmlORM\Xml\SimpleXmlExplorer;
use Matraux\XmlORMTest\Xml\GeneralXmlNamespace;
use Nette\Utils\FileSystem;
use OutOfRangeException;
use Tester\Assert;
use Tester\TestCase;
use UnexpectedValueException;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::tester();

/**
 * @testCase
 */
final class XmlExplorerTest extends TestCase
{

	public function testFromFile(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		Assert::noError(function (): void {
			SimpleXmlExplorer::fromFile(Bootstrap::Assets . 'general.xml');
		});
	}

	public function testFromString(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$string = FileSystem::read(Bootstrap::Assets . 'general.xml');
		Assert::noError(function () use ($string): void {
			SimpleXmlExplorer::fromString($string);
		});
	}

	public function testWithIndex(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleXmlExplorer::fromFile(Bootstrap::Assets . 'general.xml');
		Assert::exception(function () use ($explorer, $xmlNamespace): void {
			$explorer->withIndex('notExists', $xmlNamespace);
		}, XmlParsingException::class);

		Assert::type(SimpleXmlExplorer::class, $explorer->withIndex('data', $xmlNamespace));
	}

	public function testCountable(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleXmlExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withIndex('data', $xmlNamespace)
			->withIndex('main', $xmlNamespace)
			->withIndex('item', $xmlNamespace);

		Assert::count(20000, $explorer);
	}

	public function testIterator(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleXmlExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withIndex('data', $xmlNamespace)
			->withIndex('main', $xmlNamespace)
			->withIndex('item', $xmlNamespace);

		foreach ($explorer as $index => $subExplorer) {
			Assert::type('int', $index);
			Assert::type(SimpleXmlExplorer::class, $subExplorer);
		}
	}

	public function testArrayAccess(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleXmlExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withIndex('data', $xmlNamespace)
			->withIndex('main', $xmlNamespace)
			->withIndex('item', $xmlNamespace);

		Assert::exception(function () use ($explorer): void {
			$value = isset($explorer['test']);
		}, UnexpectedValueException::class);

		Assert::exception(function () use ($explorer): void {
			$value = isset($explorer[-1]);
		}, UnexpectedValueException::class);

		Assert::equal(true, isset($explorer[0]));

		Assert::exception(function () use ($explorer): void {
			$value = $explorer[20000];
		}, OutOfRangeException::class);

		Assert::type(SimpleXmlExplorer::class, $explorer[0]);
	}

	public function testAttribute(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleXmlExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withIndex('data', $xmlNamespace)
			->withIndex('main', $xmlNamespace);

		Assert::equal('1.3.1', $explorer->getAttribute('program-version'));
		Assert::equal('Custom note', $explorer->getAttribute('custom-note'));
		Assert::equal(null, $explorer->getAttribute('notExists'));
	}

}

(new XmlExplorerTest())->run();
