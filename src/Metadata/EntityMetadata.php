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

	/**
	 * @param ReflectionClass<Entity> $reflection
	 */
	protected function __construct(protected ReflectionClass $reflection)
	{
		$attributes = $this->reflection->getAttributes(XmlElement::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->name = array_shift($attributes)?->newInstance()->name ?? $this->reflection->name;

		$attributes = $this->reflection->getAttributes(XmlNamespace::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->namespace = array_shift($attributes)?->newInstance();
	}

	/**
	 * @param ReflectionClass<Entity> $reflection
	 */
	public static function create(ReflectionClass $reflection): static
	{
		return new static($reflection);
	}

}
