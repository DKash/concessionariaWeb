<?php

namespace banco;

use libraries\drivers\ConexaoPDO;

class Veiculo extends Metodos_Genericos {
    /**
     * @id
     */
    private $idVeiculo;

    private $veiculo;
    private $marca;
    private $ano;
    private $descricao;
    private $vendido;
    /**
     * @tipo Date
     */
    private $created;
    /**
     * @tipo Date
     */
    private $updated;

    /**
     * @method
     */
    public function __construct() {
        $this->banco = new ConexaoPDO($this);
    }

    /**
     * @return mixed
     */
    public function getIdVeiculo() {
        return $this->idVeiculo;
    }

    /**
     * @param mixed $idVeiculo
     */
    public function setIdVeiculo($idVeiculo) {
        $this->idVeiculo = $idVeiculo;
    }

    /**
     * @return mixed
     */
    public function getVeiculo() {
        return $this->veiculo;
    }

    /**
     * @param mixed $veiculo
     */
    public function setVeiculo($veiculo) {
        $this->veiculo = $veiculo;
    }

    /**
     * @return mixed
     */
    public function getMarca() {
        return $this->marca;
    }

    /**
     * @param mixed $marca
     */
    public function setMarca($marca) {
        $this->marca = $marca;
    }

    /**
     * @return mixed
     */
    public function getAno() {
        return $this->ano;
    }

    /**
     * @param mixed $ano
     */
    public function setAno($ano) {
        $this->ano = $ano;
    }

    /**
     * @return mixed
     */
    public function getDescricao() {
        return $this->descricao;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    /**
     * @return mixed
     */
    public function getVendido() {
        return $this->vendido;
    }

    /**
     * @param mixed $vendido
     */
    public function setVendido($vendido) {
        $this->vendido = $vendido;
    }

    /**
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created) {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated() {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated) {
        $this->updated = $updated;
    }
}