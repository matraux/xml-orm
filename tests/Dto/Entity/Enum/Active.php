<?php declare(strict_types = 1);

namespace Matraux\XmlOrmTest\Dto\Entity\Enum;

enum Active: string
{

	case True = 'true';
	case False = 'false';

}
