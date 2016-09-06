<?php

namespace AppBundle\Command;

use AppBundle\Entity\Meaning;
use AppBundle\Entity\Word;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;

class ImportWordsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:import:words')
            ->setDescription('Imports the CC-CEDICT words')
            ->addArgument('file', InputArgument::REQUIRED, 'The path to the CC-CEDICT file');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();
        $file = new File($input->getArgument('file'));

        $open = $file->openFile();
        while (!$open->eof()) {
            $line = $open->getCurrentLine();
            $parsedArr = $this->parse(trim($line));

            if (!$parsedArr) {
                continue;
            }

            $word = new Word();

            $word->setComplex($parsedArr['complex']);
            $word->setSimple($parsedArr['simplified']);
            $word->setPinyin($parsedArr['pinyin']);
            $em->persist($word);

            foreach ($parsedArr['english'] as $english) {
                $meaning = new Meaning();
                $meaning->setMeaning($english);
                $meaning->setWord($word);
                $em->persist($meaning);
            }
        }
        $em->flush();
    }

    /**
     * @param string $cedictLine
     * @return mixed
     */
    public function parse($cedictLine)
    {
        if (substr($cedictLine, 0, 1) === '#') {
            return false;
        }

        $complexSep = stripos($cedictLine, ' ');
        $simplifiedSep = stripos($cedictLine, ' ', $complexSep + 1);
        $quoteStart = stripos($cedictLine, '[', $simplifiedSep);
        $quoteEnd = stripos($cedictLine, ']', $quoteStart);
        $transStart = stripos($cedictLine, '/', $quoteEnd);
        $translations = explode("/", substr($cedictLine, $transStart + 1, -1));

        return [
            'complex' => substr($cedictLine, 0, $complexSep),
            'simplified' => substr($cedictLine, $complexSep + 1, $simplifiedSep - $complexSep - 1),
            'pinyin' => substr($cedictLine, $quoteStart + 1, $quoteEnd - $quoteStart - 1),
            'english' => $translations,
        ];
    }
}
