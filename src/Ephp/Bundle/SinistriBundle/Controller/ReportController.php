<?php

namespace Ephp\Bundle\SinistriBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\SinistriBundle\Entity\Report;
use Ephp\Bundle\SinistriBundle\Form\ReportType;

/**
 * Report controller.
 *
 * @Route("/report/tabellone")
 */
class ReportController extends Controller
{
    /**
     * Lists all Report entities.
     *
     * @Route("/{scheda}", name="report_tabellone")
     * @Template()
     */
    public function indexAction($scheda)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EphpSinistriBundle:Report')->findBy(array('scheda' => $scheda));

        return array(
            'entities' => $entities,
            'scheda' => $scheda,
        );
    }

    /**
     * Finds and displays a Report entity.
     *
     * @Route("/{scheda}/{id}/show", name="report_tabellone_show")
     * @Template()
     */
    public function showAction($scheda, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EphpSinistriBundle:Report')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'scheda' => $scheda,
        );
    }

    /**
     * Displays a form to create a new Report entity.
     *
     * @Route("/new/{scheda}", name="report_tabellone_new")
     * @Template()
     */
    public function newAction($scheda)
    {
        $entity = new Report();
        $entity->setData(new \DateTime());
        $form   = $this->createForm(new ReportType($scheda), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'scheda' => $scheda,
        );
    }

    /**
     * Creates a new Report entity.
     *
     * @Route("/{scheda}/create", name="report_tabellone_create")
     * @Method("POST")
     * @Template("EphpSinistriBundle:Report:new.html.twig")
     */
    public function createAction(Request $request, $scheda)
    {
        $entity  = new Report();
        $form = $this->createForm(new ReportType($scheda), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('report_tabellone_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'scheda' => $scheda,
        );
    }

    /**
     * Displays a form to edit an existing Report entity.
     *
     * @Route("/{scheda}/{id}/edit", name="report_tabellone_edit")
     * @Template()
     */
    public function editAction($scheda, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EphpSinistriBundle:Report')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $editForm = $this->createForm(new ReportType($scheda), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'scheda' => $scheda,
        );
    }

    /**
     * Edits an existing Report entity.
     *
     * @Route("{scheda}/{id}/update", name="report_tabellone_update")
     * @Method("POST")
     * @Template("EphpSinistriBundle:Report:edit.html.twig")
     */
    public function updateAction(Request $request, $scheda, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EphpSinistriBundle:Report')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReportType($scheda), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('report_tabellone_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'scheda' => $scheda,
        );
    }

    /**
     * Deletes a Report entity.
     *
     * @Route("/{id}/delete", name="report_tabellone_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EphpSinistriBundle:Report')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Report entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('report_tabellone'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
