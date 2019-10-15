<?php

namespace FactelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType ;
use FactelBundle\Entity\Factura;
use FactelBundle\Entity\Cliente;
use FactelBundle\Entity\PtoEmision;
class CxCobrarType extends AbstractType {
    private $securityContext;

    public function __construct($securityContext) {
        $this->securityContext = $securityContext;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
            
            

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            
            
                ->add('cliente_id', ChoiceType :: class,[
                    'choices'  => [
                       
                    ]])
                ->add('pago', 'choice', array(
                    'choices' => array(
                        '1' => 'Cuenta Inicial',
                        '2' => 'Cancelacion',
                        '3' => 'Credito Contable'
                ))
                ->add('ptoEmision_id',  'choice', array(
                    'label'=>'Cuenta Contable',
                    'choices' => array(
                        
                ))
                ->add('formaPago', 'choice', array(
                    'label'=>'Cuenta Contable',
                    'choices' => array(
                        '1'=>'Efectivo',
                        '2'=>'Cheque',
                        '3'=>'Tarjeta de Credito'
                ))
                ->add('secuencial', 'text', array(
                    
                    'required' => true,
                    'placeholder' => 'Nro FActura'
                )
                    
                ))
                ->add('totalSinImpuesto', 'text', array(
                    'label'=>'Valor',
                    'required' => true,
                    'placeholder' => '0.00'
                    )
                     
                ))
                ->add('tatalDescuento', 'text', array(
                    '0.00',
                    'enable'=>false
                )));
                
                
        /*if ($this->securityContext->isGranted("ROLE_ADMIN") || $this->securityContext->isGranted("ROLE_EMISOR_ADMIN")) {
            $builder->add('logo', 'file', array(
                        'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                        'property_path' => 'logo',
                        'required' => true
                    ))
                    ->add('firma', 'file', array(
                        'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                        'property_path' => 'firma',
                        'required' => true
                    ));
            if ($this->securityContext->isGranted("ROLE_ADMIN")) {
                $builder->add('activo', 'checkbox', array(
                    'required' => false,
                ))->add('dirDocAutorizados', 'text', array(
                    'label' => 'Ruta Autorizados'
                ));
            }
        }*/
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'FactelBundle\Entity\Factura'
        ));
    }
}