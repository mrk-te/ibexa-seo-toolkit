<?php

namespace Codein\IbexaSeoToolkit\DependencyInjection\Contracts\FieldHelper;

use eZ\Publish\Core\Helper\FieldHelper;

interface FieldHelperAwareInterface
{
    public function setFieldHelper(FieldHelper $fieldHelper);

    public function getFieldHelper(): FieldHelper;
}
