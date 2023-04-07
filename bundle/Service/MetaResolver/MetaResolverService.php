<?php

namespace Codein\IbexaSeoToolkit\Service\MetaResolver;

use Codein\IbexaSeoToolkit\Exception\MetaResolverException;
use Codein\IbexaSeoToolkit\FieldType\Value;
use Codein\IbexaSeoToolkit\Model\MetaResolvedValue\MetaResolvedValue;
use eZ\Publish\API\Repository\Values\Content\Content;

class MetaResolverService
{
    /** @var MetaResolverInterface[]|iterable */
    private $patternResolvers;

    /**
     * @param iterable|MetaResolverInterface[] $patternResolvers
     */
    public function __construct(iterable $patternResolvers)
    {
        $this->patternResolvers = $patternResolvers;
    }

    /**
     * @param string $fieldKey
     * @param Content $content
     * @param Value $metaFieldValue
     * @param array $fieldTypeMetasConfig
     * @param mixed $fieldDefinitionDefaultValue
     * @return MetaResolvedValue
     */
    public function resolve(string $fieldKey, Content $content, Value $metaFieldValue, array $fieldTypeMetasConfig, $fieldDefinitionDefaultValue): MetaResolvedValue
    {
        foreach ($this->patternResolvers as $patternResolver) {
            if ($patternResolver->supports($fieldKey, $fieldTypeMetasConfig['type'])) {
                return $patternResolver->resolve($fieldKey, $content, $metaFieldValue, $fieldTypeMetasConfig, $fieldDefinitionDefaultValue);
            }
        }
        throw new MetaResolverException(sprintf("No resolver found for fieldKey '%s'", $fieldKey));
    }
}
