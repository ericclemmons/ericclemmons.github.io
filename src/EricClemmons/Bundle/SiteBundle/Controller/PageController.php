<?php

namespace EricClemmons\Bundle\SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{
    /**
     * @Route("/{category}", requirements={"category" = "!posts"})
     * @Template()
     */
    public function pagesAction($category)
    {
        $pages = $this->get('static.page_repository')->findByCategory($category);

        return array('pages' => $pages);
    }

    /**
     * @Route("/page/{slug}")
     * @Template()
     */
    public function viewAction($slug)
    {
        $page = $this->get('static.page_repository')->find($slug);

        if (!isset($page)) {
            throw new NotFoundHttpException($page.' Not Found');
        }

        return array('page' => $page);
    }
}
