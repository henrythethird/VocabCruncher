<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sentence;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;

class ImportSentencesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:import:sentences')
            ->setDescription('Import sentences in the following format: [English][tab][Chinese]')
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

            $sentence = new Sentence();

            $sentence->setEnglish($parsedArr['english']);
            $sentence->setMandarin($parsedArr['mandarin']);

            $em->persist($sentence);
        }
        $em->flush();
    }

    /**
     * @param $line
     * @return array|bool
     */
    public function parse($line)
    {
        $sep = explode("\t", $line);

        if (!isset($sep[1])) {
            return false;
        }

        return [
            'english' => $sep[1],
            'mandarin' => $sep[0],
        ];
    }
}
