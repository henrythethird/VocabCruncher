<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sentence;
use AppBundle\Entity\Word;
use AppBundle\Service\PinyinService;
use AppBundle\Value\SearchValue;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DictionaryController extends Controller
{
    /**
     * @Route("/dictionary", name="dictionary_index")
     * @Template("dictionary/index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $searchTerm = $request->get('q');

        return [
            'searchTerm' => $searchTerm
        ];
    }

    /**
     * @Route("/dictionary/search", name="dictionary_search")
     */
    public function searchAction(Request $request)
    {
        $searchTerm = $request->get('q');
        $chineseFirst = $request->get('chinese_first', false);

        if (empty($searchTerm)) {
            return new JsonResponse(['view' => ""]);
        }

        $searchUtil = $this->get("app.search");

        /** @var SearchValue $searchValue */
        $searchValue = $searchUtil->search(
            $searchTerm,
            $request->get('chinese_first', $chineseFirst)
        );

        return new JsonResponse([
            'view' => $this->renderView('dictionary/result.html.twig', [
                'results' => $searchValue->getResults()
            ]),
            'search_preference' => $searchValue->getSearchPreference()
        ]);
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
        $pinyin = new PinyinService();

        $chars = [];
        for ($i = 0; $i < mb_strlen($word->getSimple()); $i++) {
            $char = mb_substr($word->getSimple(), $i, 1);

            if ($pinyin->containsChinese($char)) {
                $chars[] = mb_substr($word->getSimple(), $i, 1);
            }
        }
        return ['chars' => $chars];
    }

    /**
     * @Route("/dictionary/words/{id}", name="dictionary_words")
     * @Template("dictionary/words.html.twig")
     */
    public function wordsAction(Word $word)
    {
        $repo = $this->getDoctrine()->getRepository(Word::class);
        $related = $repo->findRelatedWords($word->getSimple());
        dump($related);
        return ['words' => $related];
    }

    /**
     * @Route("/dictionary/chars/{id}", name="dictionary_chars")
     * @Template("dictionary/chars.html.twig")
     */
    public function charsAction(Word $word)
    {
        $repo = $this->getDoctrine()->getRepository(Word::class);
        $chars = [];
        for ($i = 0; $i < mb_strlen($word->getSimple()); $i++) {
            $chars[] = mb_substr($word->getSimple(), $i, 1);
        }

        $chars = array_unique($chars);
        $words = [];

        foreach ($chars as $char) {
            $words = array_merge($repo->findBy(["simple" => $char]), $words);
        }

        return ['words' => $words];
    }

    /**
     * @Route("/dictionary/sentences/{id}", name="dictionary_sentences")
     * @Template("dictionary/sentences.html.twig")
     */
    public function sentencesAction(Word $word)
    {
        $repo = $this->getDoctrine()->getRepository(Sentence::class);
        $sentences = $repo->containsChinese($word->getSimple());

        return ['exampleSentences' => $sentences];
    }
}
