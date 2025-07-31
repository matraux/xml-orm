<?php declare(strict_types = 1);

namespace Matraux\XmlORM\Entity;

use DOMNode;
use Throwable;
use Stringable;
use DOMDocument;
use RuntimeException;
use ReflectionProperty;
use ReflectionAttribute;
use Matraux\XmlORM\Xml\XmlNamespace;
use Matraux\XmlORM\Xml\XmlExplorer;
use Matraux\XmlORM\Collection\Collection;
use Matraux\XmlORM\Metadata\PropertyMetadataFactory;
use ReflectionClass;

abstract class Entity implements Stringable
{

	protected const string Encoding = 'utf-8';

	final protected function __construct()
	{
	}

	final static function fromExplorer(XmlExplorer $explorer): static
	{
		$entity = new static();

		$properties = PropertyMetadataFactory::create(static::class);
		foreach($properties as $property) {
			if($property->attribute) {
				$entity->{$property->name} = $explorer->getAttribute($property->attribute);

				continue;
			}

			try {
				$subExplorer = $explorer->withIndex($property->index, $property->namespace);
			} catch (Throwable) {
				continue;
			}

			if($type = $property->type) {
				if (is_subclass_of($type, self::class)) {
					/** @var class-string<static> $type */
					$entity->{$property->name} = $type::fromExplorer($subExplorer);
					continue;
				}

				if (is_subclass_of($type, Collection::class)) {
					/** @var class-string<Collection<static>> $type */
					$entity->{$property->name} = $type::create($subExplorer);
					continue;
				}
			}

			$value = $subExplorer->getValue();
			if($property->type) {
				settype($value, $property->type);
			}

			$entity->{$property->name} = $value;
		}

		return $entity;
	}

	/**
	 * @throws RuntimeException If can not create XML
	 */
	public function asXml(?DOMNode $document = null): string
	{
		// phpcs:ignore
		$document ??= new DOMDocument('1.0', static::Encoding);

		$owner = $document instanceof DOMDocument ? $document : $document->ownerDocument;
		if (!$owner) {
			throw new RuntimeException('Invalid DOM document owner.');
		}

		$element = $owner->createElement((string) $this);
		$document->appendChild($element);

		$reflection = new ReflectionClass(static::class);
		$attributes = $reflection->getAttributes(XmlNamespace::class, ReflectionAttribute::IS_INSTANCEOF);
		if ($xmlns = array_shift($attributes)?->newInstance()) {
			if (!$owner->documentElement) {
				throw new RuntimeException('Invalid DOM document element.');
			}

			$owner->documentElement->setAttribute('xmlns:' . $xmlns::getName(), $xmlns::getSource());
		}

		$properties = PropertyMetadataFactory::create(static::class);
		foreach ($properties as $property) {
			if (!new ReflectionProperty(static::class, $property->name)->isInitialized($this)) {
				continue;
			}

			$value = $this->{$property->name};

			if ($value instanceof self) {
				$value->asXml($element);
			} elseif ($value instanceof Collection) {
				foreach ($value as $entity) {
					$entity->asXml($element);
				}
			} elseif (is_scalar($value) || $value === null) {

				if ($property->attribute) {
					$element->setAttribute($property->attribute, (string) $value);

					continue;
				}

				$name = $property->index;

				if ($xmlns = $property->namespace) {
					$name = $xmlns::getName() . ':' . $name;
					$owner->documentElement?->setAttribute('xmlns:' . $xmlns::getName(), $xmlns::getSource());
				}

				if (is_bool($value)) {
					$elementProperty = $owner->createElement($name, $value ? 'true' : 'false');
				} else {
					$elementProperty = $owner->createElement($name, (string) $value);
				}

				if($value !== null){
					$element->appendChild($elementProperty);
				}
			}
		}

		if (!$document instanceof DOMDocument) {
			return '';
		} elseif (!$xml = $document->saveXML()) {
			throw new RuntimeException('Error during generating XML.');
		}

		return $xml;
	}

	final public static function create(): static
	{
		return new static();
	}

	final public function __toString(): string
	{
		return $this->asXml();
	}

}
