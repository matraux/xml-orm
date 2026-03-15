<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Metadata;

use BackedEnum;
use Matraux\JsonOrm\Exception\CodecException;
use Matraux\XmlOrm\Codec\BackedEnumCodec;
use Matraux\XmlOrm\Codec\BoolCodec;
use Matraux\XmlOrm\Codec\Codec;
use Matraux\XmlOrm\Codec\CollectionCodec;
use Matraux\XmlOrm\Codec\EntityCodec;
use Matraux\XmlOrm\Codec\FloatCodec;
use Matraux\XmlOrm\Codec\IntCodec;
use Matraux\XmlOrm\Collection\Collection;
use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Xml\XmlAttribute;
use Matraux\XmlOrm\Xml\XmlElement;
use Matraux\XmlOrm\Xml\XmlNamespace;
use PropertyHookType;
use ReflectionAttribute;
use ReflectionNamedType;
use ReflectionProperty;
use RuntimeException;

final readonly class PropertyMetadata
{
	public string $name;

	public string $class;

	public ?string $attribute;

	public string $index;

	public ?XmlNamespace $namespace;

	public ?Codec $codec;

	public function __construct(public ReflectionProperty $reflection)
	{
		$this->name = $this->reflection->name;
		$this->class = $this->reflection->class;
		$this->attribute = $this->resolveAttribute();
		$this->index = $this->resolveIndex();
		$this->namespace = $this->resolveNamespace();
		$this->codec = $this->resolveCodec();
	}

	public function isInitialized(Entity $entity): bool
	{
		return $this->reflection->isInitialized($entity) || $this->reflection->getHook(PropertyHookType::Get);
	}

	protected function resolveNamespace(): ?XmlNamespace
	{
		$attributes = $this->reflection->getAttributes(XmlNamespace::class, ReflectionAttribute::IS_INSTANCEOF);
		if (count($attributes) > 1) {
			throw new RuntimeException(sprintf('%s::$%s expects single %s attribute, multiple given.', $this->reflection->class, $this->reflection->name, XmlNamespace::class));
		}

		return array_shift($attributes)?->newInstance();
	}

	protected function resolveAttribute(): ?string
	{
		$attributes = $this->reflection->getAttributes(XmlAttribute::class, ReflectionAttribute::IS_INSTANCEOF);
		if (count($attributes) > 1) {
			throw new RuntimeException(sprintf('%s::$%s expects single %s attribute, multiple given.', $this->reflection->class, $this->reflection->name, XmlAttribute::class));
		}

		return array_shift($attributes)?->newInstance()->name;
	}

	protected function resolveIndex(): string
	{
		$attributes = $this->reflection->getAttributes(XmlElement::class, ReflectionAttribute::IS_INSTANCEOF);
		if (count($attributes) > 1) {
			throw new RuntimeException(sprintf('%s::$%s expects single %s attribute, multiple given.', $this->reflection->class, $this->reflection->name, XmlElement::class));
		}

		return array_shift($attributes)?->newInstance()->name ?? $this->reflection->name;
	}

	protected function resolveCodec(): ?Codec
	{
		$attributes = $this->reflection->getAttributes(Codec::class, ReflectionAttribute::IS_INSTANCEOF);
		if (count($attributes) > 1) {
			throw new CodecException(sprintf('%s::$%s expects single %s attribute, multiple given.', $this->reflection->class, $this->reflection->name, Codec::class));
		}

		if ($codec = array_shift($attributes)?->newInstance()) {
			return $codec;
		}

		$type = $this->reflection->getType();
		if (!$type instanceof ReflectionNamedType) {
			return null;
		}

		$type = match (strtolower($type->getName())) {
			'parent' => ($parent = $this->reflection->getDeclaringClass()->getParentClass()) ? $parent->name : throw new RuntimeException(sprintf('Unresolvable type parent for %s::$%s.', $this->reflection->class, $this->reflection->name)),
			'self' => $this->reflection->class,
			default => $type->getName(),
		};

		return match (true) {
			is_subclass_of($type, Entity::class) => new EntityCodec($type),
			is_subclass_of($type, Collection::class) => new CollectionCodec($type),
			is_subclass_of($type, BackedEnum::class) => new BackedEnumCodec($type),
			$type === 'int' => new IntCodec(),
			$type === 'float' => new FloatCodec(),
			$type === 'null' => new BoolCodec(),
			default => null,
		};
	}
}
