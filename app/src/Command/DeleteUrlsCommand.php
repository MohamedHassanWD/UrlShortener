<?php

namespace App\Command;

use App\Entity\ShortUrl;
use App\Services\UrlService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

class DeleteUrlsCommand extends Command
{
    protected static $defaultName = 'delete:urls';

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Delete all generated URLs that are older than X days')
            ->setHelp('This command allows you to delete all generated URLs that are older than X days')
            ->addArgument('days', InputArgument::REQUIRED, 'Number of days back')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $days = $input->getArgument('days');
        
        $date = new \DateTime();
        $date->modify('- '.$days.'day');
        $d = $date->format('Y-m-d H:i:s');

        $io->note(sprintf('Deleting URLs generated before: %s', $d));

        $urls = $this->em->createQuery("SELECT e FROM App:ShortUrl e WHERE e.created_at < '$d'")->getResult();

        $count = count($urls);

        if($count < 1){
            $io->note('No URLs Found!');
            return 0;
        }

        foreach($urls as $url){
            $this->em->remove($url);
            $this->em->flush();
        }

        $io->success("All old URLs deleted ($count total)!");

        return 0;
    }
}
