<?php declare(strict_types = 1);

namespace Matraux\XmlOrmTest\Dto\Entity;

use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Xml\XmlElement;
use Matraux\XmlOrmTest\Dto\Xml\GeneralXmlNamespace;

#[GeneralXmlNamespace]
#[XmlElement('response')]
final class ResponseEntity extends Entity
{

	#[GeneralXmlNamespace]
	#[XmlElement('data')]
	public DataEntity $data;

}
