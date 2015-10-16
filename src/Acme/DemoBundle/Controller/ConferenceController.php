<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



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
    
}
