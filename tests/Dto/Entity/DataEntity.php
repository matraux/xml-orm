<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Test\Dto\Entity;

use Matraux\XmlOrm\Entity\Entity;
use Matraux\XmlOrm\Test\Dto\Xml\GeneralXmlNamespace;
use Matraux\XmlOrm\Xml\XmlElement;

#[GeneralXmlNamespace]
#[XmlElement('data')]
final class DataEntity extends Entity
{
	#[GeneralXmlNamespace]
	#[XmlElement('main')]
	public MainEntity $main;
}
