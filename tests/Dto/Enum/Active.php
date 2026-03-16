<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Test\Dto\Enum;

enum Active: string
{
	case True = 'true';
	case False = 'false';
}
