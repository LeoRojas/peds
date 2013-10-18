<?php

// src/Peds/UserBundle/Form/UserType.php
namespace Peds\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user_name', 'text');
        $builder->add('email', 'email');
        $builder->add('Password', 'repeated', array(
           'first_name' => 'password',
           'second_name' => 'confirm',
           'type' => 'password',
        ));
        //$builder->add('role');
        /*
        $builder->add('role', 'choice', array(
        'choices'   => array('1' => 'Admin', '2' => 'User'),
        'required'  => false,
        ));
        */
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Peds\EntitiesBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}