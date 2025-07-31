<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Xml;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use LogicException;
use Traversable;

/**
 * @implements IteratorAggregate<int|string,static>
 * @implements ArrayAccess<int|string,static>
 */
abstract class XmlExplorer implements IteratorAggregate, Countable, ArrayAccess
{

	/**
	 * @return Traversable<int|string,static>
	 */
	abstract public function getIterator(): Traversable;

	/**
	 * Returns child explorer by element name and optional namespace.
	 */
	abstract public function withIndex(string $index, ?XmlNamespace $namespace = null): static;

	/**
	 * Returns the string content of the current element.
	 */
	abstract public function getValue(): ?string;

	/**
	 * Returns the value of an attribute or null if not found.
	 */
	abstract public function getAttribute(string $name): ?string;

	/**
	 * @return int<0,max>
	 */
	abstract public function count(): int;

	abstract public function offsetExists(mixed $offset): bool;

	abstract public function offsetGet(mixed $offset): static;

	final public function offsetSet(mixed $offset, mixed $value): void
	{
		throw new LogicException('Explorer is read-only.');
	}

	final public function offsetUnset(mixed $offset): void
	{
		throw new LogicException('Explorer is read-only.');
	}

}
