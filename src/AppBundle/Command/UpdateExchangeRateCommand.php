<?php

namespace AppBundle\Command;

use AppBundle\Entity\Currency;
use AppBundle\Repository\CurrencyRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateExchangeRateCommand extends Command
{

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine, $name = null)
{
    $this->doctrine = $doctrine;
    parent::__construct($name);
}

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
            ->setName('app:exchange:rate:update')
            ->setDescription('will update the exchange the rate currencies in Currency entity')
            ->setHelp('no help')
//            ->addArgument('value', InputArgument::REQUIRED, 'Use true or false')

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
        // récupération des taux de change
        $results = json_decode(file_get_contents('https://api.fixer.io/latest?symbols=USD,GBP'));
        $results_raw = file_get_contents('https://api.fixer.io/latest?symbols=USD,GBP');
//        dump($results->rates);
//        dump($results_raw);

        $update = $this->doctrine
            ->getRepository(Currency::class)
            ->updateExchangeRate($results->rates)
        ;


        //sortie
        foreach ($results->rates as $key => $value){
            $output->writeln('Exchange rates <question>' . $key . ' updated at '. $value .'</question>');
        }



    }


}