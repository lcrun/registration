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
                  //  ->add('company', null, array('label' => '学校', 'translation_domain' => 'FOSUserBundle','required'=>true))
                
                 ->add('company', 'choice', 
                    array('label' => '学校',  
                        'choices' => array(           
                            "中国科学技术大学" =>"中国科学技术大学"//,本次会议只针对科大教师
                            /*"合肥工业大学" =>"合肥工业大学",
                            "安徽大学" =>"安徽大学",
                            "安徽农业大学" =>"安徽农业大学",
                            "安徽医科大学" =>"安徽医科大学",
                            "安徽师范大学" =>"安徽师范大学",
                            "安徽建筑大学" =>"安徽建筑大学",
                            "安徽中医药大学" =>"安徽中医药大学",
                            "陆军军官学院" =>"陆军军官学院",
                            "合肥学院" =>"合肥学院",
                            "安徽理工大学" =>"安徽理工大学",
                            "安徽工业大学" =>"安徽工业大学",
                            "安徽工程大学" =>"安徽工程大学",
                            "淮北师范大学" =>"淮北师范大学",
                            "安徽财经大学" =>"安徽财经大学",
                            "皖南医学院" =>"皖南医学院",
                            "蚌埠医学院" =>"蚌埠医学院",
                            "淮南师范学院" =>"淮南师范学院",
                            "安徽科技学院" =>"安徽科技学院",
                            "阜阳师范学院" =>"阜阳师范学院",
                            "安庆师范学院" =>"安庆师范学院",
                            "合肥师范学院" =>"合肥师范学院",
                            "滁州学院" =>"滁州学院",
                            "池州学院" =>"池州学院",
                            "皖西学院" =>"皖西学院",
                            "宿州学院" =>"宿州学院",
                            "黄山学院" =>"黄山学院",
                            "巢湖学院" =>"巢湖学院",
                            "蚌埠学院" =>"蚌埠学院",
                            "铜陵学院" =>"铜陵学院"*/
                        ),
                        'placeholder' => '请选择学校'
                    ))
                ->add('address', null, array('label' => '单位', 'translation_domain' => 'FOSUserBundle','required'=>true))
                 ->add('position', null, array('label' => '职称', 'translation_domain' => 'FOSUserBundle','required'=>true))          
//     ->add('gender', 'choice',array('label' => '性别', 
            //        'choices' => array(),
            //        'expanded' => true ))
                
                                ->add('gender', 'choice', 
                    array('label' => '性别',  
                        'choices' => array(           
                           '男' => '男', '女' => '女'
                        ),
                        'placeholder' => '请选择'
                    ))
                ->add('phone', null, array('label' => '手机', 'translation_domain' => 'FOSUserBundle'))

            ;
               
        
    }
    public function getName()
    {
       
    return 'acme_user_registration';

    }
}