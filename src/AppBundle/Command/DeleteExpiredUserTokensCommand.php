<?php

namespace AppBundle\Command;

use AppBundle\Entity\UserToken;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class DeleteExpiredUserTokensCommand extends Command
{

    private $doctrine;


    public function __construct(ManagerRegistry $doctrine, $name = null)
    {
        $this->doctrine = $doctrine;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('app:delete:expired:tokens')
            ->setDescription('Delete expired tokens reset password tokens in database' )
            ->setHelp('N/A')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this
            ->doctrine
            ->getRepository(UserToken::class)
            ->deleteExpiredTokens()
        ;

        //output
        $output->writeln($result);
        $output->writeln("<info>Mon info</info>");


    }


}