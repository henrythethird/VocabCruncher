<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Word;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("default/index.html.twig")
     */
    public function indexAction()
    {
        /**
         * @var EntityRepository $repo
         */
        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository(Word::class);

        $words = $repo->findAll();

        return [
            'words' => $words,
        ];
    }

    /**
     * @Route("/admin/test")
     */
    public function adminTestAction(Request $request)
    {
        return $request;
    }
}
