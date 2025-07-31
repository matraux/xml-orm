**[Back](../Readme.md)**

# Definitions

## XML namespace
```php
use Attribute;
use Matraux\XmlORM\Xml\XmlNamespace;

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
use Matraux\XmlORM\Entity\Entity;
use Matraux\XmlORM\Xml\XmlElement;
use Matraux\XmlORM\Xml\XmlAttribute;

#[GeneralXmlNamespace] // create XML element with namespace "gen"
#[XmlElement('item')] // create XML element with name "item"
final class ItemEntity extends Entity
{

	#[GeneralXmlNamespace] // search XML element with namespace "gen"
	#[XmlElement('ID')] // search XML element with different name "ID"
	public int $id;

	#[XmlAttribute('ATTR')] // search XML element attribute with name "ATTR"
	public string $attr;

}
```

## Collection
```php
use Matraux\XmlORM\Collection\Collection;

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