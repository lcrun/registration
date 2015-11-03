<?php
namespace Acme\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends BaseType
{
   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('username');
        $builder->add('name', null, array('label' => '姓名', 'translation_domain' => 'FOSUserBundle'))
                ->add('company', null, array('label' => '学校', 'translation_domain' => 'FOSUserBundle','required'=>true))
                          
                ->add('position', null, array('label' => '单位', 'translation_domain' => 'FOSUserBundle','required'=>true))
                ->add('gender', 'choice',array('label' => '性别', 
                    'choices' => array('男' => '男', '女' => '女'),
                    'expanded' => true ))
                ->add('phone', null, array('label' => '手机', 'translation_domain' => 'FOSUserBundle'))
                ->add('address', null, array('label' => '职称', 'translation_domain' => 'FOSUserBundle','required'=>true));
               
        
    }
    public function getName()
    {
       
    return 'acme_user_registration';

    }
}