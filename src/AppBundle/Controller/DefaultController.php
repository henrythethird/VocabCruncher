<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Word;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("default/index.html.twig")
     */
    public function indexAction()
    {
        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository(Word::class)
        ;

        $words = $repo->findAll();

        return [
            'words' => $words
        ];
    }
}
