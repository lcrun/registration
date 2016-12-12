<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\DemoBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;


/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class SignUpController extends Controller
{
    /**
     * ShowUpAction
     */
    public function signShowAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        } 
        $conferences = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->findAll();
        $signUps = $user->getSignUps();
        
        $now = new \DateTime();
        $status = array();
        foreach($conferences as $conf){
            if($conf->getDueDate() < $now){
                $status[] = "已截止";
            } else {
                $find = false;
                foreach($signUps as $signUp){
                    if($conf == $signUp->getConference()){
                        $find = true;
                        break;
                    }
                }
                if($find)
                    $status[] = "已报名";
                else 
                    $status[] = "正常";
            }
        }
        
        $msg = $this->get('session')->getFlashBag()->get('msg');
        return $this->renderRegView($request,'AcmeDemoBundle:SignUp:signUp.html.twig', 
                array(
                    'conferences' => $conferences,
                    'signUps' => $signUps,
                    'status' => $status,
                    'msg' => $msg
                ));
    }


    //登录那个方法的controller，每个页面都需要，所以写成公用的方法
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


    /**
     * disSign the user
     */
    public function signAction(Request $request, $id)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        } 
        $msg = "报名成功！";
        
        
        $conference = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->find($id);
        $now = new \DateTime();
                        //   $signUps =array(); 
       if($user != null) {
      $signUps =  $this->getDoctrine()
                                        ->getManager()
                                        ->getRepository('AcmeDemoBundle:SignUp')
                                        ->querySignUpByUser($user,$conference);  }
        
        if($conference == null){
            $msg = "不存在会议！";
        } else if($conference->getDueDate() < $now){
            $msg = "报名已截止！";
        } else {
            $signUp = $this->getDoctrine()->getManager()
                    ->getRepository('AcmeDemoBundle:SignUp')->findOneBy(array('user'=>$user, 'conference'=>$conference));
            if($signUp == null){
                $signUp = new \Acme\DemoBundle\Entity\SignUp(); 
                $signUp->setUser($user);
                $signUp->setConference($conference);
                $signUp->setSignUpDate($now);
                try{
                    $this->getDoctrine()->getManager()->persist($signUp);
                    $this->getDoctrine()->getManager()->flush();
                } catch (\Exception $ex){
                    $msg = "报名失败！";
                }
            } else {
                $msg = "重复报名！";
            }
        }

        //之前对非科大老师有限制
        /*
            else if(count($signUps) >=5 && ($user->getCompany() != "中国科学技术大学" || $user->getCompany() != "合肥工业大学")){
            
               $msg=  "本会议同一个学校最多只能报名5名老师";
       
        }
        */

        $this->get('session')->getFlashBag()->add('msg', $msg);
        
        return $this->redirect($this->generateUrl("_sign_show"));
    }
    public function dissignAction(Request $request, $id)
    {
         $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
/*
        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'user' => $user
        ));
        */
                
        $signUp = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:SignUp')->find($id);
        
        $this->getDoctrine()->getManager()->remove( $signUp);
        $this->getDoctrine()->getManager()->flush();
        
        
        return $this->redirect($this->generateUrl("_sign_show"));
    }
    
    
    
}
