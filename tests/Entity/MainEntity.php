<?php declare(strict_types = 1);

namespace Matraux\XmlORMTest\Entity;

use Matraux\XmlORM\Entity\Entity;
use Matraux\XmlORM\Xml\XmlAttribute;
use Matraux\XmlORM\Xml\XmlElement;
use Matraux\XmlORMTest\Collection\ItemCollection;
use Matraux\XmlORMTest\Xml\GeneralXmlNamespace;

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
	#[XmlElement('item')]
	public ItemCollection $items;

}
