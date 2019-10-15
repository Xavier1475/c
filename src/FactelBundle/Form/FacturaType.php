<?php

namespace FactelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FacturaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('claveAcceso','hidden')
            ->add('numeroAutorizacion','hidden')
            ->add('fechaAutorizacion','hidden')
            ->add('estado','hidden')
            ->add('ambiente','hidden')
            ->add('tipoEmision','hidden')
            ->add('secuencial','hidden')
            ->add('formaPago','hidden')
            ->add('fechaEmision','hidden')
            ->add('nombreArchivo','hidden')
            ->add('totalSinImpuestos','hidden')
            ->add('subtotal12','hidden')
            ->add('subtotal0','hidden')
            ->add('subtotalNoIVA','hidden')
            ->add('subtotalExentoIVA','hidden')
            ->add('valorICE','hidden')
            ->add('valorIRBPNR','hidden')
            ->add('iva12','hidden')
            ->add('totalDescuento')
            ->add('propina','hidden')
            ->add('valorTotal','hidden')
            ->add('nroCuota','hidden')
            ->add('banco')
            ->add('ctaContable')
            ->add('fechaVencimiento','hidden')
            ->add('nroCuenta')
            ->add('monto')
            ->add('abono')
            ->add('saldo')
            ->add('estadoCuenta')
            ->add('firmado','hidden')
            ->add('enviarSiAutorizado','hidden')
            ->add('observacion','hidden')
            ->add('cliente','hidden')
            ->add('emisor','hidden')
            ->add('establecimiento')
            ->add('ptoEmision')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FactelBundle\Entity\Factura'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'factelbundle_factura';
    }
}
