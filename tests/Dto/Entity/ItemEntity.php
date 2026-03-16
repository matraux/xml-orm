<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Test\Dto\Entity;

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
