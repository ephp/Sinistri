<?php

namespace Ephp\Bundle\SinistriBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\SinistriBundle\Entity\Ospedale;
use Ephp\Bundle\SinistriBundle\Form\OspedaleType;

/**
 * Ospedale controller.
 *
 * @Route("/ospedali")
 */
class OspedaleController extends Controller
{
    /**
     * Lists all Ospedale entities.
     *
     * @Route("/", name="ospedali")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EphpSinistriBundle:Ospedale')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Displays a form to create a new Ospedale entity.
     *
     * @Route("/new", name="ospedali_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Ospedale();
        $form   = $this->createForm(new OspedaleType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Ospedale entity.
     *
     * @Route("/create", name="ospedali_create")
     * @Method("post")
     * @Template("EphpSinistriBundle:Ospedale:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Ospedale();
        $request = $this->getRequest();
        $form    = $this->createForm(new OspedaleType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ospedali'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Ospedale entity.
     *
     * @Route("/{id}/edit", name="ospedali_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpSinistriBundle:Ospedale')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ospedale entity.');
        }

        $editForm = $this->createForm(new OspedaleType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Ospedale entity.
     *
     * @Route("/{id}/update", name="ospedali_update")
     * @Method("post")
     * @Template("EphpSinistriBundle:Ospedale:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpSinistriBundle:Ospedale')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ospedale entity.');
        }

        $editForm   = $this->createForm(new OspedaleType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ospedali'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Ospedale entity.
     *
     * @Route("/{id}/delete", name="ospedali_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EphpSinistriBundle:Ospedale')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ospedale entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ospedali'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
