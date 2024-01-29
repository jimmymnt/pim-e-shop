<?php

namespace App\Twig\Extension;

use App\Website\LinkGenerator\BlogLinkGenerator;
use Pimcore\Model\DataObject\BlogArticle;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BlogExtension extends AbstractExtension
{
    protected $blogLinkGenerator;

    public function __construct(BlogLinkGenerator $linkGenerator)
    {
        $this->blogLinkGenerator = $linkGenerator;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('blog_hello', [$this, 'printHello']),
            new TwigFunction('app_post_detail_link', [$this, 'generateLink']),
        ];
    }

    public function generateLink(BlogArticle $post): string
    {
        return $this->blogLinkGenerator->generate($post, []);
    }

    /**
     * @return string
     */
    public function printHello(): string
    {
        return 'Hello World';
    }
}
