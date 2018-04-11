<?php

namespace banco;

use libraries\drivers\ConexaoPDO;

abstract class Metodos_Genericos {
    /**
     * @var ConexaoPDO
     */
    public $banco;
}