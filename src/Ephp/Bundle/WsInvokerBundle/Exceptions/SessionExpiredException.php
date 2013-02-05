<?php

namespace Ephp\Bundle\WsInvokerBundle\Exceptions;


class SessionExpiredException extends \Exception {
    
    public function __construct() {
        $message = "Session Expired";
        $code = '401';
        parent::__construct($message, $code, null);
    }
    
    public function __toString() {
        return $this->getCode().' - '. $this->getMessage();
    }

    
    
}

?>
