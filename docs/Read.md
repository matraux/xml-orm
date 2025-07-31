**[Back](../README.md)**

# Read

## XML code
```xml
<?xml version="1.0" encoding="utf-8"?>
<gen:main xmlns:gen="http://www.w3.org/2001/XMLSchema">
	<gen:ID>1000</gen:ID>
	<gen:NAME>Maim entity</gen:NAME>
	<gen:ITEM ATTR="First attribute">
		<gen:ITEM_ID>1</gen:ITEM_ID>
		<gen:ITEM_NAME>First item entity</gen:ITEM_NAME>
	</gen:ITEM>
	<gen:ITEM ATTR="Second attribute">>
		<gen:ITEM_ID>2</gen:ITEM_ID>
		<gen:ITEM_NAME>Second item entity</gen:ITEM_NAME>
	</gen:ITEM>
	<gen:ITEM ATTR="Third attribute">>
		<gen:ITEM_ID>3</gen:ITEM_ID>
		<gen:ITEM_NAME>Third item entity</gen:ITEM_NAME>
	</gen:ITEM>
</gen:main>
```

## Entity read
```php
use Matraux\XmlORM\Xml\SimpleXmlExplorer;

$explorer = SimpleXmlExplorer::fromString($xml);
$main = MainEntity::create($explorer); // Tell reader where to start --> $reader->withIndex('gen:main')

echo $main->id; // (int) 1000
echo $main->name; // (string) Maim entity

foreach($main->items as $item) {
	echo $item->id; // (int) 1, 2, 3
	echo $item->name; // (string) First item entity | Second item entity | Third item entity
	echo $item->attr; // (string) First attribute | Second attribute | Third attribute
}

echo $main->items[1]->name; // (string) First item entity
echo $main->items[3]->name; // (string) Third item entity

echo count($main->items); // (int) 3

echo $main; // (string) gen:main
echo $main->items[1]; // (string) gen:item
```