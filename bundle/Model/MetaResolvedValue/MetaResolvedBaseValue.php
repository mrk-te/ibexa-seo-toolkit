<?php

namespace Codein\IbexaSeoToolkit\Model\MetaResolvedValue;

class MetaResolvedBaseValue implements MetaResolvedValue
{
    public const TYPE = 'text';

    /**
     * @var null|mixed
     */
    protected $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed|null $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
