<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sentence;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            ->getRepository(Sentence::class);

        $sentences = $repo->findBy(array(), null, 100);

        return [
            'sentences' => $sentences,
        ];
    }

    /**
     * @Route("/explain/{term}", name="")
     */
    public function ajaxExplain($term)
    {
        $explain = $this->get("app.explain");
        return new JsonResponse([
            $explain->query($term)
        ]);
    }

    /**
     * @Route("/admin/test")
     */
    public function adminTestAction(Request $request)
    {
        return $request;
    }
}
