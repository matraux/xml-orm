<?php declare(strict_types = 1);

namespace Matraux\XmlORMTest\assets;

$data = '<?xml version="1.0" encoding="utf-8"?>';

$data .= '
<gen:response xmlns:gen="http://www.w3.org/2001/XMLSchema">
	<noNS>No namespace</noNS>
	<gen:data>
		<gen:main program-version="1.3.1" custom-note="Custom note">
			<gen:customName>Some custom name</gen:customName>
			<gen:customSurname>Some custom surname</gen:customSurname>
';
for ($n = 1; $n <= 20000; $n++) {
	$data .= '
			<gen:item>
				<gen:ID>' . $n . '</gen:ID>
				<gen:Name>' . $n . '. name</gen:Name>
				<gen:Active>' . ($n % 3 === 0 ? 'true' : 'false') . '</gen:Active>
				<gen:md5>' . md5((string) $n) . '</gen:md5> <!-- md5 od ID -->
				<gen:hash>' . base64_encode(md5((string) $n)) . '</gen:hash> <!-- base64 encoded md5 -->
			</gen:item>
	';
}

$data .= '
		</gen:main>
	</gen:data>
</gen:response>
';

file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'general.xml', $data);

header('Content-type: application/xml');
exit($data);
