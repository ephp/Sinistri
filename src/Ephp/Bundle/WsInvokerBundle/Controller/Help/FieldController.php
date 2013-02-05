<?php

namespace Ephp\Bundle\WsInvokerBundle\Controller\Help;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\WsInvokerBundle\Entity\Help\Field;
use Ephp\Bundle\WsInvokerBundle\Form\Help\FieldType;

/**
 * Help\Field controller.
 *
 * @Route("/admin/fields")
 */
class FieldController extends Controller
{
    /**
     * Lists all Help\Field entities.
     *
     * @Route("/{service}", name="admin_help_field")
     * @Template()
     */
    public function indexAction($service)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EphpWsInvokerBundle:Help\Field')->findBy(array('service_name' => $service));

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Help\Field entity.
     *
     * @Route("/{id}/show", name="admin_help_field_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Help\Field')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help\Field entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Help\Field entity.
     *
     * @Route("/new", name="admin_help_field_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Field();
        $form   = $this->createForm(new FieldType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Help\Field entity.
     *
     * @Route("/create", name="admin_help_field_create")
     * @Method("post")
     * @Template("EphpWsInvokerBundle:Help\Field:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Field();
        $request = $this->getRequest();
        $form    = $this->createForm(new FieldType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_help_field', array('service' => $entity->getService()->getName())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Help\Field entity.
     *
     * @Route("/{id}/edit", name="admin_help_field_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Help\Field')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help\Field entity.');
        }

        $editForm = $this->createForm(new FieldType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Help\Field entity.
     *
     * @Route("/{id}/update", name="admin_help_field_update")
     * @Method("post")
     * @Template("EphpWsInvokerBundle:Help\Field:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Help\Field')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help\Field entity.');
        }

        $editForm   = $this->createForm(new FieldType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_help_field', array('service' => $entity->getService()->getName())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Help\Field entity.
     *
     * @Route("/{id}/delete", name="admin_help_field_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EphpWsInvokerBundle:Help\Field')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Help\Field entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_help_field'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
