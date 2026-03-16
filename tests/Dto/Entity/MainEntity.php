<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Test\Dto\Entity;

use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Xml\XmlAttribute;
use Matraux\XmlOrm\Xml\XmlElement;
use Matraux\XmlOrm\Test\Dto\Collection\ItemCollection;
use Matraux\XmlOrm\Test\Dto\Xml\GeneralXmlNamespace;

#[GeneralXmlNamespace]
#[XmlElement('main')]
final class MainEntity extends Entity
{
	#[GeneralXmlNamespace]
	#[XmlElement('customName')]
	public string $name;

	#[GeneralXmlNamespace]
	#[XmlElement('customSurname')]
	public string $surname;

	#[XmlAttribute('program-version')]
	public string $version;

	#[XmlAttribute('custom-note')]
	public string $note;

	#[GeneralXmlNamespace]
	#[XmlAttribute('target')]
	public string $target;

	#[GeneralXmlNamespace]
	#[XmlElement('item')]
	public ItemCollection $items;
}
