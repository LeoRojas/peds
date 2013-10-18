<?php

// src/Peds/UserBundle/Form/TaskType.php
namespace Peds\RefProcBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;
use Peds\EntitiesBundle\Entity\ReferenceProcess;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('activity');
		$builder->add('activity', 'entity', array(
			'class' => 'PedsEntitiesBundle:Activity',
			'query_builder' => function(EntityRepository $er) use ($options) {
			return $er->createQueryBuilder('act')
			->innerJoin('act.rp', 'RP')
            ->where('RP.owner = :user_id')
			->setParameter('user_id', $options['user_id']);
			},
			));
        $builder->add('short_name', 'text');
        $builder->add('description');
        $builder->add('detail', 'choice', array(
        'choices'   => array('Yes' => 'Detailed', 'a bit' => 'some detail','No' => 'No detail'),
        'required'  => false,
		'empty_data' => 'No',
		'data' => 'No',
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Peds\EntitiesBundle\Entity\Task'
        ));
		$resolver->setOptional(array('user_id'));
    }

    public function getName()
    {
        return 'Task';
    }
}