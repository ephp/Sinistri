<?php

namespace Ephp\Bundle\WsInvokerBundle\Controller\Config;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\WsInvokerBundle\Entity\Config\Host;
use Ephp\Bundle\WsInvokerBundle\Form\Config\HostType;

/**
 * Config\Host controller.
 *
 * @Route("/admin/host")
 */
class HostController extends Controller
{
    /**
     * Lists all Config\Host entities.
     *
     * @Route("/", name="admin_host")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EphpWsInvokerBundle:Config\Host')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Config\Host entity.
     *
     * @Route("/{id}/show", name="admin_host_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Config\Host')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config\Host entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Config\Host entity.
     *
     * @Route("/new", name="admin_host_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Host();
        $form   = $this->createForm(new HostType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Config\Host entity.
     *
     * @Route("/create", name="admin_host_create")
     * @Method("post")
     * @Template("EphpWsInvokerBundle:Config\Host:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Host();
        $request = $this->getRequest();
        $form    = $this->createForm(new HostType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_host'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Config\Host entity.
     *
     * @Route("/{id}/edit", name="admin_host_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Config\Host')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config\Host entity.');
        }

        $editForm = $this->createForm(new HostType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Config\Host entity.
     *
     * @Route("/{id}/update", name="admin_host_update")
     * @Method("post")
     * @Template("EphpWsInvokerBundle:Config\Host:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Config\Host')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config\Host entity.');
        }

        $editForm   = $this->createForm(new HostType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_host'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Config\Host entity.
     *
     * @Route("/{id}/delete", name="admin_host_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EphpWsInvokerBundle:Config\Host')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Config\Host entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_host'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
