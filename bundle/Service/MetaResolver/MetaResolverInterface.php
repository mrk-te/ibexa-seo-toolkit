<?php

namespace Codein\IbexaSeoToolkit\Service\MetaResolver;

use Codein\IbexaSeoToolkit\Exception\MetaResolverException;
use Codein\IbexaSeoToolkit\FieldType\Value;
use Codein\IbexaSeoToolkit\Model\MetaResolvedValue\MetaResolvedValue;
use eZ\Publish\API\Repository\Values\Content\Content;

interface MetaResolverInterface
{
    /**
     * Returns true if the resolver supports the current meta definition
     *
     * @param string $fieldKey
     * @param string $type
     * @return boolean
     */
    public function supports(string $fieldKey, string $type): bool;

    /**
     * Returns the resolved value
     *
     * @param string $fieldKey
     * @param Content $content
     * @param Value $metaFieldValue
     * @param array $fieldTypeMetasConfig
     * @param mixed $fieldDefinitionDefaultValue
     * @return MetaResolvedValue
     * @throws MetaResolverException
     */
    public function resolve(string $fieldKey, Content $content, Value $metaFieldValue, array $fieldTypeMetasConfig, $fieldDefinitionDefaultValue): MetaResolvedValue;
}
