**[Back](../Readme.md)**

# Definitions

## XML namespace
```php
use Attribute;
use Matraux\XmlOrm\Xml\XmlNamespace;

#[Attribute]
final class GeneralXmlNamespace extends XmlNamespace
{

	public static function getName(): string
	{
		return 'gen';
	}

	public static function getSource(): string
	{
		return 'http://www.w3.org/2001/XMLSchema';
	}

}
```

## Entity
```php
use DateTime;
use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Xml\XmlElement;
use Matraux\XmlOrm\Xml\XmlAttribute;

#[GeneralXmlNamespace] // create XML element with namespace "gen"
#[XmlElement('item')] // create XML element with name "item"
final class ItemEntity extends Entity
{

	#[GeneralXmlNamespace] // search XML element with namespace "gen"
	#[XmlElement('ID')] // search XML element with different name "ID"
	public int $id;

	public string $name; // search XML element with different name "name"

	#[Property('TIME')] // search XML element with different name "TIME"
	#[DateTimeCodec] // decode/encode value via DateTimeCodec
	public ?DateTime $time;

	#[XmlAttribute('ATTR')] // search XML element attribute with name "ATTR"
	public string $attr;

}
```

## Collection
```php
use Matraux\XmlOrm\Collection\Collection;

/**
 * @extends Collection<ItemEntity>
 */
final class ItemCollection extends Collection
{

	protected static function getEntityClass(): string
	{
		return ItemEntity::class;
	}

}
```

## Codec
```php
use Attribute;
use DateTime;
use Matraux\XmlOrm\Codec\Codec;
use Matraux\XmlOrm\Xml\Explorer;
use Matraux\XmlOrm\Metadata\PropertyMetadata;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class DateTimeCodec implements Codec
{

	protected const string Format = 'd.m.Y H:i:s.u';

	public function encode(mixed $value, PropertyMetadata $property): ?string
	{
		return $value instanceof DateTime ? $value->format(self::Format) : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $property): ?DateTime
	{
		$value = $explorer[$property->index];
		if (!is_string($value)) {
			return null;
		}

		return DateTime::createFromFormat(self::Format, $value) ?: null;
	}

}
```