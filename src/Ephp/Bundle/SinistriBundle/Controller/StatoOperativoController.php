<?php

namespace Ephp\Bundle\SinistriBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\SinistriBundle\Entity\StatoOperativo;
use Ephp\Bundle\SinistriBundle\Form\StatoOperativoType;
use Ephp\Bundle\SinistriBundle\Form\StatoOperativoEditType;

/**
 * StatoOperativo controller.
 *
 * @Route("/stati-operativi")
 */
class StatoOperativoController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController;

    /**
     * Lists all StatoOperativo entities.
     *
     * @Route("/", name="stati_operativi")
     * @Template()
     */
    public function indexAction() {
        $entities = $this->findBy('EphpSinistriBundle:StatoOperativo', array());

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a StatoOperativo entity.
     *
     * @Route("/{id}/show", name="stati_operativi_show")
     * @Template()
     */
    public function showAction($id) {
        $entity = $this->find('EphpSinistriBundle:StatoOperativo', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StatoOperativo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),);
    }

    /**
     * Displays a form to create a new StatoOperativo entity.
     *
     * @Route("/new", name="stati_operativi_new")
     * @Template()
     */
    public function newAction() {
        $entity = new StatoOperativo();
        $form = $this->createForm(new StatoOperativoType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new StatoOperativo entity.
     *
     * @Route("/create", name="stati_operativi_create")
     * @Method("post")
     * @Template("EphpSinistriBundle:StatoOperativo:new.html.twig")
     */
    public function createAction() {
        $entity = new StatoOperativo();
        $request = $this->getRequest();
        $form = $this->createForm(new StatoOperativoType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            if ($entity->getPrimo()) {
                $primo = $this->findOneBy('EphpSinistriBundle:StatoOperativo', array('primo' => true));
                if ($primo) {
                    $primo->setPrimo(false);
                    $em->persist($primo);
                    $em->flush();
                }
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stati_operativi'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing StatoOperativo entity.
     *
     * @Route("/{id}/edit", name="stati_operativi_edit")
     * @Template()
     */
    public function editAction($id) {
        $entity = $this->find('EphpSinistriBundle:StatoOperativo', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StatoOperativo entity.');
        }

        $editForm = $this->createForm(new StatoOperativoEditType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing StatoOperativo entity.
     *
     * @Route("/{id}/update", name="stati_operativi_update")
     * @Method("post")
     * @Template("EphpSinistriBundle:StatoOperativo:edit.html.twig")
     */
    public function updateAction($id) {
        $em = $this->getEm();

        $entity = $this->find('EphpSinistriBundle:StatoOperativo', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StatoOperativo entity.');
        }

        $editForm = $this->createForm(new StatoOperativoEditType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stati_operativi'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a StatoOperativo entity.
     *
     * @Route("/{id}/delete", name="stati_operativi_delete")
     * @Method("post")
     */
    public function deleteAction($id) {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $entity = $this->find('EphpSinistriBundle:StatoOperativo', $id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find StatoOperativo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('stati_operativi'));
    }

    /**
     * Deletes a StatoOperativo entity.
     *
     * @Route("/tab", name="stati_operativi_tab")
     * @Method("post")
     */
    public function tabAction() {
        $em = $this->getEm();
        $id = $this->getParam('id', new \Exception('Stato non trovato'));
        $entity = $this->find('EphpSinistriBundle:StatoOperativo', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StatoOperativo entity.');
        }

        $entity->setTab(!$entity->getTab());

        $em->persist($entity);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\Response('<button>'.($entity->getTab() ? 'SI' : 'NO').'</button>');
    }

    /**
     * Deletes a StatoOperativo entity.
     *
     * @Route("/stats", name="stati_operativi_stats")
     * @Method("post")
     */
    public function statsAction() {
        $em = $this->getEm();
        $id = $this->getParam('id', new \Exception('Stato non trovato'));
        $entity = $this->find('EphpSinistriBundle:StatoOperativo', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StatoOperativo entity.');
        }

        $entity->setStats(!$entity->getStats());

        $em->persist($entity);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\Response('<button>'.($entity->getStats() ? 'SI' : 'NO').'</button>');
    }

    /**
     * Deletes a StatoOperativo entity.
     *
     * @Route("/primo", name="stati_operativi_primo")
     * @Method("post")
     */
    public function primoAction() {
        $em = $this->getEm();
        $id = $this->getParam('id', new \Exception('Stato non trovato'));
        $entity = $this->find('EphpSinistriBundle:StatoOperativo', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StatoOperativo entity.');
        }

        $primo = $this->findOneBy('EphpSinistriBundle:StatoOperativo', array('primo' => true));
        if ($primo) {
            $primo->setPrimo(false);
            $em->persist($primo);
            $em->flush();
        }
        
        $entity->setPrimo(true);

        $em->persist($entity);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\Response('<button>'.($entity->getPrimo() ? 'SI' : 'NO').'</button>');
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
