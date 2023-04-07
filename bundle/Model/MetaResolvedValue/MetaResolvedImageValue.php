<?php

namespace Codein\IbexaSeoToolkit\Model\MetaResolvedValue;

use eZ\Publish\SPI\Variation\Values\ImageVariation;

class MetaResolvedImageValue extends MetaResolvedBaseValue
{
    public function setValue($value): void
    {
        if ($value instanceof ImageVariation) {
            $this->value = $value;
        } else {
            $this->value = null;
        }
    }

    public function getValue()
    {
        if ($this->value instanceof ImageVariation) {
            return [
                'uri' => $this->value->uri,
                'width' => $this->value->width,
                'height' => $this->value->height,
            ];
        }
        return [];
    }

    public function isEmpty(): bool
    {
        return !($this->value instanceof ImageVariation);
    }
}
