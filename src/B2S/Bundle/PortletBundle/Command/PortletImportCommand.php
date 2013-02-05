<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace B2S\Bundle\PortletBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use B2S\Bundle\PortletBundle\Command\Helper\DialogHelper;
use Doctrine\ORM\Mapping\MappingException;
use B2S\Bundle\WebBundle\Functions\Funzioni;
use Doctrine\Bundle\DoctrineBundle\Mapping\MetadataFactory;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use B2S\Bundle\PortletBundle\Entity\Portlet;

/**
 * Generates a CRUD for a Doctrine entity.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class PortletImportCommand extends DoctrineCommand {

    /**
     * @see Command
     */
    protected function configure() {
        $this
                ->setDefinition(array())
                ->setDescription('Importa le nuove portlet')
                ->setHelp(<<<EOT
The <info>b2s:portlet:import</info> command import new portlets in defined table.

<info>php app/console b2s:portlet:import</info>
EOT
                )
                ->setName('b2s:portlet:import')
                ->setAliases(array('b2s:portlet:import'))
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $dialog = $this->getDialogHelper();
        $dialog->writeSection($output, 'Import Portlet', 'bg=white;fg=black');

        $areaClass = $this->getContainer()->get('doctrine')->getEntityNamespace('B2SPortletBundle') . '\\Area';
        $entityClass = $this->getContainer()->get('doctrine')->getEntityNamespace('B2SPortletBundle') . '\\Portlet';
        $conn = $this->getDoctrineConnection('default');
        $em = $this->getEntityManager('default');

        $_area = $em->getRepository($areaClass);
        $_portlet = $em->getRepository($entityClass);
        $_portlet->removeAllArea();

        $portlet_bundles = array(
            'B2SPortletBundle',
            'BringOutFontiBundle',
        );

        $exclude = array('.', '..', 'portlets.xml');

        foreach ($portlet_bundles as $bundle_nome) {
            $bundle = $this->getContainer()->get('kernel')->getBundle($bundle_nome);
            $bundle_namespace = get_class($bundle);
            echo "\n\n" . $bundle_namespace;
            $path = $bundle->getPath();
            $sep = $path{0} == '/' ? '/' : '\\';
            $path = $path . "{$sep}Resources{$sep}views{$sep}Portlets";
            echo "\n";
            if ($handle_action = opendir($path)) {
                while (false !== ($action = readdir($handle_action))) {
                    if (!in_array($action, $exclude) && strpos($action, 'twig') === false) {
                        echo "- $action\n";
                        if ($handle_layout = opendir($path . $sep . $action)) {
                            if (!file_exists($path . $sep . $action . $sep . 'portlets.xml')) {
                                $msg = "File portlets.xml mancante in {$bundle_nome}:Portlet:{$action}";
                                $dialog->writeSection($output, $msg, 'bg=white;fg=red');
                                throw new \Exception($msg);
                            }
                            $xml_name = $path . $sep . $action . $sep . 'portlets.xml';
                            $handle = fopen($xml_name, 'r');
                            $stringa_xml = fread($handle, filesize($xml_name));
                            fclose($handle);
                            if (false) {
                                $this->log($stringa_xml);
                            }
                            $xml = new \SimpleXMLElement($stringa_xml);
                            $xml = $xml->portlets;
                            while (false !== ($layout = readdir($handle_layout))) {
                                if (!in_array($layout, $exclude) && strpos($layout, 'twig') === false) {
                                    $portlet = $_portlet->findOneBy(array('bundle' => $bundle_nome, 'action' => $action, 'layout' => $layout));
                                    echo "    - $layout : " . ($portlet ? 'Esiste' : 'Manca') . "\n";
                                    if (!$portlet) {
                                        $portlet = new Portlet();
                                        $portlet->setBundle($bundle_nome);
                                        $portlet->setAction($action);
                                        $portlet->setLayout($layout);
                                    }
                                    $find = false;
                                    foreach ($xml->portlet as $info) {
                                        $info_attr = $info->attributes();
                                        if (!isset($info_attr['layout'])) {
                                            $msg = "Attributo LAYOUT mancante in {$bundle_nome}:Portlet:{$action}/portlets.xml";
                                            $dialog->writeSection($output, $msg, 'bg=white;fg=red');
                                            throw new \Exception($msg);
                                        }
                                        $_layout = $info_attr['layout']->__toString();
                                        if ($_layout == $layout) {
                                            $portlet->setNome($info->nome->__toString());
                                            $portlet->setDescrizione($info->descrizione->__toString());
                                            $portlet->setPubblico($info->pubblico->__toString() == 1);
                                            $portlet->setPrivato($info->privato->__toString() == 1);
                                            $find = true;
                                            /*
                                            foreach($info->aree->area as $area) {
                                                $area = $_area->findOneBy(array('nome' => $area->__toString()));
                                                $portlet->addAree($area);
                                            }
                                             */
                                        }
                                    }
                                    if(!$find) {
                                        $msg = "Layout {$layout} mancante in {$bundle_nome}:Portlet:{$action}/portlets.xml";
                                        $dialog->writeSection($output, $msg, 'bg=white;fg=red');
                                        throw new \Exception($msg);
                                    }
                                    $em->persist($portlet);
                                    $em->flush();
                                }
                            }
                            closedir($handle_layout);
                        }
                    }
                }
                closedir($handle_action);
            }
        }



        $bundle = new \B2S\Bundle\ShowRoomBundle\B2SShowRoomBundle();


        $dialog->writeSection($output, $entityClass);
        ;
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEm() {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        return $em;
    }

    /**
     * 
     * @return \B2S\Bundle\PortletBundle\Command\Helper\DialogHelper
     */
    protected function getDialogHelper() {
        $dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog || get_class($dialog) !== 'B2S\Bundle\PortletBundle\Command\Helper\DialogHelper') {
            $this->getHelperSet()->set($dialog = new DialogHelper());
        }
        
        return $dialog;
    }

}
