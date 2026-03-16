<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Test\Dto\Codec;

use Attribute;
use DateTime;
use Matraux\XmlOrm\Codec\Codec;
use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class DateTimeCodec implements Codec
{
	protected const string Format = 'd.m.Y H:i:s';

	public function encode(mixed $value, PropertyMetadata $metadata): ?string
	{
		return $value instanceof DateTime ? $value->format(static::Format) : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $metadata): ?DateTime
	{
		$value = $explorer->withNamespace($metadata->namespace)->withIndex($metadata->index)->value;

		return $value !== null ? new DateTime($value) : null;
	}
}
