<?php declare(strict_types = 1);

namespace Matraux\XmlORMTest\Xml;

use Attribute;
use Matraux\XmlORM\Xml\XmlNamespace;

#[Attribute]
final class GeneralXmlNamespace extends XmlNamespace
{

	public static function getName(): string
	{
			return 'gen';
	}

	public static function getSource(): string
	{
			return 'http://www.w3.org/2001/XMLSchema';
	}

}
