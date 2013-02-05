<?php

namespace Ephp\Bundle\WsInvokerBundle\Controller\Help;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\WsInvokerBundle\Entity\Help\Service;
use Ephp\Bundle\WsInvokerBundle\Form\Help\ServiceType;

/**
 * Help\Service controller.
 *
 * @Route("/admin/help")
 */
class ServiceController extends Controller
{
    /**
     * Lists all Help\Service entities.
     *
     * @Route("/", name="admin_help")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EphpWsInvokerBundle:Help\Service')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Help\Service entity.
     *
     * @Route("/{id}/show", name="admin_help_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Help\Service')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help\Service entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Help\Service entity.
     *
     * @Route("/new", name="admin_help_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Service();
        $form   = $this->createForm(new ServiceType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Help\Service entity.
     *
     * @Route("/create", name="admin_help_create")
     * @Method("post")
     * @Template("EphpWsInvokerBundle:Help\Service:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Service();
        $request = $this->getRequest();
        $form    = $this->createForm(new ServiceType(), $entity);
        $form->bindRequest($request);
        $entity->populate();
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_help'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Help\Service entity.
     *
     * @Route("/{id}/edit", name="admin_help_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Help\Service')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help\Service entity.');
        }

        $editForm = $this->createForm(new ServiceType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Help\Service entity.
     *
     * @Route("/{id}/update", name="admin_help_update")
     * @Method("post")
     * @Template("EphpWsInvokerBundle:Help\Service:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Help\Service')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Help\Service entity.');
        }

        $editForm   = $this->createForm(new ServiceType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);
        $entity->depopulate($em);
        $entity->populate();
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_help'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Help\Service entity.
     *
     * @Route("/{id}/delete", name="admin_help_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EphpWsInvokerBundle:Help\Service')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Help\Service entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_help'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
