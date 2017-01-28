<?php

namespace AppBundle\Command;

use AppBundle\Entity\Meaning;
use AppBundle\Entity\Word;
use AppBundle\Repository\WordRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\File\File;

class ImportFrequenciesCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 10000;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:import:frequency')
            ->setDescription('Imports the frequencies');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var WordRepository $repository */
        $repository = $this->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository(Word::class);

        $frequencyList = file_get_contents("app/Resources/dictionary/frequency.txt");

        $frequencyItems = explode("\n", $frequencyList);

        $index = 10001;
        foreach ($frequencyItems as $frequencyItem) {
            $items = explode(",", $frequencyItem);
            if (!isset($items[1])) {
                continue;
            }

            $words = $repository->findBy(['complex' => $items[0]]);

            /** @var Word $word */
            foreach ($words as $word) {
                $word->setFrequency($index);
            }
            $index--;
        }
        $this->getContainer()
            ->get('doctrine')
            ->getManager()
            ->flush();
    }
}
