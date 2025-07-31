<?php declare(strict_types = 1);

namespace Matraux\XmlORMTest\Entity;

use Matraux\XmlORM\Entity\Entity;
use Matraux\XmlORM\Xml\XmlElement;
use Matraux\XmlORMTest\Xml\GeneralXmlNamespace;

#[GeneralXmlNamespace]
#[XmlElement('data')]
final class DataEntity extends Entity
{

	#[GeneralXmlNamespace]
	#[XmlElement('main')]
	public MainEntity $main;

}
