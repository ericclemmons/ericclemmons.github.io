<?php

namespace EricClemmons\Bundle\SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    /**
     * @Route("/posts")
     * @Template()
     */
    public function indexAction()
    {
        $posts = $this->get('static.post_repository')->findAll();

        return array('posts' => $posts);
    }

    /**
     * @Route("/{year}/{month}/{slug}")
     * @Template()
     */
    public function viewAction($slug)
    {
        $post = $this->get('static.post_repository')->find($slug);

        if (!isset($post)) {
            throw new NotFoundHttpException($post.' Not Found');
        }

        return array('post' => $post);
    }
}
