<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\HttpFoundation\StreamedResponse;
use Acme\DemoBundle\Entity\User;
//use TaSurvey\DefaultBundle\Form\ExamType;

use Acme\DemoBundle\Entity\SignUp;
use Acme\DemoBundle\Entity\Conference;

 class ConferenceController extends Controller
{
    public function newAction(Request $request)
    {
        
        $conference = new \Acme\DemoBundle\Entity\Conference();
        $conference->setDueDate(new \DateTime('tomorrow'));
        //创建表单
        $builder=$this->createFormBuilder($conference);
        $form = $builder
            ->add('conferenceName','text', array('label' => '会议名称 ：',))
            ->add('dueDate', 'date', array('label' => '时间：',))
            ->add('detail', 'textarea', array('label' => '详情：',))
            ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->persist($conference);
            $this->getDoctrine()->getManager()->flush();
        }
        
        $conferences = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->findAll();
        return $this->render('AcmeDemoBundle:Conference:conference.html.twig', array(
            'form' => $form->createView(),
            'conferences' => $conferences
        ));
    }
    
    
    public function deleteAction(Request $request, $id)
    {
        
        $conference = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->find($id);
        
        $this->getDoctrine()->getManager()->remove($conference);
        $this->getDoctrine()->getManager()->flush();
        
        
        return $this->redirect($this->generateUrl("_new_conference"));
    } 
    
    
    
    
    public function signUpDownloadAction($id)
    {
        
      
        $em = $this->getDoctrine()->getManager();
  /*

        $exam = $em->getRepository('TaSurveyDefaultBundle:Exam')->find($id);
        if (! $exam) {
            throw $this->createNotFoundException('The exam does not exist');
        }*/

        $response = new StreamedResponse(function () use ($em, $id) {
            $signUps = $em->getRepository("AcmeDemoBundle:SignUp")->findBy(array(
                'conference' => $id,
               
            ));
            $fp = fopen('php://output', 'r+');

            foreach ($signUps as $signUp) {
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
                array_unshift($array , iconv('UTF8', 'GBK', $signUp->getUser()->getAddress()));
                array_unshift($array , iconv('UTF8', 'GBK', $signUp->getUser()->getPosition()));
                array_unshift($array , iconv('UTF8', 'GBK', $signUp->getUser()-> getCompany()));
                array_unshift($array , iconv('UTF8', 'GBK', $signUp->getUser()->getGender()));
                
                array_unshift($array, iconv('UTF8', 'GBK', $signUp->getUser()->getPhone()));      
                array_unshift($array , iconv('UTF8', 'GBK', $signUp->getUser()->getEmail()));
                array_unshift($array , iconv('UTF8', 'GBK', $signUp->getUser()->getUserName()));
              
                 fputcsv($fp, $array);
                
            }

            fclose($fp);
        });

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

        return $response;
    }
    
}
