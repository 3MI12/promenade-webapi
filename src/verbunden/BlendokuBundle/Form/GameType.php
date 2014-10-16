<?php

namespace verbunden\BlendokuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameType extends AbstractType {

    /**
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title')
                ->add('body')
        ;
    }

    /**
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'verbunden\BlendokuBundle\Entity\Level',
        ));
    }

    /**
     * @author Benjamin Brandt 2014
     * @version 1.0
     * @return string
     */
    public function getName() {
        return '';
    }

}
