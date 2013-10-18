<?php

// src/Peds/AccountBundle/Form/MatchingType.php
namespace Peds\AccountBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('id');
        $builder->add('code');
        $builder->add('short_name');
        $builder->add('description');
        $builder->add('score');

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Peds\EntitiesBundle\Entity\Matching'
        ));
    }

    public function getName()
    {
        return 'Matching';
    }
}