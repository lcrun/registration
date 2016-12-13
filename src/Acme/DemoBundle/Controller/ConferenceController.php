<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\HttpFoundation\StreamedResponse;
use Acme\DemoBundle\Entity\User;
//use TaSurvey\DefaultBundle\Form\ExamType;

use Acme\DemoBundle\Entity\SignUp;
use Acme\DemoBundle\Entity\Conference;
use Acme\DemoBundle\Entity\Backend;
//登录那个controller
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

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
            ->add('detail', 'textarea', array('label' => '会议详情：',))
              ->add('schedule', 'textarea', array('label' => '日程安排 ',))
            ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->persist($conference);
            $this->getDoctrine()->getManager()->flush();
        }
        
        $conferences = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
        // 如果点击提交了，直接跳转到后台管理首页
            return $this->redirectToRoute('_new_conference');
        }

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
    
    
       public function modifydoneAction(Request $request, $id)
    {
        
           /*
        $conference = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->find($id);
        $builder=$this->createFormBuilder($conference) ->getForm();;
          $conferences = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->findAll();
        return $this->render('AcmeDemoBundle:Conference:modifyConference.html.twig', array(
            'form' => $form->createView(),
            'conferences' => $conferences
        ));*/
           
           //return $this->redirect($this->generateUrl("_new_conference"));
       }
    
       public function modifyAction(Request $request, $id)
    {
        $conference  = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->find($id);
      
        //创建表单
        $builder=$this->createFormBuilder($conference);
           
        
        
      //  $this->getDoctrine()->getManager()->remove($conference);
      //  $this->getDoctrine()->getManager()->flush();
        
        
      //  return $this->redirect($this->generateUrl("_new_conference"));
        
        
        
       //  $builder=$this->createFormBuilder($conference);
        $form = $builder
            ->add('conferenceName','text', array('label' => '会议名称 ：',))
            ->add('dueDate', 'date', array('label' => '时间：',))
            ->add('detail', 'textarea', array('label' => '会议详情：',))
              ->add('schedule', 'textarea', array('label' => '日程安排 ',))
            ->getForm();
   // var_dump(confrence();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
        }

        if ($form->isSubmitted() && $form->isValid()) {
        // 如果点击提交了，直接跳转到后台管理首页
            return $this->redirectToRoute('_new_conference');
        }


        /*
        $conferences = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->findAll();*/
        return $this->render('AcmeDemoBundle:Conference:modifyConference.html.twig', array(
            'form' => $form->createView(),
            'conferenceId' => $id
        ));
    } 
    
    //登录那个方法的controller，每个页面都需要，所以写成公用的方法，本来觉得是不需要在会议里的，现在看来他非得要，那就这把
    protected function renderRegView($request, $twig, array $data=null)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        if (class_exists('\Symfony\Component\Security\Core\Security')) {
            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;
        } else {
            // BC for SF < 2.6
            $authErrorKey = SecurityContextInterface::AUTHENTICATION_ERROR;
            $lastUsernameKey = SecurityContextInterface::LAST_USERNAME;
        }

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        if ($this->has('security.csrf.token_manager')) {
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        } else {
            // BC for SF < 2.4
            $csrfToken = $this->has('form.csrf_provider')
                ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate')
                : null;
        }
        if(!$data){
            $data=array();
        }
        $data['last_username'] = $lastUsername;
        $data['error'] = $error;
        $data['csrf_token'] = $csrfToken;
        return $this->render($twig, $data);
    }
    //会议简介放在这边吧，按道理来讲呢，是需要在会议的数据库里面添加一个字段，然后输入，获取的。。。现在是手写生成。。。
    public function introductionAction(Request $request){   
        return $this->renderRegView($request,'AcmeDemoBundle:Conference:introduction.html.twig');
    }
    
    public function setHomeAction($id){
        $backends = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Backend')->findAll();
        if($backends != null){
            foreach($backends as $backend){
                $this->getDoctrine()->getManager()->remove($backend);
            }
            $this->getDoctrine()->getManager()->flush();
        }
        $backend = new Backend();
        $backend->setConferenceId($id);
        $this->getDoctrine()->getManager()->persist($backend);
        $this->getDoctrine()->getManager()->flush();
        
        return $this->redirect($this->generateUrl("acme_demo_homepage"));
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
            //这种方式在超过63条时会有问题
            /*$signUps = $em->getRepository("AcmeDemoBundle:SignUp")->findBy(array(
                'conference' => $id,
               
            ));*/
            //采用dql的方式可以多查
            $query = $em->createQuery('SELECT u FROM AcmeDemoBundle:User u INNER JOIN AcmeDemoBundle:SignUp s with u.id = s.user WHERE s.conference = :id')->setParameter('id',$id);
            $users = $query->getResult();

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
                array_unshift($array , iconv('UTF8', 'GBK', $user->getAddress()));
                array_unshift($array , iconv('UTF8', 'GBK', $user->getPosition()));
                array_unshift($array , iconv('UTF8', 'GBK', $user-> getCompany()));
                array_unshift($array , iconv('UTF8', 'GBK', $user->getGender()));
                
                array_unshift($array, iconv('UTF8', 'GBK', $user->getPhone()));      
                array_unshift($array , iconv('UTF8', 'GBK', $user->getEmail()));
                array_unshift($array , iconv('UTF8', 'GBK', $user->getName()));
              
                 fputcsv($fp, $array);
                
            }

            fclose($fp);
        });

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

        return $response;
    }
    //显示出参会人员的名单
    public function findParticipantsAction($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT u FROM AcmeDemoBundle:User u INNER JOIN AcmeDemoBundle:SignUp s with u.id = s.user WHERE s.conference = :id')->setParameter('id',$id);
        $users = $query->getResult();
        /*
        $signUps = $em->getRepository("AcmeDemoBundle:SignUp")->findBy(array(
            'conference' => $id,
        ));*/
            
        return $this->render('AcmeDemoBundle:Conference:findParticipants.html.twig', array(
            'users'=>$users
        ));

    }
}
