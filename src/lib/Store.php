<?php

namespace distantnative\Retour;

use Kirby\Data\Data;
use Kirby\Toolkit\F;

class Store {

    protected $data;
    protected $file;

    public function data()
    {
        if ($this->data) {
            return $this->data;
        }

        return $this->read();
    }

    protected static function defaults(): array
    {
        return [];
    }

    public function read()
    {
        if (F::exists($this->file) === false) {
            return $this->data = static::defaults();
        }

        return $this->data = Data::read($this->file, 'yaml');
    }

    public function write(array $data = []): void
    {
        Data::write($this->file, $data, 'yaml');
        $this->data = $data;
    }

}
