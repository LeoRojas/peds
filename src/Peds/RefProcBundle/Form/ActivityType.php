<?php

// src/Peds/UserBundle/Form/ActivityType.php
namespace Peds\RefProcBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('rp');
		
		$builder->add('rp', 'entity', array(
			'class' => 'PedsEntitiesBundle:ReferenceProcess',
			'query_builder' => function(EntityRepository $er) use ($options) {
			return $er->createQueryBuilder('rp')
            ->where('rp.owner = :user_id')
			->setParameter('user_id', $options['user_id']);
			},
			));
        $builder->add('short_name', 'text');
        $builder->add('description');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Peds\EntitiesBundle\Entity\Activity'
        ));
		$resolver->setOptional(array('user_id'));
    }
	/*
	    public function getDefaultOptions()
    {
		$opts=array();
		$opts['user_id']=7;
		return $opts;
    }
	*/
    public function getName()
    {
        return 'activity';
    }
}