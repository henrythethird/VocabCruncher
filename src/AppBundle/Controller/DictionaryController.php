<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Word;
use AppBundle\Util\SearchUtil;
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
    public function searchAction(Request $request)
    {
        $searchTerm = $request->get('q');

        if (empty($searchTerm)) {
            return ['results' => []];
        }

        $repo = $this->getDoctrine()
            ->getRepository(Word::class);

        $searchUtil = new SearchUtil(
            $this->getDoctrine()
                ->getRepository(Word::class)
        );

        return [
            'results' => $searchUtil->search(
                $searchTerm,
                $request->get('chinese_first', false)
            ),
        ];
    }
}
