<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Matraux\XmlORM\Entity\Entity;
use Matraux\XmlORM\Exception\ReadonlyAccessException;
use Matraux\XmlORM\Xml\XmlExplorer;
use OutOfRangeException;
use RuntimeException;
use Traversable;
use UnexpectedValueException;

/**
 * @template TEntity of Entity
 * @implements IteratorAggregate<int,TEntity>
 * @implements ArrayAccess<int,TEntity>
 */
abstract class Collection implements ArrayAccess, Countable, IteratorAggregate
{

	/** @var array<int,TEntity> */
	final protected array $entities = [];

	/** @var int<0,max> */
	final protected int $countCache;

	final protected function __construct(protected ?XmlExplorer $explorer = null)
	{
	}

	final public static function create(?XmlExplorer $explorer = null): static
	{
		/** @var static<TEntity> */
		return new static($explorer);
	}

	/**
	 * @return int<0,max>
	 */
	final public function count(): int
	{
		/** @var int<0,max> */
		return $this->countCache ??= count($this->explorer ?? $this->entities);
	}

	final public function offsetExists(mixed $offset): bool
	{
		if (!is_int($offset)) {
			throw new UnexpectedValueException(sprintf('Expected offset type "positive-int", "%s" given.', gettype($offset)));
		} elseif ($offset < 0) {
			throw new UnexpectedValueException('Expected offset type "positive-int".');
		}

		return $this->explorer ? isset($this->explorer[$offset]) : array_key_exists($offset, $this->entities);
	}

	/**
	 * @return TEntity
	 */
	final public function offsetGet(mixed $offset): Entity
	{
		if (!$this->offsetExists($offset)) {
			throw new OutOfRangeException(sprintf('Offset "%s" is out of range.', $offset));
		}

		return $this->explorer ? static::getEntityClass()::fromExplorer($this->explorer[$offset]) : $this->entities[$offset];
	}

	final public function offsetSet(mixed $offset, mixed $value): void
	{
		$this->assertWritable();

		if (!is_int($offset)) {
			throw new UnexpectedValueException(sprintf('Expected offset type "positive-int", "%s" given.', gettype($offset)));
		} elseif ($offset < 0) {
			throw new UnexpectedValueException('Expected offset type "positive-int".');
		} elseif (!$value instanceof Entity || $value::class !== static::getEntityClass()) {
			throw new RuntimeException(sprintf('Expected entity type "%s", "%s" given', static::getEntityClass(), gettype($value)));
		}

		unset($this->countCache);
		$this->entities[$offset] = $value;
	}

	final public function offsetUnset(mixed $offset): void
	{
		$this->assertWritable();

		if (!is_int($offset)) {
			throw new UnexpectedValueException(sprintf('Expected offset type "positive-int", "%s" given.', gettype($offset)));
		} elseif ($offset < 0 || !array_key_exists($offset, $this->entities)) {
			throw new OutOfRangeException(sprintf('Offset "%s" is out of range.', $offset));
		}

		unset($this->countCache);
		unset($this->entities[$offset]);
	}

	/**
	 * @return Traversable<int,TEntity>
	 */
	public function getIterator(): Traversable
	{
		if ($this->explorer) {
			foreach ($this->explorer as $index => $data) {
				yield (int) $index => static::getEntityClass()::fromExplorer($data);
			}
		} else {
			foreach ($this->entities as $index => $entity) {
				yield $index => $entity;
			}
		}
	}

	/**
	 * @return TEntity
	 */
	public function createEntity(): Entity
	{
		unset($this->countCache);

		return $this->entities[] = static::getEntityClass()::create();
	}

	/**
	 * @return class-string<TEntity>
	 */
	abstract protected static function getEntityClass(): string;

	final protected function assertWritable(): void
	{
		if ($this->explorer !== null) {
			throw new ReadonlyAccessException('Collection is readonly.');
		}
	}

}
