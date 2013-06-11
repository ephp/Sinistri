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
class ReportController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;
    
    /**
     * Lists all Report entities.
     *
     * @Route("/{scheda}", name="report_tabellone")
     * @Template()
     */
    public function indexAction($scheda) {
        $entities = $this->findBy('EphpSinistriBundle:Report', array('scheda' => $scheda), array('number' => 'DESC'));

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
    public function showAction($scheda, $id) {
        $entity = $this->find('EphpSinistriBundle:Report', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
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
    public function newAction($scheda) {
        $old = $this->findOneBy('EphpSinistriBundle:Report', array('scheda' => $scheda), array('number' => 'DESC'));
        /* @var $old \Ephp\Bundle\SinistriBundle\Entity\Report */
        $entity = new Report();
        if ($old) {
            $entity->setNumber($old->getNumber() + 1);
            $entity->setAnalisiDanno($old->getAnalisiDanno());
            $entity->setAzioni($old->getAzioni());
            $entity->setCopertura($old->getCopertura());
            $entity->setDescrizioneInFatto($old->getDescrizioneInFatto());
            $entity->setMedicoLegale1($old->getMedicoLegale1());
            $entity->setMedicoLegale2($old->getMedicoLegale2());
            $entity->setMedicoLegale3($old->getMedicoLegale3());
            $entity->setNote($old->getNote());
            $entity->setPossibileRivalsa($old->getPossibileRivalsa());
            $entity->setRelazioneAvversaria($old->getRelazioneAvversaria());
            $entity->setRelazioneExAdverso($old->getRelazioneExAdverso());
            $entity->setRichiestaSa($old->getRichiestaSa());
            $entity->setRiserva($old->getRiserva());
            $entity->setStato($old->getStato());
            $entity->setValutazioneResponsabilita($old->getValutazioneResponsabilita());
        } else {
            $entity->setNumber('1');
        }
        $entity->setScheda($this->find('EphpSinistriBundle:Scheda', $scheda));
        $entity->setData(new \DateTime());
        $entity->setValidato(false);
        $em = $this->getEm();
        $em->persist($entity);
        $em->flush();
        
        return $this->redirect($this->generateUrl('report_tabellone_edit', array('scheda' => $scheda, 'id' => $entity->getId())));
        
        $form = $this->createForm(new ReportType($scheda), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
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
    public function createAction(Request $request, $scheda) {
        $entity = new Report();
        $entity->setValidato(false);
        $form = $this->createForm(new ReportType($scheda), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tabellone_report', array('id' => $scheda)));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'scheda' => $scheda,
        );
    }

    /**
     * Displays a form to edit an existing Report entity.
     *
     * @Route("/{scheda}/{id}/edit", name="report_tabellone_edit")
     * @Template()
     */
    public function editAction($scheda, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EphpSinistriBundle:Report')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $editForm = $this->createForm(new ReportType($scheda), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
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
    public function updateAction(Request $request, $scheda, $id) {

        $entity = $this->find('EphpSinistriBundle:Report', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReportType($scheda), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('report_tabellone_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
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
    public function deleteAction(Request $request, $id) {
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

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * Finds and displays a Scheda entity.
     *
     * @Route("/mostra/{id}", name="report_full")
     * @Template()
     */
    public function fullAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EphpSinistriBundle:Scheda')->find($id);
        /* @var $entity Scheda */

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }

        return array(
            'entity' => $entity,
        );
    }
    
    /**
     * Lists all Scheda entities.
     *
     * @Route("-autoupdate", name="report_autoupdate", defaults={"_format"="json"})
     */
    public function autoupdateAction() {
        $req = $this->getRequest()->get('report');
        $em = $this->getEm();

        $report = $this->find('EphpSinistriBundle:Report', $req['id']);
        /* @var $report \Ephp\Bundle\SinistriBundle\Entity\Report */
        try {
            switch ($req['field']) {
                case 'report[data]':
                    $data = \DateTime::createFromFormat('d/m/Y', $req['value']);
                    $report->setData($data);
                    break;
                case 'report[copertura]':
                    $report->setCopertura($req['value']);
                    break;
                case 'report[stato]':
                    $report->setStato($req['value']);
                    break;
                case 'report[descrizione_in_fatto]':
                    $report->setDescrizioneInFatto($req['value']);
                    break;
                case 'report[relazione_avversaria]':
                    $report->setRelazioneAvversaria($req['value']);
                    break;
                case 'report[relazione_ex_adverso]':
                    $report->setRelazioneExAdverso($req['value']);
                    break;
                case 'report[medico_legale1]':
                    $report->setMedicoLegale1($req['value']);
                    break;
                case 'report[medico_legale2]':
                    $report->setMedicoLegale2($req['value']);
                    break;
                case 'report[medico_legale3]':
                    $report->setMedicoLegale3($req['value']);
                    break;
                case 'report[valutazione_responsabilita]':
                    $report->setValutazioneResponsabilita($req['value']);
                    break;
                case 'report[analisi_danno]':
                    $report->setAnalisiDanno($req['value']);
                    break;
                case 'report[riserva]':
                    $report->setRiserva($req['value']);
                    break;
                case 'report[possibile_rivalsa]':
                    $report->setPossibileRivalsa($req['value']);
                    break;
                case 'report[richiesta_sa]':
                    $report->setRichiestaSa($req['value']);
                    break;
                case 'report[note]':
                    $report->setNote($req['value']);
                    break;
            }
            $em->persist($report);
            $em->flush();
        } catch (\Exception $e) {
            throw $e;
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode($req));
    }

}
