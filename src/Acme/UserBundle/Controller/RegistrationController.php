<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

/**
 * Controller managing the registration
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends Controller
{
    private function getCurrentConference(){
        $backend = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Backend')->findOneBy(array());
        $conference = null;
        if($backend != null){
            $conference = $this->getDoctrine()->getManager()
                    ->getRepository('AcmeDemoBundle:Conference')->find($backend->getConferenceId());
        }
        return $conference;
    }
    
    public function registerAction(Request $request)
    {
        $conference = $this->getCurrentConference();
        $now = new \DateTime();
        $errorTip = null;
        if($conference == null || $conference->getDueDate()<$now){
            $errorTip = "暂无可会议可报名！";
        }
        
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);
        $user->setUsername($user->getEmail());
        
        
           $ifuser  = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:User')->findByEmail($user->getEmail());
            if($ifuser   != null ){
            $errorTip = "用户已经注册过，请登陆后往“个人中心”注册会议！";
        }
        
        else{
           

                if ($form->isValid()) {
                    $event = new FormEvent($form, $request);
                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);



                    $userManager->updateUser($user);

                    //报名成功
                    $result = $this->signUpSuccess($user);
                    if($result['success']){
                    } else {
                        $errorTip = $result['msg'];
                    }

                    if (null === $response = $event->getResponse()) {
                        $url = $this->generateUrl('fos_user_registration_confirmed');
                        $response = new RedirectResponse($url);
                    }

                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
                    return $response;
                }

        }
        
        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
            'errorTip' => $errorTip
        ));
    }
    
    private function signUpSuccess($user){
        $conference = $this->getCurrentConference();
        $now = new \DateTime();
        if($conference == null || $conference->getDueDate()<$now){
            return array('success'=>false, 'msg'=>'暂无可会议可报名！');
        }
        $signUp = $this->getDoctrine()->getManager()
                    ->getRepository('AcmeDemoBundle:SignUp')->findOneBy(array('user'=>$user, 'conference'=>$conference));
        if($signUp == null){
            $signUp = new \Acme\DemoBundle\Entity\SignUp(); 
            $signUp->setUser($user);
            $signUp->setConference($conference);
            $signUp->setSignUpDate($now);
            //try{
                $this->getDoctrine()->getManager()->persist($signUp);
                $this->getDoctrine()->getManager()->flush();
                //发送提醒邮件
                $this->sendSuccessEmail($user, $conference);
                return array('success'=>true, 'msg'=>'报名成功！');
            //} catch (\Exception $ex){
            //    return array('success'=>false, 'msg'=>'报名失败！'+$ex->getMessage());
            //}
        } else {
            return array('success'=>false, 'msg'=>'重复报名！');
        }
    }
    
    private function sendSuccessEmail($user, $conference){
        $mailer = $this->get('mailer');
        $from= $this->container->getParameter('mailer_user');
        $url = $this->generateUrl('_sign_show', array(), true);
        $message = $mailer->createMessage()
            ->setSubject('您已成功报名了会议－－'.$conference->getConferenceName())
            ->setFrom($from)
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'AcmeUserBundle:Default:signUpEmail.html.twig',
                    array('conference' => $conference, 'user'=>$user, 'url'=>$url)
                ),
                'text/html'
            )
        ;
        $res = $mailer->send($message);
        var_dump($res);
    }

    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction()
    {
        $email = $this->get('session')->get('fos_user_send_confirmation_email/email');
        $this->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->render('FOSUserBundle:Registration:checkEmail.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Receive the confirmation token from user email provider, login the user
     */
    public function confirmAction(Request $request, $token)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('fos_user_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    /**
     * Tell the user his account is now confirmed
     */
    public function confirmedAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        
        $url = $this->generateUrl('fos_user_profile_show');
        return new RedirectResponse($url);

        /*return $this->render('FOSUserBundle:Registration:confirmed.html.twig', array(
            'user' => $user,
            'targetUrl' => $this->getTargetUrlFromSession(),
        ));*/
    }

    private function getTargetUrlFromSession()
    {
        // Set the SecurityContext for Symfony <2.6
        if (interface_exists('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface')) {
            $tokenStorage = $this->get('security.token_storage');
        } else {
            $tokenStorage = $this->get('security.context');
        }

        $key = sprintf('_security.%s.target_path', $tokenStorage->getToken()->getProviderKey());

        if ($this->get('session')->has($key)) {
            return $this->get('session')->get($key);
        }
    }
}
