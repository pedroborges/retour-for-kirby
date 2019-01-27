<?php

namespace distantnative\Retour;

class System {

    public function toArray(): array
    {
        return [
            'site'    => site()->url(),
            'view'    => option('distantnative.retour.view', 'dashboard'),
            'license' => option('distantnative.retour.license')
        ];
    }

}
