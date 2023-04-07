<?php

namespace Codein\IbexaSeoToolkit\Service\MetaResolver;

use Codein\IbexaSeoToolkit\DependencyInjection\Contracts\FieldHelper\FieldHelperAwareInterface;
use Codein\IbexaSeoToolkit\DependencyInjection\Contracts\FieldHelper\FieldHelperAwareTrait;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Field;

abstract class AbstractFieldPatternResolver implements FieldHelperAwareInterface
{
    use FieldHelperAwareTrait;

    /**
     * Returns the first not empty field matching the pattern
     * If expectedFieldTypeIdentifier is set, return the first field of the given type that is filled
     *
     * @param string|null $pattern
     * @param Content $content
     * @param string[] $expectedFieldTypeIdentifier
     * @return Field|null
     */
    public function getFieldFromPattern(?string $pattern, Content $content, array $expectedFieldTypeIdentifier = []): ?Field
    {
        if (empty($pattern)) {
            return null;
        }
        foreach (explode('|', trim($pattern,'<>')) as $fieldDefIdentifier) {
            if($field = $content->getField($fieldDefIdentifier)) {
                if ((empty($expectedFieldTypeIdentifier) || in_array($field->fieldTypeIdentifier, $expectedFieldTypeIdentifier)) && !$this->getFieldHelper()->isFieldEmpty($content, $fieldDefIdentifier)) {
                    return $field;
                }
            }
        }
        return null;
    }
}
