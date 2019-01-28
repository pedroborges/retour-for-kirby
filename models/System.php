<?php

namespace distantnative\Retour;

class System {

    public function toArray(): array
    {
        $kirby  = kirby();
        $plugin = $kirby->plugin('distantnative/retour');

        return [
            'description' => $plugin->description(),
            'version'     => $plugin->version(),
            'site'        => $kirby->site()->url(),
            'license'     => option('distantnative.retour.license'),
            'view'        => option('distantnative.retour.view'),
            'limit'       => option('distantnative.retour.limit'),
        ];
    }

}
