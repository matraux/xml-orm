<?php declare(strict_types=1);

namespace Matraux\XmlOrm\Codec;

use Matraux\XmlOrm\Metadata\PropertyMetadata;
use Matraux\XmlOrm\Xml\Explorer;

/**
 * Implementing classes must also declare:
 * #[\Attribute(\Attribute::TARGET_PROPERTY)]
 * Otherwise the codec cannot be used as a property attribute.
 */
interface Codec
{
	public function encode(mixed $value, PropertyMetadata $metadata): mixed;

	public function decode(Explorer $explorer, PropertyMetadata $metadata): mixed;
}
