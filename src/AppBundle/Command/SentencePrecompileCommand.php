<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sentence;
use AppBundle\Service\ExplainService;
use AppBundle\Service\SentenceCompileService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SentencePrecompileCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ExplainService
     */
    private $explainer;

    /**
     * @var SentenceCompileService
     */
    private $compiler;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->explainer = $this->getContainer()
            ->get('app.explain');

        $this->entityManager = $this->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->compiler = $compiler = $this->getContainer()
            ->get('app.compile');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:sentences:precompile')
            ->setDescription('Precompiles the imported Sentences')
            ->addOption('recompile', 'r', InputOption::VALUE_NONE);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sentences = $this
            ->entityManager
            ->getRepository(Sentence::class)
            ->findAll();

        /**
         * @var Sentence $sentence
         */
        foreach ($sentences as $sentence) {
            if ($input->getOption("recompile")) {
                $output->writeln("recompiling...");
                $this->compiler->recompile($sentence);
            } else {
                $this->compile($output, $sentence);
            }
        }
    }

    /**
     * @param OutputInterface $output
     * @param Sentence $sentence
     */
    public function compile(OutputInterface $output, Sentence $sentence)
    {
        try {
            $this->compiler->compile($sentence);
        } catch (\Exception $exception) {
            $output->writeln("Sentence already compiled... Skipping.");
        }
    }
}
