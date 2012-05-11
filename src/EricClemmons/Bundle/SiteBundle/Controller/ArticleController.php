<?php

namespace EricClemmons\Bundle\SiteBundle\Controller;

use EricClemmons\Bundle\SiteBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    /**
     * @Route("/articles")
     * @Template()
     */
    public function indexAction()
    {
        $articles = $this->get('article_repository')->findAll();

        return array('articles' => $articles);
    }

    /**
     * @Route("/article/{article}")
     * @Template()
     */
    public function viewAction($article)
    {
        $article = $this->get('article_repository')->find($article);

        return array('article' => $article);
    }
}
