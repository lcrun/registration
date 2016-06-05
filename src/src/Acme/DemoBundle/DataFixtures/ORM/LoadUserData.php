<?php

namespace Acme\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Acme\DemoBundle\Entity\User;
use Acme\DemoBundle\Entity\MailUser;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    //.. $container declaration & setter
    
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Get our userManager, you must implement `ContainerAwareInterface`
        $userManager = $this->container->get('fos_user.user_manager');

        // Create our user and set details
        $user = $userManager->createUser();
        $user->setName('admin');
        $user->setEmail('ctl_admin@ustc.edu.cn');
        $user->setUsername('admin');
        $user->setPlainPassword('adminpass');
        //$user->setPassword('3NCRYPT3D-V3R51ON');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPhone("055163602247");

        // Update the user
        $userManager->updateUser($user, true);
        
        /*
        
         $appPath = $this->container->get('kernel')->getRootDir();
        $dataPath = $appPath."/../data";
        
    $codeJson = $dataPath."/users.json";
        $codes = json_decode(file_get_contents($codeJson));
        foreach ($codes as $code) {
            $user = new MailUser();
            $user->setName(trim($code[0]));
             $user->setEmail(trim($code[1]));
           //   $user->setMobile(trim($code[2]));
        //   $user->setGender(trim($code[3]));
         //   $user->setDepartment(trim($code[4]));
           // $user->setSubject(trim($code[3]));
            //$user->setInfo(trim($code[4]));
            //$user->setNumber(trim($code[5]));
           
           
          //  $user->setRemark(trim($code[8]));
            $manager->persist($user);
        }
        
           $manager->flush();
      */  
    }
}