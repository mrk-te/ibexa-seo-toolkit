<?php

namespace Codein\IbexaSeoToolkit\Service\MetaResolver;

use Codein\IbexaSeoToolkit\FieldType\Value;
use Codein\IbexaSeoToolkit\Model\MetaResolvedValue\MetaResolvedBaseValue;
use Codein\IbexaSeoToolkit\Model\MetaResolvedValue\MetaResolvedValue;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\TextBlock\Value as TextBlockValue;

class StringMetaResolver extends AbstractFieldPatternResolver implements MetaResolverInterface
{
    protected const TYPES = [ 'ezstring', 'eztext' ];

    public function supports(string $fieldKey, string $type): bool
    {
        return in_array($type, self::TYPES);
    }

    public function resolve(string $fieldKey, Content $content, Value $metaFieldValue, array $fieldTypeMetasConfig, $fieldDefinitionDefaultValue): MetaResolvedValue
    {
        /** Overrided value is returned first */
        if ($overridedValue = $this->getValueFromArray($fieldKey, $metaFieldValue->metas)) {
            return new MetaResolvedBaseValue($overridedValue);
        }

        /** Resolve the pattern to read fields */
        $defaultPattern = $fieldTypeMetasConfig['default_pattern'] ?? null;
        $field = $this->getFieldFromPattern($defaultPattern, $content, self::TYPES);
        if ($field instanceof Field) {
            $value = $field->value->text;
            if ($field->value instanceof TextBlockValue) {
                $value = preg_replace('/(\n|\r|\t|\s)+/', ' ', $value);
            }
            return new MetaResolvedBaseValue($value);
        }

        /** Fallback to default */
        if (!empty($fieldDefinitionDefaultValue)) {
            return new MetaResolvedBaseValue($fieldDefinitionDefaultValue);
        }

        return new MetaResolvedBaseValue();
    }

    /**
     * @param string $fieldKey
     * @param array $values
     * @return mixed|null
     */
    protected function getValueFromArray(string $fieldKey, array $values)
    {
        if(array_key_exists($fieldKey, $values) && !empty($values[$fieldKey])) {
            return $values[$fieldKey];
        }
        return null;
    }
}
