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