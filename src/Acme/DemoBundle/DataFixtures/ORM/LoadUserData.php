<?php

namespace Acme\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Acme\DemoBundle\Entity\User;

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
        $user->setEmail('sa514007@mail.ustc.edu.cn');
        $user->setUsername('sa514007@mail.ustc.edu.cn');
        $user->setPlainPassword('adminpass');
        //$user->setPassword('3NCRYPT3D-V3R51ON');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPhone("15656590430");

        // Update the user
        $userManager->updateUser($user, true);
    }
}