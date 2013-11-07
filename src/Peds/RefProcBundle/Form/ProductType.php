<?php

// src/Peds/UserBundle/Form/ProductType.php
namespace Peds\RefProcBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		/*
        $builder->add('taskI','entity', array('class' => 'PedsEntitiesBundle:Task','required' => false, 'label' => 'Input product for task','mapped' => false));
        $builder->add('taskO','entity', array('class' => 'PedsEntitiesBundle:Task','required' => false, 'label' => 'Output product for task','mapped' => false));
		*/
        $trans=$options['translator_service'];
		
		$trans_rp_label = $trans->trans('form.rp');
		$trans_sname_label = $trans->trans('form.sname');
		$trans_desc_label = $trans->trans('form.desc');
		
        $builder->add('rp', 'entity', array(
			'class' => 'PedsEntitiesBundle:ReferenceProcess',
			'query_builder' => function(EntityRepository $er) use ($options) {
			return $er->createQueryBuilder('rp')
            ->where('rp.owner = :user_id')
			->setParameter('user_id', $options['user_id']);
			},
			'label' => $trans_rp_label
			));
        $builder->add('short_name', 'text',array(
		'attr' => array('size' => 30),
		'label' => $trans_sname_label
		));
        $builder->add('description',null,array(
		'attr' => array('cols' => 30,'rows' => 4),
		'label' => $trans_desc_label
		));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Peds\EntitiesBundle\Entity\Product'
        ));
		$resolver->setOptional(array('user_id','translator_service'));
    }

    public function getName()
    {
        return 'Product';
    }
}