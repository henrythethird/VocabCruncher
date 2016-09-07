<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Word;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DictionaryController extends Controller
{
    /**
     * @Route("/dictionary", name="dictionary_index")
     * @Template("dictionary/index.html.twig")
     */
    public function indexAction()
    {
        return;
    }

    /**
     * @Route("/dictionary/search/", name="dictionary_search")
     * @Template("dictionary/search.html.twig")
     */
    public function searchAction(Request $request)
    {
        $searchTerm = $request->get('q');
        $results = $this->getDoctrine()
            ->getRepository(Word::class)
            ->dictionarySearch($searchTerm);

        return [
            'results' => $results
        ];
    }
}
