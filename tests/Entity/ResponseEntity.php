<?php declare(strict_types = 1);

namespace Matraux\XmlORMTest\Entity;

use Matraux\XmlORM\Entity\Entity;
use Matraux\XmlORM\Xml\XmlElement;
use Matraux\XmlORMTest\Xml\GeneralXmlNamespace;

#[GeneralXmlNamespace]
#[XmlElement('response')]
final class ResponseEntity extends Entity
{

	#[GeneralXmlNamespace]
	#[XmlElement('data')]
	public DataEntity $data;

}
