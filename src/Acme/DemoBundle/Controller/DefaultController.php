<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\DemoBundle\Entity\MailUser;
use Symfony\Component\HttpFoundation\StreamedResponse;

 class DefaultController extends Controller
{
    public function indexAction()
    {
        $backend = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Backend')->findOneBy(array());
        $conference = null;
        if($backend != null){
            $conference = $this->getDoctrine()->getManager()
                    ->getRepository('AcmeDemoBundle:Conference')->find($backend->getConferenceId());
        }
        
        return $this->render('AcmeDemoBundle:Default:index.html.twig',
            array('conference' => $conference));
       
    }
    
    public function connactAction()
    {
        return $this->render('AcmeDemoBundle:Default:connact.html.twig');
    }
    
     
    public function noticeAction()
    {
        return $this->render('AcmeDemoBundle:Default:notice.html.twig');
    }
  
    
    
    public function scheduleAction()
    {   
        $backend = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Backend')->findOneBy(array());
        $conference = null;
        if($backend != null){
            $conference = $this->getDoctrine()->getManager()
                    ->getRepository('AcmeDemoBundle:Conference')->find($backend->getConferenceId());
        }
        return $this->render('AcmeDemoBundle:Default:schedule.html.twig',
            array('conference' => $conference));
    }
    
    
      public function mailAction()
    {
                 return $this->render('AcmeDemoBundle:Mail:mail.html.twig');
    }
    
    public function loadmailAction()
    {
    $mailusers = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:MailUser')->findAll();
        
         if($mailusers != null){
            foreach($mailusers as $mailuser ){
                $this->getDoctrine()->getManager()->remove($mailuser);
            }
            $this->getDoctrine()->getManager()->flush();
        }
        
         $this->getDoctrine()->getManager();
        
        $appPath = $this->get('kernel')->getRootDir(); 
             $dataPath = $appPath."/../data";
        
    $codeJson = $dataPath."/users.json";
        $codes = json_decode(file_get_contents($codeJson));
        foreach ($codes as $code) {
            $user = new MailUser();
            $user->setName(trim($code[0]));
             $user->setEmail(trim($code[1]));
             // $user->setStatus('未提醒');
           //   $user->setMobile(trim($code[2]));
        //   $user->setGender(trim($code[3]));
         //   $user->setDepartment(trim($code[4]));
           // $user->setSubject(trim($code[3]));
            //$user->setInfo(trim($code[4]));
            //$user->setNumber(trim($code[5]));
           
           
          //  $user->setRemark(trim($code[8]));
            $this->getDoctrine()->getManager()->persist($user);
        }
         $this->getDoctrine()->getManager()->flush();
         
         return $this->render('AcmeDemoBundle:Mail:mail.html.twig');
        
        
    }
    
    
    public function sendmailAction()
    {
        $mailer = $this->get('mailer');
        
        $users = $this->getDoctrine()->getManager()
                    ->getRepository('AcmeDemoBundle:MailUser')->findAll();
        
        foreach ( $users as $user) {
        $message = $mailer->createMessage()
            ->setSubject('test1!')
            ->setFrom('ctl_admin@ustc.edu.cn')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
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
        $user->setStatus('已经发信');
         $this->getDoctrine()->getManager()->flush();
        }

       $backend = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Backend')->findOneBy(array());
        $conference = null;
        if($backend != null){
            $conference = $this->getDoctrine()->getManager()
                    ->getRepository('AcmeDemoBundle:Conference')->find($backend->getConferenceId());
        }
        
        return $this->render('AcmeDemoBundle:Default:index.html.twig',
            array('conference' => $conference));
    }
    
    
      public function participatedAction($email)
    {
          $user =$this->getDoctrine()->getManager()
                    ->getRepository('AcmeDemoBundle:MailUser')->findOneByEmail($email);
     
          $user->setParticipated(true);
           $this->getDoctrine()->getManager()->flush();
           
          return $this->render('AcmeDemoBundle:Mail:notice.html.twig'); 
    }
    
    
    
     public function downParticipatedAction()
    {
        
      
        $em = $this->getDoctrine()->getManager();
  /*

        $exam = $em->getRepository('TaSurveyDefaultBundle:Exam')->find($id);
        if (! $exam) {
            throw $this->createNotFoundException('The exam does not exist');
        }*/

        $response = new StreamedResponse(function () use ($em) {
            $users = $em->getRepository("AcmeDemoBundle:MailUser")->findByParticipated(true);
            
            $fp = fopen('php://output', 'r+');
            

            foreach ($users as $user) {
                /*
                //shuzhu这数组这 中 取出列
                $answers = array_column($result->getAnswer(), 'answer');
                $formatAnswers = array_map(function ($answer) {
                    return iconv('UTF8', 'GBK', $answer);
                }, $answers);
                $array = [
    "foo" => "bar",
    "bar" => "foo",
];
                "$signUp->getUser()->getPhone()",
                array_unshift($formatAnswers, iconv('UTF8', 'GBK', $result->getStudent()->formatParticipated()));
                array_unshift($formatAnswers, $result->getStudent()->getNumber());
                fputcsv($fp, $formatAnswers);*/
                
                $array = array();
  
                array_unshift($array , iconv('UTF8', 'GBK', $user->getEmail()));
                array_unshift($array , iconv('UTF8', 'GBK', $user->getName()));
              
                 fputcsv($fp, $array);
                
            }

            fclose($fp);
        });

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', 'attachment; filename="participate.csv"');

        return $response;
    }
    
}
