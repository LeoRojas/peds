<?php

// src/Peds/UserBundle/Form/ProductType.php
namespace Peds\RefProcBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('taskI','entity', array('class' => 'PedsEntitiesBundle:Task','required' => false, 'label' => 'Input product for task','mapped' => false));
        $builder->add('taskO','entity', array('class' => 'PedsEntitiesBundle:Task','required' => false, 'label' => 'Output product for task','mapped' => false));
        $builder->add('short_name', 'text');
        $builder->add('description', 'text');

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Peds\EntitiesBundle\Entity\Product'
        ));
    }

    public function getName()
    {
        return 'Product';
    }
}