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

    /**
     * @Route("/dictionary/details/{id}", name="dictionary_details")
     * @Template("dictionary/details.html.twig")
     */
    public function detailsAction(Word $word)
    {
        return ['word' => $word];
    }

    /**
     * @Route("/dictionary/stroke/{id}", name="dictionary_stroke")
     * @Template("dictionary/stroke.html.twig")
     */
    public function strokeAction(Word $word)
    {
        return ['word' => $word];
    }

    /**
     * @Route("/dictionary/words/{id}", name="dictionary_words")
     * @Template("dictionary/words.html.twig")
     */
    public function wordsAction(Word $word)
    {
        return ['word' => $word];
    }

    /**
     * @Route("/dictionary/sentences/{id}", name="dictionary_sentences")
     * @Template("dictionary/sentences.html.twig")
     */
    public function sentencesAction(Word $word)
    {
        return ['sentences' => 1];
    }
}
