<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DictionaryController extends Controller
{
    /**
     * @Route("/dictionary/search", name="dictionary_index")
     * @Template("dictionary/index.html.twig")
     */
    public function searchAction(Request $request)
    {
        $searchTerm = $request->get('q');
        $chineseFirst = $request->get('chinese_first', false);

        if (empty($searchTerm)) {
            return ['results' => []];
        }

        $searchUtil = $this->get("app.search");

        return [
            'results' => $searchUtil->search(
                $searchTerm,
                $request->get('chinese_first', $chineseFirst)
            ),
        ];
    }
}
