<?php
namespace app\commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RandomizeCommand extends \Knp\Command\Command {
    
    protected function configure() {
        $this
          ->setName("randomize")
          ->setDescription("will run randomized for emails in database")
          ->setHelp('will randomize emails from database, and save them to ouptput')
          ->addArgument('output', InputArgument::REQUIRED, 'output filename')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $app = $this->getSilexApplication();
        $outputFilename = $input->getArgument('output');

        $records = $app['database.model']->getRecords();
        shuffle($records);

        $results = array();

        foreach ($records as $index => $record) {
            $nextRecord = isset($records[$index+1]) ? $records[$index+1] : $records[0];

            $row = explode("\t\t", $record);
            $rowNext = explode("\t\t", $nextRecord);

            $emailFrom = trim($row[2]);
            $emailTo = trim($rowNext[2]);

            $output->writeln($emailFrom."\t\t->\t\t".$emailTo);

            $results[] = array(
                'from' => trim($emailFrom),
                'to' => trim($emailTo),
            );
        }

        file_put_contents($outputFilename, serialize($results));

        $output->writeln(sprintf("saved %s records to: %s", count($results), $outputFilename));
    }
}