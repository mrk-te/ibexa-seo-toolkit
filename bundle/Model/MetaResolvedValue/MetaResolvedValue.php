<?php

namespace Codein\IbexaSeoToolkit\Model\MetaResolvedValue;

interface MetaResolvedValue
{
    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return mixed
     */
    public function getValue();
}
