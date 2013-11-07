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
		$trans=$options['translator_service'];
		
		$trans_sname_label = $trans->trans('form.sname');
		$trans_desc_label = $trans->trans('form.desc');
		$trans_vis_label = $trans->trans('form.vis');
		$trans_workf_label = $trans->trans('form.workf');
		$trans_vis_pub_label = $trans->trans('form.vis.public');
		$trans_vis_pri_label = $trans->trans('form.vis.private');
		$trans_work_yes_label = $trans->trans('form.workf.yes');
		$trans_workf_no_label = $trans->trans('form.workf.no');
		
        $builder->add('short_name',null,array(
		'attr' => array('size' => 30),
		'label' => $trans_sname_label
		));
        $builder->add('description',null,array(
		'attr' => array('cols' => 30,'rows' => 4),
		'label' => $trans_desc_label
		));
        $builder->add('visibility', 'choice', array(
        'choices'   => array('PUBLIC' => $trans_vis_pub_label, 'PRIVATE' => $trans_vis_pri_label),
        'required'  => false,
		'empty_data' => 'PUBLIC',
		'data' => 'PUBLIC',
		'label' => $trans_vis_label
        ));
        $builder->add('workflow', 'choice', array(
        'choices'   => array('1' => $trans_work_yes_label, '0' => $trans_workf_no_label),
        'required'  => false,
		'empty_data' => 0,
		'data' => 0,
		'label' => $trans_workf_label
        ));
        //$builder->add('owner');

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Peds\EntitiesBundle\Entity\ReferenceProcess'
        ));
		$resolver->setOptional(array('translator_service'));
    }

    public function getName()
    {
        return 'ReferenceProcess';
    }
}