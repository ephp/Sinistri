<?php

namespace Ephp\Bundle\GestoriBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\GestoriBundle\Entity\Gestore;
use Ephp\Bundle\GestoriBundle\Form\GestoreType;
use Ephp\Bundle\GestoriBundle\Form\GestoreNewType;

/**
 * Gestore controller.
 *
 * @Route("/gestori")
 */
class GestoreController extends Controller
{
    /**
     * Lists all Gestore entities.
     *
     * @Route("/", name="gestori")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EphpGestoriBundle:Gestore')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Displays a form to create a new Gestore entity.
     *
     * @Route("/new", name="gestori_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Gestore();
        $form   = $this->createForm(new GestoreNewType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Gestore entity.
     *
     * @Route("/create", name="gestori_create")
     * @Method("post")
     * @Template("EphpGestoriBundle:Gestore:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Gestore();
        $request = $this->getRequest();
        $form    = $this->createForm(new GestoreNewType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $entity->setUsername($entity->getEmail());
            $entity->setPlainPassword($entity->getPassword());
            $entity->addRole('ROLE_USER');
            if($request->get('role_admin', 'ko') == 'ok') {
                $entity->addRole('ROLE_ADMIN');
            }
            $entity->setEnabled(true);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('gestori'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Gestore entity.
     *
     * @Route("/{id}/edit", name="gestori_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpGestoriBundle:Gestore')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Gestore entity.');
        }

        $editForm = $this->createForm(new GestoreType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Gestore entity.
     *
     * @Route("/{id}/update", name="gestori_update")
     * @Method("post")
     * @Template("EphpGestoriBundle:Gestore:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpGestoriBundle:Gestore')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Gestore entity.');
        }

        $editForm   = $this->createForm(new GestoreType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('gestori'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Gestore entity.
     *
     * @Route("/{id}/delete", name="gestori_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('EphpGestoriBundle:Gestore')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Gestore entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('gestori'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
