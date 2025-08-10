<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Metadata;

use Matraux\XmlORM\Xml\XmlAttribute;
use Matraux\XmlORM\Xml\XmlElement;
use Matraux\XmlORM\Xml\XmlNamespace;
use Nette\Utils\Type;
use ReflectionAttribute;
use ReflectionProperty;

final readonly class PropertyMetadata
{

	public string $name;

	public ?string $attribute;

	public string $index;

	public ?XmlNamespace $namespace;

	public ?string $type;

	protected function __construct(ReflectionProperty $reflection)
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
	}

	public static function create(ReflectionProperty $reflection): static
	{
		return new static($reflection);
	}

}
