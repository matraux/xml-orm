<?php declare(strict_types = 1);

namespace Matraux\XmlOrmTest\Dto\Entity;

use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Xml\XmlAttribute;
use Matraux\XmlOrm\Xml\XmlElement;
use Matraux\XmlOrmTest\Dto\Collection\ItemCollection;
use Matraux\XmlOrmTest\Dto\Xml\GeneralXmlNamespace;

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
