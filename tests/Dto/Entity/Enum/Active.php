<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Test\Dto\Entity\Enum;

enum Active: string
{
	case True = 'true';
	case False = 'false';
}
