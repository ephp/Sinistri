<?php

namespace Ephp\Bundle\WsInvokerBundle\Functions;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use B2S\Bundle\PortletBundle\Entity\Portlet;
use B2S\Bundle\PortletBundle\Entity\PortletRepository;
use B2S\Bundle\PortletBundle\Entity\PortletPagina;
use B2S\Bundle\PortletBundle\Entity\Pagina;

class Funzioni {

    const TERRA = 6372.795477598;

    public static function json($o, $continue = true) {
        $json = json_encode($o);
        if (!$continue) {
            echo $json;
            exit;
        }
        return $json;
    }
    
    public static function print_r($o) {
        return print_r($o, true);
    }

    public static function pr($o, $continue = false) {
        echo "<pre>";
        echo self::print_r($o);
        echo "</pre>";
        if (!$continue)
            exit;
    }

    public static function var_dump($o) {
        ob_start();
        var_dump($o);
        $out = ob_get_contents();
        ob_end_clean();
        return $out;
    }
    
    public static function vd($o, $continue = false) {
        var_dump($o);
        if (!$continue)
            exit;
    }

    public static function typeof($s) {
        return (gettype($s) == 'object' ? 'Classe: ' . get_class($s) : 'Tipo: ' . gettype($s));
    }

    public static function info($s, $continue = false) {
        echo self::typeof($s);
        if (!$continue)
            exit;
    }

    public static function infoPr($s, $continue = false) {
        echo '<pre>'.(gettype($s) == 'object' ? 'Classe: ' . get_class($s) : 'Tipo: ' . gettype($s)).'<pre>';
        self::pr($s, $continue);
    }

    public static function infoVd($s, $continue = false) {
        echo '<pre>'.(gettype($s) == 'object' ? 'Classe: ' . get_class($s) : 'Tipo: ' . gettype($s)).'<pre>';
        self::vd($s, $continue);
    }

    public static function alfabeto() {
        return array(
            'A', 'B', 'C', 'D', 'E',
            'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y',
            'Z'
        );
    }

    public static function staticMap($lat, $lon, $x, $y, $zoom, $color = '337FC5', $type = 'roadmap') {
        return "http://maps.google.com/maps/api/staticmap?center={$lat},{$lon}&zoom={$zoom}&size={$x}x{$y}&maptype={$type}&sensor=true&markers=color:0x{$color}|{$lat},{$lon}&markers=size:tiny";
    }

    public static function getDistanzaRad($da, $a) {
        $da['lat'] = floatval($da['lat']);
        $da['lon'] = floatval($da['lon']);
        $a['lat'] = floatval($a['lat']);
        $a['lon'] = floatval($a['lon']);
        return acos(
                        (
                        sin($da['lat'])
                        *
                        sin($a['lat'])
                        ) + (
                        cos($da['lat'])
                        *
                        cos($a['lat'])
                        *
                        cos($da['lon'] - $a['lon'])
                        )
                ) * self::TERRA;
    }

    public static function getDistanza($da, $a) {
        return (
                acos(
                        (
                        sin(
                                deg2rad($da['lat'])
                        ) * sin(
                                deg2rad($a['lat'])
                        )
                        ) + (
                        cos(
                                deg2rad($da['lat'])
                        ) * cos(
                                deg2rad($a['lat'])
                        ) * cos(
                                deg2rad($da['lon'] - $a['lon'])
                        )
                        )
                )
                ) * 6372.795477598;
    }

    public static function getFrmoArray($params, $name, $default = null) {
        return isset($params[$name]) ? $params[$name] : $default;
    }
    
    public static function tronca($testo, $lunghezza, $space = true) {
        $testo = str_replace('  ', ' ', strip_tags(str_replace('<', ' <', $testo)));
        if (strlen($testo) <= $lunghezza) {
            return $testo;
        }
        if($space) {
            $len = strpos($testo, ' ', $lunghezza);
            return substr($testo, 0, $len) . '...';
        } else {
            return substr($testo, 0, $lunghezza-3) . '...';
        }
    }

    public static function km($distanza) {
        $distanza = floatval($distanza);
        if ($distanza < 0) {
            return 'n.d.';
        }
        if ($distanza < 1) {
            $distanza = $distanza * 1000;
            if ($distanza < 1) {
                return '1m';
            }
            if ($distanza < 100) {
                return round($distanza) . 'm';
            }
            return round($distanza, -1) . 'm';
        }
        if ($distanza < 10) {
            return round($distanza, 1) . 'km';
        }
        return round($distanza) . 'km';
    }

    public static function time($label, $start, $continue = true) {
        $end = microtime(true);
        $time = $end - $start;
        echo "<pre><b>{$label}</b>: {$time} secondi</pre>\n";
        if (!$continue)
            exit;
        return $end;
    }

    public static function cercaRegExp($testo, $find) {
        $parole = explode(' ', $find);
        foreach ($parole as $k => $parola) {
            $parole[$k] = "({$parola}){1}";
        }
        $regexp = implode('[a-z0-9\.\, \-]+', $parole);
        preg_match_all("/{$regexp}/i", $testo, $match);
        return count($match[0]) > 0;
    }

