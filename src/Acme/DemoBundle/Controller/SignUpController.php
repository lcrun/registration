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
    public function signShowAction()
    {
       
        
    $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        } 
      $conferences = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->findAll();
        return $this->render('AcmeDemoBundle:SignUp:signUp.html.twig', array(
            
            'conferences' => $conferences
        ));
  
    }

    /**
     * disSign the user
     */
    public function SignAction(Request $request, $id)
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
         $signUp = new \Acme\DemoBundle\Entity\SignUp();  
 
          $conference = $this->getDoctrine()->getManager()
                ->getRepository('AcmeDemoBundle:Conference')->find($id);
        $signUp->setUser($user);
        $signUp->setConference($conference);
        $signUp->setSignUpDate(new \DateTime('today'));
        $this->getDoctrine()->getManager()->persist($signUp);
            $this->getDoctrine()->getManager()->flush();
      
        return $this->redirect($this->generateUrl("_sign_show"));
    }
}
