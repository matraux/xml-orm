<?php declare(strict_types = 1);

namespace Matraux\XmlOrm\Metadata;

use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Xml\XmlElement;
use Matraux\XmlOrm\Xml\XmlNamespace;
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
	public function __construct(ReflectionClass $reflection)
	{
		$attributes = $reflection->getAttributes(XmlElement::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->name = array_shift($attributes)?->newInstance()->name ?? $reflection->name;

		$attributes = $reflection->getAttributes(XmlNamespace::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->namespace = array_shift($attributes)?->newInstance();

		$properties = [];
		foreach ($reflection->getProperties() as $property) {
			$properties[] = new ReflectionClass(PropertyMetadata::class)
				->newLazyGhost(function(PropertyMetadata $propertyMetadata) use($property): void {
					$propertyMetadata->__construct($property);
				});
		}

		$this->properties = $properties;
	}

}
