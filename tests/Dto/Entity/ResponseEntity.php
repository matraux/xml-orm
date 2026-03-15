<?php declare(strict_types = 1);

namespace Matraux\XmlOrm\Test\Dto\Entity;

use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Xml\XmlElement;
use Matraux\XmlOrm\Test\Dto\Xml\GeneralXmlNamespace;

#[GeneralXmlNamespace]
#[XmlElement('response')]
final class ResponseEntity extends Entity
{

	#[GeneralXmlNamespace]
	#[XmlElement('data')]
	public DataEntity $data;

}
