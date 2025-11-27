<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Xml;

use Countable;
use ArrayAccess;
use Traversable;
use LogicException;
use IteratorAggregate;
use Matraux\XmlORM\Xml\XmlNamespace;

/**
 * @implements IteratorAggregate<int|string,static>
 * @implements ArrayAccess<int|string,static>
 */
abstract class Explorer implements IteratorAggregate, Countable, ArrayAccess
{

	abstract public ?string $value
	{
		get;
	}

	/**
	 * @return Traversable<int|string,static>
	 */
	abstract public function getIterator(): Traversable;

	abstract public function withIndex(string $index): static;

	abstract public function withNamespace(?XmlNamespace $namespace): static;

	abstract public function attribute(string $name): ?string;

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
