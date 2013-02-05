<?php

namespace Ephp\Bundle\WsInvokerBundle\Functions;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;

class Log {

    public static $STRING = 0;
    public static $PR = 1;
    public static $VD = 2;
    public static $CLASS = 4;
    public static $COUNT = 8;
    
    private static $MAX = 15;
    
    public static function info(Controller $controller, $object, $text = '', $mode = 0) {
        $message = self::getMessage($object, $text, $mode);
        $logger = $controller->get('logger');
        /* @var $logger \Symfony\Bridge\Monolog\Logger */
        $pre = Funzioni::typeof($controller, true);
        $logger->info("{$pre} | {$message}");
    }
    public static function notice(Controller $controller, $object, $text = '', $mode = 0) {
        $message = self::getMessage($object, $text, $mode);
        $logger = $controller->get('logger');
        /* @var $logger \Symfony\Bridge\Monolog\Logger */
        $pre = Funzioni::typeof($controller, true);
        $logger->notice("{$pre} | {$message}");
    }
    public static function debug(Controller $controller, $object, $text = '', $mode = 0) {
        $message = self::getMessage($object, $text, $mode);
        $logger = $controller->get('logger');
        /* @var $logger \Symfony\Bridge\Monolog\Logger */
        $pre = Funzioni::typeof($controller, true);
        $logger->debug("{$pre} | {$message}");
    }
    public static function alert(Controller $controller, $object, $text = '', $mode = 0) {
        $message = self::getMessage($object, $text, $mode);
        $logger = $controller->get('logger');
        /* @var $logger \Symfony\Bridge\Monolog\Logger */
        $pre = Funzioni::typeof($controller, true);
        $logger->alert("{$pre} | {$message}");
    }
    public static function warning(Controller $controller, $object, $text = '', $mode = 0) {
        $message = self::getMessage($object, $text, $mode);
        $logger = $controller->get('logger');
        /* @var $logger \Symfony\Bridge\Monolog\Logger */
        $pre = Funzioni::typeof($controller, true);
        $logger->warn("{$pre} | {$message}");
    }
    public static function error(Controller $controller, $object, $text = '', $mode = 0) {
        $message = self::getMessage($object, $text, $mode);
        $logger = $controller->get('logger');
        /* @var $logger \Symfony\Bridge\Monolog\Logger */
        $pre = Funzioni::typeof($controller, true);
        $logger->err("{$pre} | {$message}");
    }
    public static function critic(Controller $controller, $object, $text = '', $mode = 0) {
        $message = self::getMessage($object, $text, $mode);
        $logger = $controller->get('logger');
        /* @var $logger \Symfony\Bridge\Monolog\Logger */
        $pre = Funzioni::typeof($controller, true);
        $logger->crit("{$pre} | {$message}");
    }
    public static function emergency(Controller $controller, $object, $text = '', $mode = 0) {
        $message = self::getMessage($object, $text, $mode);
        $logger = $controller->get('logger');
        /* @var $logger \Symfony\Bridge\Monolog\Logger */
        $pre = Funzioni::typeof($object, true);
        $logger->emerg("{$pre} | {$message}");
    }

    private static function getMessage($object, $text = '', $mode = 0) {
        if(is_int($text) && $text <= self::$MAX) {
            $mode = $text;
            $text = '';
        }
        $message = '';
        switch($mode) {
            case self::$COUNT:
                if(is_array($object)) {
                    oggetto_array:
                    $message .= "ARRAY con ".count($object).' elementi';
                    break;
                }
                if($object instanceof \Traversable) {
                    $message .= "OGGETTO "+get_class($s)+" con ".count($object).' elementi';
                    break;
                }
                $message .= "Variabile non iterabile: ";
            case self::$CLASS:
                $message .= Funzioni::typeof($object, true);
                break;
            case self::$VD:
                $message .= Funzioni::var_dump($object, true);
                break;
            case self::$PR:
                $message .= Funzioni::print_r($object);
                break;
            case self::$STRING:
                if(is_bool($object)) {
                    $message .= 'Valore booleano: ';
                    $message .= $object ? 'TRUE' : 'FALSE';
                } elseif(is_array($object)) {
                    goto oggetto_array;
                } elseif(!is_object($object)) {
                    $message .= $object;
                } else {
                    $message .= Funzioni::typeof($object, true);
                    $methods = get_class_methods($object);
                    if(in_array('__toString', $methods)) {
                        $message .= ': '.$object.__toString();
                    }
                }
                break;
        }
        
        return ($text != '' ? "{$text}: " : '').$message;
    }
}
