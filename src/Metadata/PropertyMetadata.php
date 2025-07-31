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

	protected function __construct(protected readonly ReflectionProperty $reflection)
	{
		$this->name = $this->reflection->name;

		$attributes = $this->reflection->getAttributes(XmlAttribute::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->attribute = array_shift($attributes)?->newInstance()->name;

		$attributes = $this->reflection->getAttributes(XmlElement::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->index = array_shift($attributes)?->newInstance()->name ?? $this->reflection->name;

		$attributes = $this->reflection->getAttributes(XmlNamespace::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->namespace = array_shift($attributes)?->newInstance();

		$type = Type::fromReflection($this->reflection);
		$this->type = $type?->getSingleName();
	}

	public static function create(ReflectionProperty $reflection): static
	{
		return new static($reflection);
	}

}
