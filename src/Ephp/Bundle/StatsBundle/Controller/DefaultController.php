<?php

namespace Ephp\Bundle\StatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/stats")
 */
class DefaultController extends Controller
{
    use \Ephp\UtilityBundle\Controller\Traits\BaseController;
    /**
     * @Route("/", name="stats")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'ospedali' => $this->ospedali(),
            'definiti' => $this->definiti(),
            'stati_operativi' => $this->statiOperativi(),
            );
    }
    
    private function ospedali() {
        $connection = $this->getEm()->getConnection();
        $q = "   SELECT * FROM stats_ospedali ";
        $stmt = $connection->executeQuery($q);
        return $stmt->fetchAll();
    }
    
    private function definiti() {
        $connection = $this->getEm()->getConnection();
        $q = "   SELECT * FROM stats_definiti ";
        $stmt = $connection->executeQuery($q);
        $out = $stmt->fetchAll();
        foreach($out as $i => $data) {
            if($data['ultimo_definito']) {
                $out[$i]['ultimo_definito'] = new \DateTime(date("c", strtotime($data['ultimo_definito'])));
            }
            if($data['ultimo_definito_tutto']) {
                $out[$i]['ultimo_definito_tutto'] = new \DateTime(date("c", strtotime($data['ultimo_definito_tutto'])));
            }
        }
        return $out;
    }

    private function statiOperativi() {
        $connection = $this->getEm()->getConnection();
        $q1 = "   SELECT * FROM stats_stati_operativi_completa ";
        $stmt = $connection->executeQuery($q1);
        $socs = $stmt->fetchAll();
        $q2 = "   SELECT * FROM stats_stati_operativi_gestori ORDER BY sigla, id";
        $stmt = $connection->executeQuery($q2);
        $sogs = $stmt->fetchAll();
        $out = array();
        $stati = array();
        $gestori = array();
        $totali = array();
        foreach($socs as $soc) {
            $out[$soc['id']] = array(
                'stato' => $soc['stato'],
                'totali' => $soc['n'],
            );
        }
        foreach($sogs as $sog) {
            if(!isset($gestori[$sog['sigla']])) {
                $gestori[$sog['sigla']] = $sog['sigla'];
                $totali[$sog['sigla']] = 0;
            }
            $out[$sog['id']][$sog['sigla']] = $sog['n'];
            $totali[$sog['sigla']] += $sog['n'];
        }
        foreach($gestori as $gestore) {
            foreach($socs as $soc) {
                if(!isset($out[$soc['id']][$gestore])) {
                    $out[$soc['id']][$gestore] = 0;
                }
            }
        }
        return array('gestori' => $gestori, 'stats' => $out, 'totali' => $totali);
    }
}
