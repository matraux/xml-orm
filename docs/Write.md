**[Back](../README.md)**

# Write

## Entity write
```php
$main = MainEntity::create();
$main->id = 1000;
$main->name = 'Maim entity';
$items = $main->items = ItemCollection::create();
$item = $items->createEntity();
$item->id = 1;
$item->name = 'First item entity';
$item->attr = 'First attribute';

echo $main;
```

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
</gen:main>
```