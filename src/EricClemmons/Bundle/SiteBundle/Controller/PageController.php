<?php

namespace EricClemmons\Bundle\SiteBundle\Controller;

use EricClemmons\Bundle\SiteBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    /**
     * @Route("/page/:page")
     * @Template()
     */
    public function viewAction($page)
    {
        $page = $this->get('page_repository')->find($page);

        return array('page' => $page);
    }
}
