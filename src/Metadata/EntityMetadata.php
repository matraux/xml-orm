<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Metadata;

use Matraux\XmlORM\Entity\Entity;
use Matraux\XmlORM\Xml\XmlElement;
use Matraux\XmlORM\Xml\XmlNamespace;
use ReflectionAttribute;
use ReflectionClass;

final readonly class EntityMetadata
{

	public string $name;

	public ?XmlNamespace $namespace;

	/** @var array<PropertyMetadata> */
	public array $properties;

	/**
	 * @param ReflectionClass<Entity> $reflection
	 */
	protected function __construct(ReflectionClass $reflection)
	{
		$attributes = $reflection->getAttributes(XmlElement::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->name = array_shift($attributes)?->newInstance()->name ?? $reflection->name;

		$attributes = $reflection->getAttributes(XmlNamespace::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->namespace = array_shift($attributes)?->newInstance();

		$properties = [];
		foreach ($reflection->getProperties() as $property) {
			$properties[] = PropertyMetadata::create($property);
		}

		$this->properties = $properties;
	}

	/**
	 * @param ReflectionClass<Entity> $reflection
	 */
	public static function create(ReflectionClass $reflection): static
	{
		return new static($reflection);
	}

}
