<?php declare(strict_types=1);

namespace Codein\eZPlatformSeoToolkit\Analysis\Analyzers;

use Codein\eZPlatformSeoToolkit\Analysis\AbstractAnalyzer;
use Codein\eZPlatformSeoToolkit\Analysis\RatioLevels;
use Codein\eZPlatformSeoToolkit\Model\AnalysisDTO;
use Codein\eZPlatformSeoToolkit\Service\AnalyzerService;
use Codein\eZPlatformSeoToolkit\Service\XmlProcessingService;

/**
 * Class InternalLinksAnalyzer.
 */
final class InternalLinksAnalyzer extends AbstractAnalyzer
{
    private const CATEGORY = 'codein_seo_toolkit.analyzer.category.lisibility';

    private const GOOD_RATIO = 1 / 100;

    /** @var XmlProcessingService */
    private $xmlProcessingService;

    /** @var AnalyzerService */
    private $analyzerService;

    public function __construct(AnalyzerService $analyzerService, XmlProcessingService $xmlProcessingService)
    {
        $this->xmlProcessingService = $xmlProcessingService;
        $this->analyzerService = $analyzerService;
    }

    public function analyze(AnalysisDTO $analysisDTO): array
    {
        $fields = $analysisDTO->getFields();

        \libxml_use_internal_errors(true);
        /** @var \DOMDocument $xml */
        try {
            $html = $this->xmlProcessingService->combineAndProcessXmlFields($fields);

        } catch (\Exception $e) {
            return $this->analyzerService->compile(self::CATEGORY, null, null);
        } 
        
        $htmlText = \strip_tags($html->saveHTML());
        $wordCount = \str_word_count($htmlText);

        $domxPath = new \DOMXPath($html);
        $allLinks = $domxPath->query('.//a');

        $count = 0;

        /** @var \DOMElement $link */
        foreach ($allLinks as $link) {
            $linkHref = $link->getAttribute('href');
            // Drop internal links
            if (false !== \strpos($linkHref, 'ezlocation://')) {
                ++$count;
            }
        }

        $ratio = $count / $wordCount;

        $status = RatioLevels::LOW;
        if ($ratio > 0 && $ratio < self::GOOD_RATIO) {
            $status = RatioLevels::MEDIUM;
        } elseif ($ratio >= self::GOOD_RATIO) {
            $status = RatioLevels::HIGH;
        }

        return [
            self::CATEGORY => [
                'status' => $status,
                'data' => [
                    'count' => $count,
                    'recommended' => \ceil($wordCount / (1 / self::GOOD_RATIO))
                ],
            ],
        ];
    }

    public function support(AnalysisDTO $data): bool
    {
        if (count($data->getFields()) === 0) {
            return false;
        }
        return true;
    }
}