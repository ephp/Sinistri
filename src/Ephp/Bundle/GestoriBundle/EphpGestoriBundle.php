<?php

namespace Ephp\Bundle\GestoriBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EphpGestoriBundle extends Bundle {

    public function getParent() {
        return 'EphpACLBundle';
    }

}
