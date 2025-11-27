<?php declare(strict_types = 1);

namespace Matraux\XmlOrm\Entity;

use DOMDocument;
use DOMNode;
use Matraux\XmlOrm\Collection\Collection;
use Matraux\XmlOrm\Metadata\EntityMetadataFactory;
use Matraux\XmlOrm\Xml\Explorer;
use RuntimeException;
use Stringable;

abstract class Entity implements Stringable
{

	protected const string Encoding = 'utf-8';

	final protected function __construct()
	{
	}

	final static function fromExplorer(Explorer $explorer): static
	{
		$entity = new static();

		$entityMetadata = EntityMetadataFactory::create(static::class);
		foreach ($entityMetadata->properties as $property) {
			if ($property->attribute) {
				$entity->{$property->name} = $explorer->attribute($property->attribute);

				continue;
			}

			if (!isset($explorer->withNamespace($property->namespace)[$property->index])) {
				continue;
			}

			if ($property->codec) {
				$entity->{$property->name} = $property->codec->decode($explorer, $property);

				continue;
			}

			$entity->{$property->name} = $explorer->withNamespace($property->namespace)->withIndex($property->index)->value;
		}

		return $entity;
	}

	final public static function create(): static
	{
		return new static();
	}

	/**
	 * @throws RuntimeException If can not create XML
	 */
	public function asXml(?DOMNode $document = null): string
	{
		// phpcs:ignore
		$document ??= new DOMDocument('1.0', static::Encoding);

		$entityMetadata = EntityMetadataFactory::create(static::class);

		$owner = $document instanceof DOMDocument ? $document : $document->ownerDocument;
		if (!$owner) {
			throw new RuntimeException('Invalid DOM document owner.');
		}

		$xmlns = $entityMetadata->namespace;
		$name = $xmlns ? $xmlns::getName() . ':' . $entityMetadata->name : $entityMetadata->name;

		$element = $owner->createElement($name);
		$document->appendChild($element);

		if ($xmlns) {
			if (!$owner->documentElement) {
				throw new RuntimeException('Invalid DOM document element.');
			}

			$owner->documentElement->setAttribute('xmlns:' . $xmlns::getName(), $xmlns::getSource());
		}

		foreach ($entityMetadata->properties as $property) {
			if (!$property->isInitialized($this)) {
				continue;
			}

			$value = $property->codec ? $property->codec->encode($this->{$property->name}, $property) : $this->{$property->name};

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

				$elementProperty = $owner->createElement($name, (string) $value);

				if ($value !== null) {
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

	final public function __toString(): string
	{
		return $this->asXml();
	}

}