    public static function createAlias($dir, $slug, $domain, \Doctrine\ORM\EntityManager $em, $old = false) {
        if ($old) {
            $return = system('ssh root@mail.ecossekr.com \"rm /var/qmail/{$dir}.qmail-{$old}\"');
            if ($return == 0) {
                
            }
        }
        $separatore = $dir{0} == '/' ? ':' : '-';
        $count = null;
        $name = false;
        try {
            $_user = $em->getRepository('B2S\Bundle\ACLBundle\Entity\UserShowroom');
            do {
                $email = str_replace('-', '.', $slug) . ($count ? '.'.$count : '') . '@' . $domain;
                if(!$_user->findOneBy(array('email' => $email))) {
                    $name = str_replace('-', $separatore, $slug) . ($count ? '.'.$count : '') ;
                }
            } while ($name === false);
            $handle = fopen("{$dir}.qmail-{$name}", 'w+');
            $rows = array();
            //$rows[] = "&{$email}";
            $rows[] = "messenger";
            fwrite($handle, implode("\n", $rows));
            fclose($handle);
            $command = "scp {$dir}.qmail-{$name} root@mail.{$domain}:/var/qmail/alias";
            exec($command, $output, $retval);
            if ($retval != 0) {
                throw new \Exception(print_r($output, true));
            }
        } catch (\Exception $e) {
            return ' ' . str_replace($separatore, '.', $name) . '@' . $domain;
        }
        return str_replace($separatore, '.', $name) . '@' . $domain;
    }

    public static function isEmail($email) {
        $regexp_email = "^([a-zA-Z0-9_\\.\\-\\+])+\\@(([a-zA-Z0-9\\-])+\\.)+([a-zA-Z0-9]{2,4})+$";
        preg_match("/{$regexp_email}/", $email, $match);
        return count($match) > 0;
    }

    public static function pulisci($frase) {
        $remove = $replace = array();
        $not_remove = array_merge(array(32, 39, 45, 46, 47), range(48, 57), range(65, 90), range(97, 122));
        for ($i = 0; $i < 127; $i++) {
            if (!in_array($i, $not_remove)) {
                $remove[] = chr($i);
                $replace[] = ' ';
            }
        }
        for ($i = 65; $i < 91; $i++) {
            $remove[] = chr($i);
            $replace[] = chr($i + 32);
        }
        $remove = array_merge(array('.', '`', '&agrave;', '&egrave;', '&eacute;', '&igrave;', '&ograve;', '&ugrave;'), $remove, array('     ', '    ', '   ', '  '));
        $replace = array_merge(array('', '\'', 'a', 'e', 'e', 'i', 'o', 'u'), $replace, array(' ', ' ', ' ', ' '));

        return str_replace($remove, $replace, strip_tags($frase));
    }

    public static function normalizza($frase) {
        return strtolower(str_replace(
                                array('`', "'A", "'E", "'I", "'O", "'U"), array('', 'a', 'e', 'i', 'o', 'u'), iconv("utf-8", "ascii//TRANSLIT", $frase)
                        ));
    }
    
    public static function ripulisci($frase) {
        $frase = str_replace(array('>', "\n", "\r"), array('> ', ' ', ' '), $frase);
        $frase = strip_tags($frase);
        $frase = self::pulisci($frase);
        $frase = self::normalizza($frase);
        return $frase;
    }
    
    public static function cancellaRicerca($session) {
        $old = $session->get('search.id', false);
        if ($old) {
            $session->remove($old);
            $session->remove('search.id');
        }
    }
    
    public static function generaPortlet($portlet_info, $i, Pagina &$pagina, PortletRepository $_portlet, \Doctrine\ORM\EntityManager $em) {
        $portlet = $_portlet->trova($portlet_info[1], $portlet_info[2], $portlet_info[3]);
        $portlet_pagina = new PortletPagina();
        $portlet_pagina->setAreaLayout($portlet_info[0]);
        $portlet_pagina->setOrdine($i);
        $portlet_pagina->setOrdineEdit(false);
        $portlet_pagina->setPagina($pagina);
        $portlet_pagina->setPortlet($portlet);
        $em->persist($portlet_pagina);
        $em->flush();
        if(isset($portlet_info[4])) {
            $i = 0;
            foreach ($portlet_info[4] as $proprieta => $valore) {
                $configurazione = new \B2S\Bundle\PortletBundle\Entity\Configurazione();
                $configurazione->setValore($valore);
                $configurazione->setProprieta($proprieta);
                $configurazione->setProgressivo($i);
                $configurazione->setPortlet($portlet_pagina);
                $em->persist($configurazione);
                $em->flush();
            }
        }
        $pagina->addPortlets($portlet_pagina);
    }
    
    public static function getPortlet(\B2S\Bundle\PortletBundle\Entity\PortletPagina $portlet_pagina, array $params) {
        $portlet = $portlet_pagina->getPortlet();
        if(!$portlet) {
            self::vd($portlet_pagina);
        }
        $bundle = str_replace('Professionista', 'Profilo', $portlet->getBundle());
        $name = "{$bundle}:Portlet{$portlet->getAction()}:index";
        return array(
            'action' => $name,
            'params' => array_merge($params, $portlet_pagina->getParams(), array('layout' => $portlet->getLayout())),
        );
    }

}
