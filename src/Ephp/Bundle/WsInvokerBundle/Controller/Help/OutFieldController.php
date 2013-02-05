<?php

namespace Ephp\Bundle\WsInvokerBundle\Controller\Help;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\WsInvokerBundle\Entity\Help\OutField;
use Ephp\Bundle\WsInvokerBundle\Form\Help\OutFieldType;

/**
 * Help\OutField controller.
 *
 * @Route("/help/output")
 */
class OutFieldController extends Controller
{
    /**
     * Lists all Help\OutField entities.
     *
     * @Route("/{service}", name="admin_help_output")
     * @Template()
     */
    public function indexAction($service)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EphpWsInvokerBundle:Help\OutField')->findBy(array('service_name' => $service));

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Help\OutField entity.
     *
     * @Route("/{id}/show", name="admin_help_output_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Help\OutField')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help\OutField entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Help\OutField entity.
     *
     * @Route("/new", name="admin_help_output_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new OutField();
        $form   = $this->createForm(new OutFieldType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Help\OutField entity.
     *
     * @Route("/create", name="admin_help_output_create")
     * @Method("post")
     * @Template("EphpWsInvokerBundle:Help\OutField:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new OutField();
        $request = $this->getRequest();
        $form    = $this->createForm(new OutFieldType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_help_output', array('service' => $entity->getService()->getName())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Help\OutField entity.
     *
     * @Route("/{id}/edit", name="admin_help_output_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Help\OutField')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help\OutField entity.');
        }

        $editForm = $this->createForm(new OutFieldType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Help\OutField entity.
     *
     * @Route("/{id}/update", name="admin_help_output_update")
     * @Method("post")
     * @Template("EphpWsInvokerBundle:Help\OutField:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Help\OutField')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help\OutField entity.');
        }

        $editForm   = $this->createForm(new OutFieldType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_help_output', array('service' => $entity->getService()->getName())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Help\OutField entity.
     *
     * @Route("/{id}/delete", name="admin_help_output_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EphpWsInvokerBundle:Help\OutField')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Help\OutField entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_help_output', array('service' => $entity->getService()->getName())));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
