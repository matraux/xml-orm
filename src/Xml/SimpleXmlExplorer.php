<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Xml;

use Traversable;
use SimpleXMLElement;
use UnexpectedValueException;
use Matraux\XmlORM\Xml\XmlNamespace;
use Matraux\XmlORM\Exception\XmlParsingException;

final class SimpleXmlExplorer extends XmlExplorer
{

	protected function __construct(protected SimpleXMLElement $xml)
	{
	}

	public function offsetExists(mixed $offset): bool
	{
		if (!is_int($offset)) {
			throw new UnexpectedValueException(sprintf('Expected offset type "positive-int", "%s" given.', gettype($offset)));
		} elseif ($offset < 0) {
			throw new UnexpectedValueException('Expected offset type "positive-int".');
		}

		return isset($this->xml[$offset]);
	}

	public function offsetGet(mixed $offset): static
	{
		if (!isset($this->xml[$offset])) {
			throw new XmlParsingException(sprintf('Element with index %u not found.', $offset));
		}

		return new static($this->xml[$offset]);
	}

	/**
	 * @var int<0,max>
	 */
	protected int $countCache;

	public function count(): int
	{
		return $this->countCache ??= $this->xml->count();
	}

	public static function fromFile(string $file): static
	{
		libxml_use_internal_errors(true);
		$xml = simplexml_load_file($file);
		$error = libxml_get_last_error();
		libxml_clear_errors();

		if ($xml === false || $error !== false) {
			throw new XmlParsingException(sprintf('Invalid XML: %s', $error->message ?? 'Unknown error'));
		}

		return new static($xml);
	}

	public static function fromString(string $data): static
	{
		libxml_use_internal_errors(true);
		$xml = simplexml_load_string($data);
		$error = libxml_get_last_error();
		libxml_clear_errors();

		if ($xml === false || $error !== false) {
			throw new XmlParsingException(sprintf('Invalid XML: %s', $error->message ?? 'Unknown error'));
		}

		return new static($xml);
	}

	public function getValue(): string
	{
		return (string) $this->xml;
	}

	public function getAttribute(string $name): ?string
	{
		$attribute = $this->xml->attributes()->{$name};

		return $attribute instanceof SimpleXMLElement ? (string) $attribute : null;
	}

	public function getIterator(): Traversable
	{
		$index = 0;
		foreach ($this->xml as $xml) {
			yield $index++ => new static($xml);
		}
	}

	public function withIndex(string $index, ?XmlNamespace $namespace = null): static
	{
		$children = $this->xml->children($namespace?->getSource())->{$index};
		if (!$children instanceof SimpleXMLElement || !isset($children[0])) {
			throw new XmlParsingException(sprintf('Invalid XML: Element with index "%s" not found.', $index));
		}

		return new static($children);
	}

}
