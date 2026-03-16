<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Xml;

use Matraux\XmlOrm\Exception\XmlParsingException;
use OutOfRangeException;
use SimpleXMLElement;
use Traversable;
use UnexpectedValueException;

final class SimpleExplorer extends Explorer
{
	public ?string $value {
		get => (string) $this->xml;
	}

	/** @var int<0,max> */
	protected int $countCache;

	protected function __construct(protected SimpleXMLElement $xml, protected ?XmlNamespace $namespace = null) {}

	public static function fromFile(string $file): static
	{
		libxml_clear_errors();
		libxml_use_internal_errors(true);
		$xml = simplexml_load_file($file);
		$error = libxml_get_last_error();

		if ($xml === false || $error !== false) {
			throw new XmlParsingException(sprintf('Invalid XML: %s', $error->message ?? 'Unknown error'));
		}

		return new static($xml);
	}

	public static function fromString(string $data): static
	{
		libxml_clear_errors();
		libxml_use_internal_errors(true);
		$xml = simplexml_load_string($data);
		$error = libxml_get_last_error();

		if ($xml === false || $error !== false) {
			throw new XmlParsingException(sprintf('Invalid XML: %s', $error->message ?? 'Unknown error'));
		}

		return new static($xml);
	}

	public function withNamespace(?XmlNamespace $namespace): static
	{
		$explorer = clone $this;
		$explorer->namespace = $namespace;

		return $explorer;
	}

	public function offsetExists(mixed $offset): bool
	{
		if (!is_int($offset) && !is_string($offset)) {
			throw new UnexpectedValueException(sprintf('Expected offset type "int|string", "%s" given.', gettype($offset)));
		} elseif (is_int($offset) && $offset < 0) {
			throw new UnexpectedValueException(sprintf('Expected offset value "positive-int", "%s" given.', $offset));
		}

		return is_int($offset)
			? isset($this->xml[$offset])
			: isset($this->xml->children($this->namespace?->getSource())->{$offset});
	}

	public function offsetGet(mixed $offset): static
	{
		if (!isset($this[$offset])) {
			throw new OutOfRangeException(sprintf('Element with index %s not found.', $offset));
		}

		return $this->withIndex($offset);
	}

	public function count(): int
	{
		return $this->countCache ??= $this->xml->count();
	}

	public function attribute(string $name): ?string
	{
		$attribute = $this->xml->attributes()->{$name};

		return $attribute instanceof SimpleXMLElement ? (string) $attribute : null;
	}

	public function getIterator(): Traversable
	{
		$index = 0;
		foreach ($this->xml as $xml) {
			yield $index++ => new static($xml, $this->namespace);
		}
	}

	public function withIndex(int|string $index): static
	{
		if (is_string($index)) {
			$children = $this->xml->children($this->namespace?->getSource())->{$index};
			if (!$children instanceof SimpleXMLElement || !isset($children[0])) {
				throw new XmlParsingException(sprintf('Invalid XML: Element with index "%s" not found.', $index));
			}
		} else {
			$children = $this->xml[$index];
			if (!$children instanceof SimpleXMLElement) {
				throw new XmlParsingException(sprintf('Invalid XML: Element with index "%s" not found.', $index));
			}
		}

		return new static($children, $this->namespace);
	}
}
