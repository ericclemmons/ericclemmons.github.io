<?php

namespace EricClemmons\Bundle\SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{
    /**
     * @Route("/articles")
     * @Template()
     */
    public function indexAction()
    {
        $articles = $this->get('static.article_repository')->findAll();

        return array('articles' => $articles);
    }

    /**
     * @Route("/article/{article}")
     * @Template()
     */
    public function viewAction($article)
    {
        $article = $this->get('static.article_repository')->find($article);

        if (! $article) {
            throw new NotFoundHttpException($article.' Not Found');
        }

        return array('article' => $article);
    }
}
