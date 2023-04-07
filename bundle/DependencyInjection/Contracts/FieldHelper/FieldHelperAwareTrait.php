<?php

namespace Codein\IbexaSeoToolkit\DependencyInjection\Contracts\FieldHelper;

use eZ\Publish\Core\Helper\FieldHelper;

trait FieldHelperAwareTrait
{
    /** @var FieldHelper */
    protected $fieldHelper;

    public function setFieldHelper(FieldHelper $fieldHelper)
    {
        $this->fieldHelper = $fieldHelper;
    }

    public function getFieldHelper(): FieldHelper
    {
        return $this->fieldHelper;
    }
}
