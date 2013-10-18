<?php

// src/Peds/UserBundle/Form/RPType.php
namespace Peds\RefProcBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RPType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('short_name');
        $builder->add('description');
        $builder->add('visibility', 'choice', array(
        'choices'   => array('PUBLIC' => 'Public', 'PRIVATE' => 'Private'),
        'required'  => false,
		'empty_data' => 'PUBLIC',
		'data' => 'PUBLIC',
        ));
        $builder->add('workflow', 'choice', array(
        'choices'   => array('1' => 'Yes', '0' => 'No'),
        'required'  => false,
		'empty_data' => 0,
		'data' => 0,
        ));
        //$builder->add('owner');

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Peds\EntitiesBundle\Entity\ReferenceProcess'
        ));
    }

    public function getName()
    {
        return 'ReferenceProcess';
    }
}