<?php
namespace app\commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RandomizeCommand extends \Knp\Command\Command {
    
    protected function configure() {
        $this
          ->setName("randomize")
          ->setDescription("will run randomized for emails in database");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $app = $this->getSilexApplication();

        $records = $app['database.model']->getRecords();
        shuffle($records);

        $results = array();

        foreach ($records as $index => $record) {
            $nextRecord = isset($records[$index+1]) ? $records[$index+1] : $records[0];

            $row = explode("\t\t", $record);
            $rowNext = explode("\t\t", $nextRecord);

            $emailFrom = $row[2];
            $emailTo = $rowNext[2];

            $results[] = array(
                'from' => trim($emailFrom),
                'to' => trim($emailTo),
            );
        }

        $outFilename = '/tmp/emails.txt';
        file_put_contents($outFilename, serialize($results));

        $output->writeln("saved ".count($results)." records to: $outFilename");
    }
}