<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Acme\DemoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\DemoBundle\Entity\MailUser;
/**
 * Description of SendmailCommand
 *
 * @author wing
 */
class SendmailCommand extends ContainerAwareCommand{
    //put your code here
    
    protected function configure()
    {
        $this->setName('app:sendmail')
            ->setDescription('定时邮件提醒任务.')
            ->setHelp(<<<EOT
用于分时发送提醒邮件。
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // do something here

        $manager = $this->getContainer()->get('doctrine')->getManager();
        $maxOnce = 5;//每次发送邮件数
        $count = 0;
        $users = $manager->getRepository('AcmeDemoBundle:MailUser')
                    ->findBy(array( 'status'=>null));
      
            foreach($users as $user){
               
                    $code = $this->sendEmail($user);
                    $user->setStatus('已提醒');
                  
                    $manager->flush();
                    $count++;
                    if($count >= $maxOnce)
                        break;
                  
               
            }
            
       
    }
    
    private function sendEmail($user){
       
        $mailer =$this->getContainer()->get('mailer');
       $message = $mailer->createMessage()
            ->setSubject('test1!')
            ->setFrom('ctl_admin@ustc.edu.cn')
            ->setTo($user->getEmail())
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    // app/Resources/views/Emails/registration.html.twig
                    'Emails/notice.html.twig',
                    array('user' => $user)
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        $mailer->send($message);
 
    }

    

}
