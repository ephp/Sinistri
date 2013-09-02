<?php

namespace Ephp\Bundle\SinistriBundle\Controller\Traits;

trait SxCalendarController {

    protected $ANALISI_SINISTRI_COPERTURA = "ASC";
    protected $VERIFICA_INCARICHI_MEDICI  = "VIM";
    protected $RICERCA_POLIZZE_MEDICI     = "RPM";
    protected $RELAZIONE_RISERVA          = "RER";
    protected $RICHIESTA_SA               = "RSA";
    protected $TRATTATIVE_AGGIORNAMENTI   = "TAX";
    protected $JWEB                       = "JWB";
    protected $EMAIL_JWEB                 = "EJW";
    protected $CANCELLERIA_TELEMATICA     = "CNT";
    protected $RAVINALE                   = "RVP";
    protected $EMAIL_RAVINALE             = "MRV";
    protected $ATTIVITA_MANUALE           = "OTH";
    protected $EMAIL_MANUALE              = "EML";
    protected $RISCHEDULAZIONE            = "RIS";
    protected $VERIFICA_PERIODICA         = "VER";
    protected $CAMBIO_STATO_OPERATIVO     = "CHS";
    protected $CAMBIO_GESTORE             = "CHG";
    protected $PRIORITA                   = "PRI";
    
    /**
     * @return \Ephp\Bundle\CalendarBundle\Entity\Calendario
     */
    protected function getCalendar() {
        
        $cal = $this->findOneBy('EphpCalendarBundle:Calendario', array('sigla' => 'JFC-SX'));
        if (!$cal) {
            $_cal = $this->getRepository('EphpCalendarBundle:Calendario');
            /* @var $_cal \Ephp\Bundle\CalendarBundle\Entity\CalendarioRepository */
            $cal = $_cal->createCalendario('JFC-SX', 'JF-Claims Sinistri');
            $_tipo = $this->getRepository('EphpCalendarBundle:Tipo');
            /* @var $_tipo \Ephp\Bundle\CalendarBundle\Entity\TipoRepository */
            $_tipo->createTipo($this->ANALISI_SINISTRI_COPERTURA, 'Analisi Sinistri e Copertura', 'aaffaa', $cal, false);
            $_tipo->createTipo($this->VERIFICA_INCARICHI_MEDICI, 'Verifica Incarichi e Medici', 'aaffaa', $cal, false);
            $_tipo->createTipo($this->RICERCA_POLIZZE_MEDICI, 'Ricerca Polizze e Medici', 'aaffaa', $cal, false);
            $_tipo->createTipo($this->RELAZIONE_RISERVA, 'Relazione e Riserva', 'aaffaa', $cal, false);
            $_tipo->createTipo($this->RICHIESTA_SA, 'Richiesta di SA', 'aaffaa', $cal, false);
            $_tipo->createTipo($this->TRATTATIVE_AGGIORNAMENTI, 'Trattative e Aggiornamenti', 'aaffaa', $cal, false);
            $_tipo->createTipo($this->JWEB, 'J-Web Claims', 'ffaaaa', $cal);
            $_tipo->createTipo($this->EMAIL_JWEB, 'Email da J-Web Claims', 'ff8888', $cal, true, false, true);
            $_tipo->createTipo($this->CANCELLERIA_TELEMATICA, 'Cancelleria Telematiche', 'aaffff', $cal);
            $_tipo->createTipo($this->RAVINALE, 'Ravinale Piemonte', 'ffaaff', $cal);
            $_tipo->createTipo($this->EMAIL_RAVINALE, 'Email da Ravinale Piemonte', 'ff88ff', $cal, true, false, true);
            $_tipo->createTipo($this->ATTIVITA_MANUALE, 'Attività manuali', 'ffffaa', $cal);
            $_tipo->createTipo($this->EMAIL_MANUALE, 'Email', 'ffff88', $cal, true, false, true);
            $_tipo->createTipo($this->RISCHEDULAZIONE, 'Rischedulazione', 'aaaaaa', $cal, true, false, false);
            $_tipo->createTipo($this->PRIORITA, 'Priorita', 'aaaaaa', $cal, false, false, false);
            $_tipo->createTipo($this->VERIFICA_PERIODICA, 'Verifica periodica', 'aaffaa', $cal);
            $_tipo->createTipo($this->CAMBIO_STATO_OPERATIVO, 'Cambio Stato Operativo', 'aaaaff', $cal, true, false, false);
            $_tipo->createTipo($this->CAMBIO_GESTORE, 'Cambio Gestore', 'aaaaaa', $cal, false, false, false);
        }
        return $cal;
    }

