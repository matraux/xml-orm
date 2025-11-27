<?php declare(strict_types = 1);

namespace Matraux\XmlOrmTest\Dto\Entity;

use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Xml\XmlElement;
use Matraux\XmlOrmTest\Dto\Entity\Enum\Active;
use Matraux\XmlOrmTest\Dto\Xml\GeneralXmlNamespace;

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

}
