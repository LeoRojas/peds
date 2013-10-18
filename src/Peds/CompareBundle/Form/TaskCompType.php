<?php

// src/Peds/UserBundle/Form/TaskType.php
namespace Peds\CompareBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskCompType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('task1','choice',array('read_only' =>true));
		$builder->add('task1');
		/*
		$builder->add('task1','entity',array(
		'class' => 'PedsEntitiesBundle:Task',
		'required' => false,
		'disabled' => true,
		'read_only' =>true));
		*/
		$builder->add('task2');
		$builder->add('matching');
        $builder->add('obs', 'textarea');
        
		/*
		$builder->add('detail', 'choice', array(
        'choices'   => array('Yes' => 'Detailed', 'a bit' => 'some detail','No' => 'No detail'),
        'required'  => false,
        ));
		*/
		
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Peds\EntitiesBundle\Entity\TaskComp'
        ));
    }

    public function getName()
    {
        return 'TaskComp';
    }
}