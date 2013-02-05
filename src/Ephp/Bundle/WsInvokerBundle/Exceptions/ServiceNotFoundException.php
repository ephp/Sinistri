<?php

namespace Ephp\Bundle\WsInvokerBundle\Exceptions;


class ServiceNotFoundException extends \Exception {
    
    public function __construct($group, $service) {
        $message = "Service {$group}/{$service} not found";
        $code = '700';
        parent::__construct($message, $code, null);
    }
    
    public function __toString() {
        return $this->getCode().' - '. $this->getMessage();
    }

    
    
}

?>
