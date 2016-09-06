<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sentence;
use AppBundle\Entity\SentenceIndex;
use AppBundle\Entity\Word;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SentencePrecompileCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:sentences:precompile')
            ->setDescription('Precompiles the imported Sentences')
            ->addOption('force', 'f', InputArgument::OPTIONAL);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();

        $sentences = $em->getRepository(Sentence::class)
            ->findAll();

        $explainer = $this->getContainer()
            ->get('app.explain');

        /**
         * @var Sentence $sentence
         */
        foreach ($sentences as $sentence) {
            if ($input->getOption('force') && !$sentence->getIndexes()->isEmpty()) {
                $sentence->setIndexes(new ArrayCollection());
            }
            $explanation = $explainer->explain($sentence->getMandarin());
            $output->writeln($sentence->getMandarin());

            /**
             * @var Word $word
             */
            foreach ($explanation as $index => $word) {
                if (!is_object($word)) {
                    continue;
                }

                $sentenceIndex = new SentenceIndex();
                $sentenceIndex->setIndex($index);
                $sentenceIndex->setSentence($sentence);
                $sentenceIndex->setWord($word);

                $em->persist($sentenceIndex);
            }
        }
        $em->flush();
    }
}
