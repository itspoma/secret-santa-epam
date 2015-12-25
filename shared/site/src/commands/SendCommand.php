<?php
namespace app\commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendCommand extends \Knp\Command\Command {
    
    protected function configure() {
        $this
          ->setName("send")
          ->setDescription("will run randomized & send emails");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $app = $this->getSilexApplication();

        $generatedEmailsFilename = '/tmp/emails.txt';
        $emails = file_get_contents($generatedEmailsFilename);
        $emails = unserialize($emails);

        foreach ($emails as $i=>$email) {
            $emailFrom = $email['from'];
            $emailTo = $email['to'];

            $output->writeln(sprintf("%s/%s %s -> %s", $i, count($emails), $emailFrom, $emailTo));

            $emailTo = 'roman_rodomansky@epam.com';
            // $emailTo = 'halyna_myronova@epam.com';
            $emailTo = 'nataliya_kuksa@epam.com';
            // $emailTo = 'Yuliya_Branytska@epam.com';

            $toName = $email['to'];
            $toName = str_replace('@epam.com', '', $toName);
            $toName = str_replace('_', ' ', $toName);
            $toName = ucwords($toName);

            $ok = $app['mailer']->send(\Swift_Message::newInstance()
                ->setFrom(array($app['swiftmailer.options']['username']))
                ->setTo(array($emailTo))
                ->setSubject('Гру розпочато!')
                ->setBody(
                    $app['twig']->render('email/you-are-an-adm.html.twig', array(
                        'receiver' => $toName,
                    )),
                    'text/html'
                )
            );

            $output->writeln(sprintf('ok - %s', $ok));

            if ($ok != 1) {
                var_dump(time(), $emailFrom, $emailTo, $ok);
            }

            die;
        }

        $output->writeln("end!");
    }
}