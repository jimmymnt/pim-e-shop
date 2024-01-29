<?php
namespace App\Website\LinkGenerator;

use App\Website\Tool\Text;
use Pimcore\Http\Request\Resolver\DocumentResolver;
use Pimcore\Localization\LocaleServiceInterface;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\ClassDefinition\LinkGeneratorInterface;
use Pimcore\Model\Document;
use Pimcore\Twig\Extension\Templating\PimcoreUrl;
use Symfony\Component\HttpFoundation\RequestStack;

class BlogLinkGenerator implements LinkGeneratorInterface
{
    /**
     * @var DocumentResolver
     */
    protected $documentResolver;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var PimcoreUrl
     */
    protected $pimcoreUrl;

    /**
     * @var LocaleServiceInterface
     */
    protected $localeService;

    /**
     * NewsLinkGenerator constructor.
     *
     * @param DocumentResolver $documentResolver
     * @param RequestStack $requestStack
     * @param PimcoreUrl $pimcoreUrl
     * @param LocaleServiceInterface $localeService
     */
    public function __construct(DocumentResolver $documentResolver, RequestStack $requestStack, PimcoreUrl $pimcoreUrl, LocaleServiceInterface $localeService)
    {
        $this->documentResolver = $documentResolver;
        $this->requestStack = $requestStack;
        $this->pimcoreUrl = $pimcoreUrl;
        $this->localeService = $localeService;
    }

    public function generate(object $object, array $params = []): string
    {
        if (!($object instanceof DataObject\BlogArticle)) {
            throw new \InvalidArgumentException('Given object is no Post');
        }

        return DataObject\Service::useInheritedValues(true, function () use ($object, $params) {
            $fullPath = '';

            if (isset($params['document']) && $params['document'] instanceof Document) {
                $document = $params['document'];
            } else {
                $document = $this->documentResolver->getDocument($this->requestStack->getCurrentRequest());
            }

            $localeUrlPart = '/' . $this->localeService->getLocale() . '/';
            if ($document && $localeUrlPart !== $document->getFullPath()) {
                $fullPath = substr($document->getFullPath(), strlen($localeUrlPart));
            }

            if ($document && !$fullPath) {
                $fullPath = $document->getProperty('post_default_document')
                    ? substr($document->getProperty('post_default_document')->getFullPath(), strlen($localeUrlPart))
                    : '';
            }

            $locale = $params['locale'] ?? null;

            return $this->pimcoreUrl->__invoke(
                [
                'posttitle' => Text::toUrl($object->getTitle($locale) ? $object->getTitle($locale) : 'blogs'),
                'post' => $object->getId(),
                'path' => $fullPath,
                '_locale' => $locale,
            ],
                'blogs-detail',
                true
            );
        });
    }
}
