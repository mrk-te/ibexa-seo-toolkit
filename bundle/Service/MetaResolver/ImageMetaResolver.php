<?php

namespace Codein\IbexaSeoToolkit\Service\MetaResolver;

use Codein\IbexaSeoToolkit\FieldType\Value;
use Codein\IbexaSeoToolkit\Model\MetaResolvedValue\MetaResolvedBaseValue;
use Codein\IbexaSeoToolkit\Model\MetaResolvedValue\MetaResolvedImageValue;
use Codein\IbexaSeoToolkit\Model\MetaResolvedValue\MetaResolvedValue;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\Image\Value as ImageValue;
use eZ\Publish\SPI\Variation\VariationHandler;

class ImageMetaResolver extends AbstractFieldPatternResolver implements MetaResolverInterface
{
    protected const TYPES = [ 'ezimage' ];

    /** @var VariationHandler */
    protected $variationHandler;

    public function __construct(VariationHandler $variationHandler)
    {
        $this->variationHandler = $variationHandler;
    }

    public function supports(string $fieldKey, string $type): bool
    {
        return in_array($type, self::TYPES);
    }

    public function resolve(string $fieldKey, Content $content, Value $metaFieldValue, array $fieldTypeMetasConfig, $fieldDefinitionDefaultValue): MetaResolvedValue
    {
        $resolvedValue = new MetaResolvedImageValue();
        $defaultPattern = $fieldTypeMetasConfig['default_pattern'] ?? null;
        $field = $this->getFieldFromPattern($defaultPattern, $content, self::TYPES);
        if ($field instanceof Field && $field->value instanceof ImageValue) {
            $imageVariation = $this->variationHandler->getVariation($field, $content->versionInfo, $fieldTypeMetasConfig['image_alias']);
            $resolvedValue->setValue($imageVariation);
        }

        return $resolvedValue;
    }
}
