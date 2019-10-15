<?php

namespace FactelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FactelBundle\Form\PlancuentasType;
use FactelBundle\Entity\Plancuentas;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use FactelBundle\Util;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * PlanCtas controller.
 *
 * @Route("/planes")
 */
class PlanCtasController extends Controller
{

    /**
     * Lists all PlanCtas entities.
     *
     * @Route("/cuentas", name="ctas")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
    public function indexPlanes() {
        $personas = $this->getDoctrine()
                    ->getRepository(Plancuentas::class)
                    ->findAll();
        return $this->render('FactelBundle:PlanCuent:index.html.twig',[
            'cta'=> $personas
        ]);
    }

    /**
     * Lists all PlanCtas entities.
     *
     * @Route("/agregar", name="addctas")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
    public function addPlanes(Request $request){
        $cta=new Plancuentas();

        $form=$this->Form($cta);
        $form->handleRequest($request);
        if($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($cta);
            return $this->redirectToRoute('ctas');
        }
        /*$form= $this->createForm(new PlancuentasType(),$cta);

        $form->handleRequest($request);     
            if($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                 $entity = $em->getRepository('MandantBundle:Preisliste')->find($id);
                  if (!$entity) {
                       throw $this->createNotFoundException('Unable to find Preisliste entity.'); 
                    }
                     $em->remove($entity);
                      $em->flush();
                      return $this->redirect($this->generateUrl('ctas_create'));
                
            }*/
            return $this->render('FactelBundle:PlanCuent:edit.html.twig',[
                'form'=>$form->createView()
            ]);
    }

    private function Form(Plancuentas $entity){
        $form= $this->createForm(new PlancuentasType(),$entity,array(
            'action'=> $this->generateUrl('ctas'),
            'method'=> 'POST'
        ));
        return $form;
    }

    /**
     * Creates a new PlanCtas entity.
     *
     * @Route("/", name="ctas_create")
     * @Method("POST")
     * @Secure(roles="ROLE_EMISOR")
     * @Template("FactelBundle:PlanCuent:index.html.twig")
     */
}
