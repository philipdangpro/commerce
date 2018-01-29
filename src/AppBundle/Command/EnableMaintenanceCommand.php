<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnableMaintenanceCommand extends Command
{
    /*
     * configuration de la commande
     *      setName: nom de la commande: obligatoire
     *      setDescription : description
     *      setHelp: aide // accessible avec l'option -h
     *      addArgument: ajouter un argument; par défaut optionnel
     *      addOption: ajouter un option
     */
    protected function configure()
    {
        $this
            ->setName('app:maintenance:enable')
            ->setDescription('Enable or disable maintenance mode')
            ->setHelp('argument of the command is true|false')
            ->addArgument('value', InputArgument::REQUIRED, 'Use true or false')

        ;
    }

    /*
     * exécution de la commande
     *      $input : permet de récupérer les arguments et les options définies dans configure()
     *      $output : affichage de sortie de la console
     *          style: <info> / <error> / <question> / <comment>
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //récupération de l'argument value
        $value = $input->getArgument('value');

        //tester la valeur saisie
        if($value !== 'true' && $value !== "false"){
            throw new InvalidArgumentException('You must use true or false as value');
        }

        //import du fichier
        $file = file_get_contents('app/config/maintenance.yml');
        //on remplace le contenu
        $content = preg_replace('/true|false/', $value, $file);
        //on on réécrit le fichier
        file_put_contents('app/config/maintenance.yml', $content);

        $message = ($value === "true") ? 'Maintenance <comment>enabled</comment>' : 'Maintenance <comment>disabled</comment>';



        //output
        $output->writeln($message);
        $output->writeln("<info>Mon info</info>");
        $output->writeln("<error>Mon erreur</error>");
        $output->writeln("<question>Ma question</question>");
        $output->writeln("<comment>Mon message</comment>");

    }


}