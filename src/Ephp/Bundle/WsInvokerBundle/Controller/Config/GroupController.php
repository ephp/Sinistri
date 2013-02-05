<?php

namespace Ephp\Bundle\WsInvokerBundle\Controller\Config;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\WsInvokerBundle\Entity\Config\Group;
use Ephp\Bundle\WsInvokerBundle\Form\Config\GroupType;

/**
 * Config\Group controller.
 *
 * @Route("/admin/group")
 */
class GroupController extends Controller
{
    /**
     * Lists all Config\Group entities.
     *
     * @Route("/", name="admin_group")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EphpWsInvokerBundle:Config\Group')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Config\Group entity.
     *
     * @Route("/{id}/show", name="admin_group_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Config\Group')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config\Group entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Config\Group entity.
     *
     * @Route("/new", name="admin_group_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Group();
        $form   = $this->createForm(new GroupType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Config\Group entity.
     *
     * @Route("/create", name="admin_group_create")
     * @Method("post")
     * @Template("EphpWsInvokerBundle:Config\Group:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Group();
        $request = $this->getRequest();
        $form    = $this->createForm(new GroupType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_group'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Config\Group entity.
     *
     * @Route("/{id}/edit", name="admin_group_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Config\Group')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config\Group entity.');
        }

        $editForm = $this->createForm(new GroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Config\Group entity.
     *
     * @Route("/{id}/update", name="admin_group_update")
     * @Method("post")
     * @Template("EphpWsInvokerBundle:Config\Group:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpWsInvokerBundle:Config\Group')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config\Group entity.');
        }

        $editForm   = $this->createForm(new GroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_group'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Config\Group entity.
     *
     * @Route("/{id}/delete", name="admin_group_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EphpWsInvokerBundle:Config\Group')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Config\Group entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_group'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
