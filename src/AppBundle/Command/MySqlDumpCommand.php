<?php

namespace AppBundle\Command;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class MySqlDumpCommand extends Command
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer, $name = null)
    {
        $this->mailer = $mailer;
        parent::__construct($name);
    }


    protected function configure()
    {
        $this
            ->setName('app:mysql:dump')
            ->setDescription('Export Database and send it by email')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //date du jour
        $date = new \DateTime();
        $dateFormat = $date->format('Y-m-d');

        //Process : donne l'accès au terminal de l'OS
        $command = 'mysqldump -u root -ptroiswa commerce > ' . $dateFormat . '-commerce.sql';

        // zip
        $command .= " && zip $dateFormat-commerce.zip $dateFormat-commerce.sql ";

        $process = new Process($command);

        //exécution du process
        $process->run();

        $process = new Process("rm " . $dateFormat. "-commerce.sql");
        $process->run();

        //email
        $message = (new \Swift_Message("$dateFormat - dump mysql"))
            ->setFrom('contact@contact.com')
            ->setTo('admin@admin.com')
            ->setBody("$dateFormat - dump mysql")
            ->attach(\Swift_Attachment::fromPath("$dateFormat-commerce.zip"))
        ;

        $this->mailer->send($message);
        
        // récupération de la sortie du terminal
        $outputProcess = $process->getOutput();

        //sortie
        $output->writeln($outputProcess);
        $output->writeln('Mysql dump sended by email');


    }

}