<?php declare(strict_types = 1);

namespace Matraux\XmlORMTest;

use Matraux\XmlORM\Collection\Collection;
use Matraux\XmlORM\Entity\Entity;
use Matraux\XmlORM\Xml\SimpleExplorer;
use Matraux\XmlORMTest\Collection\ItemCollection;
use Matraux\XmlORMTest\Entity\DataEntity;
use Matraux\XmlORMTest\Entity\ResponseEntity;
use Matraux\XmlORMTest\Xml\GeneralXmlNamespace;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::dumper();
dump('Test dumper');


$explorer = SimpleExplorer::fromFile(__DIR__ . '/assets/general.xml');

$response = ResponseEntity::fromExplorer($explorer);
bdump($response);
bdump($response->data);
bdump($response->data->main);
bdump($response->data->main->items);
bdump($response->data->main->items[0]);
bdump($response->data->main->items[1]);
bdump($response->data->main->items[2]);

$counter = 0;
foreach($response->data->main->items as $item)
{
	if($counter++ === 19999) {
		bdump($item);
		break;
	}
}

bdump((string) $response->data->main->items[0]);

dump('Exit dumper');
exit;
