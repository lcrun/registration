<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

 class DefaultController extends Controller
{
    public function indexAction()
    {$conference = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->findOneByConferenceName('新进教师1');
        
            return $this->render('AcmeDemoBundle:Default:index.html.twig',
                array('confdetail' => $conference->getDetail()));
       
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
    {   $conference = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->findOneByConferenceName('新进教师1');
        
            return $this->render('AcmeDemoBundle:Default:schedule.html.twig',
                array('confschedule' => $conference->getSchedule()));
    }
    public function mailAction($name)
{
    $mailer = $this->get('mailer');
    $message = $mailer->createMessage()
        ->setSubject('You have Completed Registration!')
        ->setFrom('sa514007@mail.ustc.edu.cn')
        ->setTo('1094006418@qq.com')
        ->setBody(
            $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                'Emails/registration.html.twig',
                array('name' => $name)
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

    return $this->render('AcmeDemoBundle:Default:index.html.twig', array('name' => $name));
  }
    
    
    
    
}
