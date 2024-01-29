<?php

namespace App\Controller;

use App\Website\Navigation\BreadcrumbHelperService;
use Knp\Component\Pager\PaginatorInterface;
use Pimcore\Model\DataObject\BlogArticle;
use Pimcore\Model\DataObject\BlogArticle\Listing;
use Pimcore\Twig\Extension\Templating\HeadTitle;
use Pimcore\Twig\Extension\Templating\Placeholder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends BaseController
{
    public function __construct()
    {
    }

    /**
     * @Route("/blogs", name="blog-listing")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function listingAction(Request $request, PaginatorInterface $paginator): Response
    {
        $posts = new Listing();
        $posts->setOrderKey('oo_id')->setOrder('DESC');

        $paginator = $paginator->paginate(
            $posts,
            $request->get('page', 1),
            6
        );

        return $this->render('blog/listing.html.twig', [
            'posts' => $posts,
            'paginationVariables' => $paginator->getPaginationData(),
        ]);
    }


    /**
     * @Route("{path}/n~{posttitle}~n{post}", name="blogs-detail", defaults={"path"=""}, requirements={"path"=".*?", "posttitle"="[\w-]+", "post"="\d+"})
     */
    public function detailAction(
        Request                 $request,
        HeadTitle               $headTitleHelper,
        Placeholder             $placeholderHelper,
        BreadcrumbHelperService $breadcrumbHelperService
    ): Response
    {
        $post = BlogArticle::getById($request->get('post'));

        if (!($post instanceof BlogArticle)) {
            throw new NotFoundHttpException('Post not found.');
        }

        $headTitleHelper($post->getTitle());
        $breadcrumbHelperService->enrichBlogPage($post);

        return $this->render('blog/detail.html.twig', [
            'post' => $post
        ]);
    }
}
