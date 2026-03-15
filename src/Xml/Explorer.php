<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Xml;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use LogicException;

/**
 * @implements IteratorAggregate<int|string,static>
 * @implements ArrayAccess<int|string,static>
 */
abstract class Explorer implements IteratorAggregate, Countable, ArrayAccess
{
	abstract public ?string $value {
		get;
	}

	abstract public function withIndex(string $index): static;

	abstract public function withNamespace(?XmlNamespace $namespace): static;

	abstract public function attribute(string $name): ?string;

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
