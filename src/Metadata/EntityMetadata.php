<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Metadata;

use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Xml\XmlElement;
use Matraux\XmlOrm\Xml\XmlNamespace;
use ReflectionAttribute;
use ReflectionClass;
use RuntimeException;

final readonly class EntityMetadata
{
	public string $name;

	public ?XmlNamespace $namespace;

	/** @var array<PropertyMetadata> */
	public array $properties;

	/**
	 * @param ReflectionClass<Entity> $reflection
	 */
	public function __construct(protected ReflectionClass $reflection)
	{
		$this->name = $this->resolveAttribute(XmlElement::class)->name ?? $this->reflection->name;
		$this->namespace = $this->resolveAttribute(XmlNamespace::class);

		$properties = [];
		foreach ($this->reflection->getProperties() as $property) {
			$properties[] = new ReflectionClass(PropertyMetadata::class)
				->newLazyGhost(function (PropertyMetadata $propertyMetadata) use ($property): void {
					$propertyMetadata->__construct($property);
				});
		}

		$this->properties = $properties;
	}

	/**
	 * @template T of object
	 *
	 * @param class-string<T> $class
	 *
	 * @return null|T
	 */
	protected function resolveAttribute(string $class): ?object
	{
		$attributes = $this->reflection->getAttributes($class, ReflectionAttribute::IS_INSTANCEOF);
		if (count($attributes) > 1) {
			throw new RuntimeException(sprintf('%s expects single %s attribute, multiple given.', $this->reflection->name, $class));
		}

		return array_shift($attributes)?->newInstance();
	}
}
