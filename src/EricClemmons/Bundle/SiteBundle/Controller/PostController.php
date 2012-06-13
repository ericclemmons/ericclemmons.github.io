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
     * @Route("/post/{post}")
     * @Template()
     */
    public function viewAction($post)
    {
        $post = $this->get('static.post_repository')->find($post);

        if (!isset($post)) {
            throw new NotFoundHttpException($post.' Not Found');
        }

        return array('post' => $post);
    }
}
