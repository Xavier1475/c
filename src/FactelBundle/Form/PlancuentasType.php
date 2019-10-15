<?php

namespace FactelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlancuentasType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idempresa')
            ->add('codCta')
            ->add('flujoCaja','text',['required'=>false])
            ->add('bansel','text',['required'=>false])
            ->add('fechaCreacion')
            ->add('tipoCta','entity',[
                'class'=>'FactelBundle:Tipocuenta',
                'property'=>'nombreCuenta'
            ])
            ->add('codMoneda','entity',[
                'class'=>'FactelBundle:Moneda',
                'property'=>'nombMoneda'
            ])
            ->add('grupo','entity',[
                'class'=>'FactelBundle:Grupo',
                'property'=>'nombGrupo'
            ])
            ->add('codUsu','entity',[
                'class'=>'FactelBundle:User',
                'property'=>'username'
            ])
            //->add('save', 'submit',['label'=>'Enviar'])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FactelBundle\Entity\Plancuentas'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'factelbundle_plancuentas';
    }
}
