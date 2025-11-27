<?php declare(strict_types = 1);

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
use Nette\Utils\Type;
use PropertyHookType;
use ReflectionAttribute;
use ReflectionProperty;

final readonly class PropertyMetadata
{

	public string $name;

	public ?string $attribute;

	public string $index;

	public ?XmlNamespace $namespace;

	public ?string $type;

	public ?Codec $codec;

	protected function __construct(protected readonly ReflectionProperty $reflection)
	{
		$this->name = $reflection->name;

		$attributes = $reflection->getAttributes(XmlAttribute::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->attribute = array_shift($attributes)?->newInstance()->name;

		$attributes = $reflection->getAttributes(XmlElement::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->index = array_shift($attributes)?->newInstance()->name ?? $reflection->name;

		$attributes = $reflection->getAttributes(XmlNamespace::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->namespace = array_shift($attributes)?->newInstance();

		$type = Type::fromReflection($reflection);
		$this->type = $type?->getSingleName();

		$attributes = $reflection->getAttributes(Codec::class, ReflectionAttribute::IS_INSTANCEOF);
		if (count($attributes) > 1) {
			throw new CodecException(sprintf('Property %s::$%s expects single %s attribute, multiple given.', $reflection->getDeclaringClass()->getName(), $this->name, Codec::class));
		}

		if ($codec = array_shift($attributes)?->newInstance()) {
			$this->codec = $codec;
		} elseif ($this->type && is_subclass_of($this->type, Entity::class)) {
			$this->codec = new EntityCodec();
		} elseif ($this->type && is_subclass_of($this->type, Collection::class)) {
			$this->codec = new CollectionCodec();
		} elseif ($this->type && is_subclass_of($this->type, BackedEnum::class)) {
			$this->codec = new BackedEnumCodec();
		} elseif ($this->type === 'bool') {
			$this->codec = new BoolCodec();
		} elseif ($this->type === 'int') {
			$this->codec = new IntCodec();
		} elseif ($this->type === 'float') {
			$this->codec = new FloatCodec();
		} else {
			$this->codec = null;
		}
	}

	public static function create(ReflectionProperty $reflection): static
	{
		return new static($reflection);
	}

	public function isInitialized(Entity $entity): bool
	{
		return $this->reflection->isInitialized($entity) || $this->reflection->getHook(PropertyHookType::Get);
	}

}
