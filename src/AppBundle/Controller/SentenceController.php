<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sentence;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SentenceController extends Controller
{
    /**
     * @Route("/sentence", name="sentence_index")
     * @Template("sentence/index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Sentence::class);
        $searchTerm = $request->get('q');
        if (empty($searchTerm)) {
            $sentences = $repository->findLatest();
        } else {
            $sentences = $repository->search($searchTerm);
        }

        return ['sentences' => $sentences];
    }

    /**
     * @Route("/sentence/show/{id}", name="sentence_show")
     * @Template("sentence/show.html.twig")
     */
    public function showSentence(Sentence $sentence)
    {
        return [
            'sentence' => $sentence,
        ];
    }
}
