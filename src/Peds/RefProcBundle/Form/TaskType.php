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
		$trans=$options['translator_service'];
		$trans_act_label = $trans->trans('form.act');
		$trans_sname_label = $trans->trans('form.sname');
		$trans_desc_label = $trans->trans('form.desc');
		$trans_detail_label = $trans->trans('form.detail');
		$trans_detail_yes_label = $trans->trans('form.detail.yes');
		$trans_detail_abit_label = $trans->trans('form.detail.abit');
		$trans_detail_no_label = $trans->trans('form.detail.no');
		
		$builder->add('activity', 'entity', array(
			'class' => 'PedsEntitiesBundle:Activity',
			'query_builder' => function(EntityRepository $er) use ($options) {
			return $er->createQueryBuilder('act')
			->innerJoin('act.rp', 'RP')
            ->where('RP.owner = :user_id')
			->setParameter('user_id', $options['user_id']);
			},
			'label' => $trans_act_label
			));
        $builder->add('short_name', 'text',array(
		'attr' => array('size' => 30),
		'label' => $trans_sname_label
		));
        $builder->add('description',null,array(
		'attr' => array('cols' => 30,'rows' => 4),
		'label' => $trans_desc_label
		));
        $builder->add('detail', 'choice', array(
        'choices'   => array(
		'Yes' => $trans_detail_yes_label,
		'a bit' => $trans_detail_abit_label,
		'No' => $trans_detail_no_label),
        'required'  => false,
		'empty_data' => 'No',
		'data' => 'No',
		'label' => $trans_detail_label
        ));
		$builder->add('role', 'entity', array(
			'class' => 'PedsEntitiesBundle:Role',
			'query_builder' => function(EntityRepository $er) use ($options) {
			return $er->createQueryBuilder('role')
			->innerJoin('role.rp', 'RP')
            ->where('RP.owner = :user_id')
			->setParameter('user_id', $options['user_id']);
			},
			'required'  => false,
			'label' => "Role label"
			));
		//for checkboxes
		//'expanded' => true,
		//remove the attr line
		//'attr' => array('size' => 4),
		$builder->add('input_prods', 'entity', array(
			'class' => 'PedsEntitiesBundle:Product',
			'query_builder' => function(EntityRepository $er) use ($options) {
			return $er->createQueryBuilder('product')
			->innerJoin('product.rp', 'RP')
            ->where('RP.owner = :user_id')
			->setParameter('user_id', $options['user_id']);
			},
			'required'  => false,
			'multiple' => true,
			'attr' => array('size' => 4),
			'mapped' => false,
			'label' => "input_prods"
			));
			
		$builder->add('output_prods', 'entity', array(
			'class' => 'PedsEntitiesBundle:Product',
			'query_builder' => function(EntityRepository $er) use ($options) {
			return $er->createQueryBuilder('product')
			->innerJoin('product.rp', 'RP')
            ->where('RP.owner = :user_id')
			->setParameter('user_id', $options['user_id']);
			},
			'required'  => false,
			'multiple' => true,
			'attr' => array('size' => 4),
			'mapped' => false,
			'label' => "output_prods"
			));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Peds\EntitiesBundle\Entity\Task'
        ));
		$resolver->setOptional(array('user_id','translator_service'));
    }

    public function getName()
    {
        return 'Task';
    }
}