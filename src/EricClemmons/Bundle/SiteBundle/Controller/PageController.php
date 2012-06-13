<?php

namespace EricClemmons\Bundle\SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{
    /**
     * @Route("/page/:page")
     * @Template()
     */
    public function viewAction($page)
    {
        $page = $this->get('static.page_repository')->find($page);

        if (!isset($page)) {
            throw new NotFoundHttpException($page.' Not Found');
        }

        return array('page' => $page);
    }
}
