<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Metadata;

use ReflectionClass;
use ReflectionAttribute;
use Matraux\XmlORM\Xml\XmlElement;
use Matraux\XmlORM\Xml\XmlNamespace;

final readonly class EntityMetadata
{

	public string $name;

	public ?XmlNamespace $namespace;

	public string $fullName;

	protected function __construct(protected ReflectionClass $reflection)
	{
		$attributes = $this->reflection->getAttributes(XmlElement::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->name = array_shift($attributes)?->newInstance()->name ?? $this->reflection->name;

		$attributes = $this->reflection->getAttributes(XmlNamespace::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->namespace = array_shift($attributes)?->newInstance();
	}

	public static function create(ReflectionClass $reflection): static
	{
		return new static($reflection);
	}

}