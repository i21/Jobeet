<?php

namespace Acme\JobeetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Acme\JobeetBundle\Entity\Job;

class JobType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // type is a varchar in the schema but we want its value to be 
            // restricted to a list of choices: full-time, part-time, freelance
            ->add('type', 'choice', array('choices' => Job::getTypes(),
                                          'expanded' => true))
            ->add('Category')
            ->add('company')
            ->add('file', 'file', array('label'=>'Company logo',
                                        'required' => false))
            ->add('url')
            ->add('position')
            ->add('location')
            ->add('description')
            ->add('how_to_apply', null, array('label'=>'How to apply'))
            ->add('is_public', null, array('label'=>'Public?'))
            //->add('is_activated')
            ->add('email')
            //->add('expires_at')
            //->add('created_at')
            //->add('updated_at')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\JobeetBundle\Entity\Job'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'acme_jobeetbundle_job';
    }
}
