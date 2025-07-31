<?php declare(strict_types = 1);

namespace Matraux\XmlORMTest\Entity;

use Matraux\XmlORM\Entity\Entity;
use Matraux\XmlORM\Xml\XmlElement;
use Matraux\XmlORMTest\Xml\GeneralXmlNamespace;

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
	public string $active;

	#[GeneralXmlNamespace]
	#[XmlElement('md5')]
	public string $md5;

	#[GeneralXmlNamespace]
	#[XmlElement('hash')]
	public string $hash;

}
