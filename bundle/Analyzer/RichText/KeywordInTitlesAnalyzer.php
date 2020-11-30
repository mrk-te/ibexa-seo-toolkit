<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analyzer\RichText;

use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use Codein\eZPlatformSeoToolkit\Service\XmlProcessingService;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\Value as FieldValue;

/**
 * Class KeywordInTitlesAnalyzer.
 */
final class KeywordInTitlesAnalyzer implements RichTextAnalyzerInterface
{
    const CATEGORY = 'codein_seo_toolkit.analyzer.category.keyword';
    /** @var \Codein\eZPlatformSeoToolkit\Service\AnalyzerService */
    private $as;

    /** @var \Codein\eZPlatformSeoToolkit\Service\XmlProcessingService */
    private $xmlProcessingService;

    public function __construct(AnalyzerService $analyzerService, XmlProcessingService $xmlProcessingService)
    {
        $this->as = $analyzerService;
        $this->xmlProcessingService = $xmlProcessingService;
    }

    public function analyze(FieldValue $fieldValue, array $data = []): array
    {
        \libxml_use_internal_errors(true);
        /** @var \DOMDocument $xml */
        $xml = $fieldValue->xml;
        $html = $this->xmlProcessingService->processDocument($xml);

        $selector = new \DOMXPath($html);

        $titles = $selector->query('//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6]');

        $status = 'low';
        $keywordSynonyms = \explode(',', \strtr(\mb_strtolower($data['keyword']), AnalyzerService::ACCENT_VALUES));
        $keywordSynonyms = \array_map('trim', $keywordSynonyms);

        $numberOfTitles = 0;
        $numberOfTitlesContainingKeyword = 0;
        foreach ($titles as $title) {
            foreach ($keywordSynonyms as $keyword) {
                /** @var \DOMElement $title */
                $titleLowercase = \strtr(\mb_strtolower($title->textContent), AnalyzerService::ACCENT_VALUES);
                if (false !== \strpos($titleLowercase, $keyword)) {
                    ++$numberOfTitlesContainingKeyword;
                    break;
                }
            }
            ++$numberOfTitles;
        }

        $ratioKeywordInTitle = 0;
        if ($numberOfTitles > 0) {
            $ratioKeywordInTitle = \round($numberOfTitlesContainingKeyword / $numberOfTitles * 100, 2);
        }

        if ($ratioKeywordInTitle > 10 && $ratioKeywordInTitle < 30) {
            $status = 'medium';
        } elseif ($ratioKeywordInTitle >= 30) {
            $status = 'high';
        }

        $analysisData = [
            'ratio' => $ratioKeywordInTitle,
        ];

        return $this->as->compile(self::CATEGORY, $status, $analysisData);
    }

    public function support(FieldDefinition $fieldDefinition, $data): bool
    {
        return 'ezrichtext' === $fieldDefinition->fieldTypeIdentifier;
    }
}
