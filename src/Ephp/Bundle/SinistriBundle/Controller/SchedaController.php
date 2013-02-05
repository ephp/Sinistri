<?php

namespace Ephp\Bundle\SinistriBundle\Controller;

use Ephp\Bundle\DragDropBundle\Controller\DragDropController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\SinistriBundle\Entity\Scheda;

/**
 * Scheda controller.
 *
 * @Route("/tabellone")
 */
class SchedaController extends DragDropController {

    /**
     * Lists all Scheda entities.
     *
     * @Route("/{ospedale}/{anno}", name="tabellone", defaults={"ospedale"="", "anno"=""})
     * @Template()
     */
    public function indexAction($ospedale, $anno) {
        $em = $this->getEm();
        $mode = 0;
        $_ospedale = $em->getRepository('EphpSinistriBundle:Ospedale');
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        if($ospedale && $anno) {
            $mode = 3;
            $ospedale = $_ospedale->findOneBy(array('sigla' => $ospedale));
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('ospedale' => $ospedale->getId(), 'anno' => $anno), array('anno' => 'ASC'));
        } elseif ($ospedale) {
            $mode = 2;
            $ospedale = $_ospedale->findOneBy(array('sigla' => $ospedale));
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('ospedale' => $ospedale->getId()), array('anno' => 'ASC'));
        } else {
            $mode = 1;
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array(), array('anno' => 'ASC'));
        }
        $ospedali = $_ospedale->findBy(array(), array('sigla' => 'ASC'));
        $gestori = $_gestore->findBy(array(), array('sigla' => 'ASC'));
        return array('entities' => $entities, 'mode' => $mode, 'ospedale' => $ospedale, 'anno' => $anno < 10 ? '0'.$anno : $anno, 'ospedali' => $ospedali, 'gestori' => $gestori, 'anni' => range(7, date('y')));
    }
    /**
     * Lists all Scheda entities.
     *
     * @Route("-gestore_{gestore}/{ospedale}/{anno}", name="tabellone_gestore", defaults={"ospedale"="", "anno"=""})
     * @Template("EphpSinistriBundle:scheda:index.html.twig")
     */
    public function gestoreAction($gestore, $ospedale, $anno) {
        $em = $this->getEm();
        $mode = 0;
        $_ospedale = $em->getRepository('EphpSinistriBundle:Ospedale');
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $gestore = $_gestore->findOneBy(array('sigla' => $gestore));
        if($ospedale && $anno) {
            $mode = 3;
            $ospedale = $_ospedale->findOneBy(array('sigla' => $ospedale));
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('gestore' => $gestore->getId(), 'ospedale' => $ospedale->getId(), 'anno' => $anno), array('anno' => 'ASC'));
        } elseif ($ospedale) {
            $mode = 2;
            $ospedale = $_ospedale->findOneBy(array('sigla' => $ospedale));
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('gestore' => $gestore->getId(), 'ospedale' => $ospedale->getId()), array('anno' => 'ASC'));
        } else {
            $mode = 1;
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('gestore' => $gestore->getId()), array('anno' => 'ASC'));
        }
        $ospedali = $_ospedale->findBy(array(), array('sigla' => 'ASC'));
        $gestori = $_gestore->findBy(array(), array('sigla' => 'ASC'));
        return array('entities' => $entities, 'mode' => $mode, 'ospedale' => $ospedale, 'anno' => $anno < 10 ? '0'.$anno : $anno, 'gestore' => $gestore, 'ospedali' => $ospedali, 'gestori' => $gestori, 'anni' => range(7, date('y')));
    }

    /**
     * Finds and displays a Scheda entity.
     *
     * @Route("-scheda/{id}", name="tabellone_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EphpSinistriBundle:Scheda')->find($id);

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
     * @Route("-upload", name="tabellone_upload")
     * @Template()
     */
    public function uploadAction() {
        return array();
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-uploadSingle", name="tabellone_upload_single")
     * @Template("EphpDragDropBundle:DragDrop:single.html.php")
     */
    public function uploadSingleAction() {
        return $this->singleFile();
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-assegna-gestore-scheda", name="tabellone_assegna_gestore", defaults={"_format"="json"})
     * @Template()
     */
    public function assegnaGestoreAction() {
        $scheda = $this->getRequest()->get('file');
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-import", name="tabellone_import", defaults={"_format"="json"})
     * @Template()
     */
    public function importAction() {
        $colonne = array('id', 'gestore', 'dasc', 'tpa', 'claimant', 'soi', 'first reserve', 'amount reserved', 'stato', 'sa', 'offerta ns', 'offerta loro', 'priorita', 'recupero offerta ns', 'recupero offerta loro', 'claimant 2', 'gmail',);
        $uri = __DIR__ . '/../../../../../web' . str_replace(' ', '+', urldecode($this->getRequest()->get('file')));
        set_time_limit(3600);
        $em = $this->getEm();
        $conn = $em->getConnection();
        $schede_aggiunte = 0;
        $schede_aggiornate = 0;
        
        $_scheda = $em->getRepository('EphpSinistriBundle:Scheda');
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $_ospedale = $em->getRepository('EphpSinistriBundle:Ospedale');
        $_stato = $em->getRepository('EphpSinistriBundle:Stato');
        $_priorita = $em->getRepository('EphpSinistriBundle:Priorita');
        
        if ($handle = fopen($uri, 'r')) {
            fgetcsv($handle, 0, ',');
            while ($dati = fgetcsv($handle, 0, ',')) {
                try {
                    $conn->beginTransaction();
                    $scheda = new Scheda();
                    foreach ($colonne as $index => $colonna) {
                        switch ($colonna) {
                            case 'gestore':
                                if ($dati[$index]) {
                                    $gestore = $_gestore->findOneBy(array('sigla' => $dati[$index]));
                                    $scheda->setGestore($gestore);
                                }
                                break;
                            case 'dasc':
                                if ($dati[$index]) {
                                    $dasc = \DateTime::createFromFormat('d/m/Y', $dati[$index]);
                                    $scheda->setDasc($dasc);
                                }
                                break;
                            case 'tpa':
                                $tpa = explode('/', $dati[$index]);
                                if (count($tpa) == 3) {
                                    $ospedale = $_ospedale->findOneBy(array('sigla' => $tpa[0]));
                                    $anno = $tpa[1];
                                    $tpa = $tpa[2];
                                    $scheda->setOspedale($ospedale);
                                    $scheda->setAnno($anno);
                                    $scheda->setTpa($tpa);
                                } else {
                                    break(3);
                                }
                                break;

                            case 'claimant':
                                $scheda->setClaimant($dati[$index]);
                                break;
                            case 'soi':
                                if ($dati[$index]) {
                                    $scheda->setSoi($dati[$index]);
                                }
                                break;
                            case 'first reserve':
                                if ($dati[$index]) {
                                    $scheda->setFirstReserve(str_replace(array('€', ' '), array('', ''), $dati[$index]));
                                }
                                break;
                            case 'amount reserved':
                                if ($dati[$index]) {
                                    $scheda->setAmountReserved(str_replace(array('€', ' '), array('', ''), $dati[$index]));
                                }
                                break;
                            case 'stato':
                                if ($dati[$index]) {
                                    $stato = $_stato->findOneBy(array('stato' => $dati[$index]));
                                    if (!$stato) {
                                        $stato = new \Ephp\Bundle\SinistriBundle\Entity\Stato();
                                        $stato->setStato($dati[$index]);
                                        $em->persist($stato);
                                        $em->flush();
                                    }
                                    $scheda->setStato($stato);
                                }
                                break;
                            case 'sa':
                                if ($dati[$index]) {
                                    $scheda->setSa(str_replace(array('€', ' '), array('', ''), $dati[$index]));
                                }
                                break;
                            case 'offerta ns':
                                if ($dati[$index]) {
                                    $scheda->setOffertaNostra(str_replace(array('€', ' '), array('', ''), $dati[$index]));
                                }
                                break;
                            case 'offerta loro':
                                if ($dati[$index]) {
                                    $scheda->setOffertaLoro(str_replace(array('€', ' '), array('', ''), $dati[$index]));
                                }
                                break;
                            case 'priorita':
                                if ($dati[$index]) {
                                    $priorita = $_priorita->findOneBy(array('priorita' => $dati[$index]));
                                    $scheda->setPriorita($priorita);
                                }
                                break;
                            case 'recupero offerta ns':
                                if ($dati[$index]) {
                                    $scheda->setRecuperoOffertaNostra($dati[$index]);
                                }
                                break;
                            case 'recupero offerta loro':
                                if ($dati[$index]) {
                                    $scheda->setRecuperoOffertaLoro($dati[$index]);
                                }
                                break;
                            default: break;
                        }
                    }
                    $old = $_scheda->findOneBy(array('ospedale' => $scheda->getOspedale()->getId(), 'anno' => $scheda->getAnno(), 'tpa' => $scheda->getTpa()));
                    /* @var $old Scheda */
                    if($old) {
                        $old->setAmountReserved($scheda->getAmountReserved());
//                        $old->setClaimant($scheda->getClaimant());
                        $old->setDasc($scheda->getDasc());
                        $old->setFirstReserve($scheda->getFirstReserve());
//                        $old->setGestore($scheda->getGestore());
//                        $old->setOffertaLoro($scheda->getOffertaLoro());
//                        $old->setOffertaNostra($scheda->getOffertaNostra());
//                        $old->setPriorita($scheda->getPriorita());
//                        $old->setRecuperoOffertaLoro($scheda->getRecuperoOffertaLoro());
//                        $old->setRecuperoOffertaNostra($scheda->getRecuperoOffertaNostra());
//                        $old->setSa($scheda->getSa());
                        $old->setSoi($scheda->getSoi());
                        $old->setStato($scheda->getStato());
                        $em->persist($old);
                        $em->flush();
                        $schede_aggiornate++;
                    } else {
                        $em->persist($scheda);
                        $em->flush();
                        $schede_aggiunte++;
                    }
                    $conn->commit();
                } catch (\Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
            }
//            unlink($uri);
        }

        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('schede_aggiunte' => $schede_aggiunte, 'schede_aggiornate' => $schede_aggiornate)));
    }

}