    /**
     * @param string $tipo
     * @return \Ephp\Bundle\CalendarBundle\Entity\Tipo
     */
    protected function getTipoEvento($sigla) {
        $cal = $this->getCalendar();
        $tipo = $this->findOneBy('EphpCalendarBundle:Tipo', array('calendario' => $cal->getId(), 'sigla' => $sigla));
        if (!$tipo) {
            $_tipo = $this->getEm()->getRepository('EphpCalendarBundle:Tipo');
            /* @var $_tipo \Ephp\Bundle\CalendarBundle\Entity\TipoRepository */
            switch ($sigla) {
                case $this->ANALISI_SINISTRI_COPERTURA:
                    $tipo = $_tipo->createTipo($this->ANALISI_SINISTRI_COPERTURA, 'Analisi Sinistri e Copertura', 'aaffaa', $cal, false);
                    break;
                case $this->VERIFICA_INCARICHI_MEDICI:
                    $tipo = $_tipo->createTipo($this->VERIFICA_INCARICHI_MEDICI, 'Verifica Incarichi e Medici', 'aaffaa', $cal, false);
                    break;
                case $this->RICERCA_POLIZZE_MEDICI:
                    $tipo = $_tipo->createTipo($this->RICERCA_POLIZZE_MEDICI, 'Ricerca Polizze e Medici', 'aaffaa', $cal, false);
                    break;
                case $this->RELAZIONE_RISERVA:
                    $tipo = $_tipo->createTipo($this->RELAZIONE_RISERVA, 'Relazione e Riserva', 'aaffaa', $cal, false);
                    break;
                case $this->RICHIESTA_SA:
                    $tipo = $_tipo->createTipo($this->RICHIESTA_SA, 'Richiesta di SA', 'aaffaa', $cal, false);
                    break;
                case $this->TRATTATIVE_AGGIORNAMENTI:
                    $tipo = $_tipo->createTipo($this->TRATTATIVE_AGGIORNAMENTI, 'Trattative e Aggiornamenti', 'aaffaa', $cal, false);
                    break;
                case $this->JWEB:
                    $tipo = $_tipo->createTipo($this->JWEB, 'J-Web Claims', 'ffaaaa', $cal);
                    break;
                case $this->EMAIL_JWEB:
                    $tipo = $_tipo->createTipo($this->EMAIL_JWEB, 'Email da J-Web Claims', 'ff8888', $cal, true, false, true);
                    break;
                case $this->CANCELLERIA_TELEMATICA:
                    $tipo = $_tipo->createTipo($this->CANCELLERIA_TELEMATICA, 'Cancelleria Telematiche', 'aaffff', $cal);
                    break;
                case $this->RAVINALE:
                    $tipo = $_tipo->createTipo($this->RAVINALE, 'Ravinale Piemonte', 'ffaaff', $cal);
                    break;
                case $this->EMAIL_RAVINALE:
                    $tipo = $_tipo->createTipo($this->EMAIL_RAVINALE, 'Email da Ravinale Piemonte', 'ff88ff', $cal, true, false, true);
                    break;
                case $this->ATTIVITA_MANUALE:
                    $tipo = $_tipo->createTipo($this->ATTIVITA_MANUALE, 'Attività manuali', 'ffffaa', $cal);
                    break;
                case $this->EMAIL_MANUALE:
                    $tipo = $_tipo->createTipo($this->EMAIL_MANUALE, 'Email', 'ffff88', $cal, true, false, true);
                    break;
                case $this->RISCHEDULAZIONE:
                    $tipo = $_tipo->createTipo($this->RISCHEDULAZIONE, 'Rischedulazione', 'aaaaaa', $cal, true, false, false);
                    break;
                case $this->PRIORITA:
                    $_tipo->createTipo($this->PRIORITA, 'Priorita', 'aaaaaa', $cal, false, false, false);
                    break;
                case $this->VERIFICA_PERIODICA:
                    $tipo = $_tipo->createTipo($this->VERIFICA_PERIODICA, 'Verifica periodica', 'aaffaa', $cal);
                    break;
                case $this->CAMBIO_STATO_OPERATIVO:
                    $tipo = $_tipo->createTipo($this->CAMBIO_STATO_OPERATIVO, 'Cambio Stato Operativo', 'aaaaff', $cal, true, false, false);
                    break;
                case $this->CAMBIO_GESTORE:
                    $tipo = $_tipo->createTipo($this->CAMBIO_GESTORE, 'Cambio Gestore', 'aaaaaa', $cal, false, false, false);
                    break;
            }
        }
        return $tipo;
    }

    /**
     * @param string $sigla
     * @param \Ephp\Bundle\SinistriBundle\Entity\Scheda $scheda
     * @param string $titolo
     * @param string $note
     * @return \Ephp\Bundle\SinistriBundle\Traits\Controller\Evento
     */
    protected function newEvento($sigla, \Ephp\Bundle\SinistriBundle\Entity\Scheda $scheda, $titolo = null, $note = "") {
        $oggi = new \DateTime();
        $cal = $this->getCalendar();
        $tipo = $this->getTipoEvento($sigla);
        $evento = new \Ephp\Bundle\SinistriBundle\Entity\Evento();
        $evento->setCalendario($cal);
        $evento->setTipo($tipo);
        $evento->setDataOra($oggi);
        $evento->setDeltaG(0);
        $evento->setGiornoIntero(true);
        $evento->setImportante(false);
        $evento->setNote($note);
        $evento->setOrdine(0);
        $evento->setRischedulazione(false);
        $evento->setScheda($scheda);
        $evento->setTitolo($titolo ? $titolo : $tipo->getNome());
        return $evento;
    }

}
