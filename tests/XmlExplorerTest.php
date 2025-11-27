<?php declare(strict_types = 1);

namespace Matraux\XmlOrmTest;

use Matraux\XmlOrm\Exception\XmlParsingException;
use Matraux\XmlOrm\Xml\SimpleExplorer;
use Matraux\XmlOrmTest\Dto\Xml\GeneralXmlNamespace;
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
			SimpleExplorer::fromFile(Bootstrap::Assets . 'general.xml');
		});
	}

	public function testFromString(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$string = FileSystem::read(Bootstrap::Assets . 'general.xml');
		Assert::noError(function () use ($string): void {
			SimpleExplorer::fromString($string);
		});
	}

	public function testWithIndex(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleExplorer::fromFile(Bootstrap::Assets . 'general.xml');
		Assert::exception(function () use ($explorer, $xmlNamespace): void {
			$explorer->withNamespace($xmlNamespace)->withIndex('notExists');
		}, XmlParsingException::class);

		Assert::type(SimpleExplorer::class, $explorer->withNamespace($xmlNamespace)->withIndex('data'));
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

		Assert::count(20000, $explorer);
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

		foreach ($explorer as $index => $subExplorer) {
			Assert::type('int', $index);
			Assert::type(SimpleExplorer::class, $subExplorer);
		}
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



		Assert::exception(function () use ($explorer): void {
			$value = $explorer['test'];
		}, OutOfRangeException::class);

		Assert::exception(function () use ($explorer): void {
			$value = $explorer[-1];
		}, OutOfRangeException::class);

		Assert::equal(true, isset($explorer[0]));

		Assert::exception(function () use ($explorer): void {
			$value = $explorer[20000];
		}, OutOfRangeException::class);

		Assert::type(SimpleExplorer::class, $explorer[0]);
	}

	public function testAttribute(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$xmlNamespace = new GeneralXmlNamespace();
		$explorer = SimpleExplorer::fromFile(Bootstrap::Assets . 'general.xml')
			->withNamespace($xmlNamespace)
			->withIndex('data')
			->withIndex('main');

		Assert::equal('1.3.1', $explorer->attribute('program-version'));
		Assert::equal('Custom note', $explorer->attribute('custom-note'));
		Assert::equal(null, $explorer->attribute('notExists'));
	}

}

(new XmlExplorerTest())->run();
