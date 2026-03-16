**[Back](../Readme.md)**

# Definitions

## XML namespace
```php
use Attribute;
use Matraux\XmlOrm\Xml\XmlNamespace;

#[Attribute]
final class GeneralXmlNamespace extends XmlNamespace
{
	public function getName(): string
	{
		return 'gen';
	}

	public function getSource(): string
	{
		return 'http://www.w3.org/2001/XMLSchema';
	}
}
```

## Entity
```php
use DateTime;
use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Test\Dto\Codec\DateTimeCodec;
use Matraux\XmlOrm\Test\Dto\Enum\Active;
use Matraux\XmlOrm\Test\Dto\Xml\GeneralXmlNamespace;
use Matraux\XmlOrm\Xml\XmlElement;

#[GeneralXmlNamespace]
#[XmlElement('item')]
final class ItemEntity extends Entity
{
	#[GeneralXmlNamespace]
	#[XmlElement('ID')]
	public int $id;

	#[GeneralXmlNamespace]
	#[XmlElement('Name')]
	public string $name;

	#[GeneralXmlNamespace]
	#[XmlElement('Active')]
	public Active $active;

	#[GeneralXmlNamespace]
	#[XmlElement('md5')]
	public string $md5;

	#[GeneralXmlNamespace]
	#[XmlElement('hash')]
	public string $hash;

	#[XmlElement('created')]
	#[DateTimeCodec]
	public DateTime $created;
}
```

## Collection
```php
use Matraux\XmlOrm\Collection\Collection;
use Matraux\XmlOrm\Test\Dto\Entity\ItemEntity;

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
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class DateTimeCodec implements Codec
{
	protected const string Format = 'd.m.Y H:i:s';

	public function encode(mixed $value, PropertyMetadata $metadata): ?string
	{
		return $value instanceof DateTime ? $value->format(static::Format) : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $metadata): ?DateTime
	{
		$value = $explorer->withNamespace($metadata->namespace)->withIndex($metadata->index)->value;

		return $value !== null ? new DateTime($value) : null;
	}
}
```