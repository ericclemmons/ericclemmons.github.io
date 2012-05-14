<?php

namespace EricClemmons\Bundle\SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends Controller
{
    /**
     * @Route("/projects")
     * @Template()
     */
    public function indexAction()
    {
        $projects = $this->get('static.page_repository')->findByCategory('projects');

        return array('projects' => $projects);
    }

    /**
     * @Route("/project/{project}")
     * @Template()
     */
    public function viewAction($project)
    {
        return array();
    }
}
