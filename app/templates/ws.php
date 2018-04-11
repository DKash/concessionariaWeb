<?php

include '../../vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');
$data = file_get_contents('php://input');
$data = json_decode($data);

$modulo = $data[0]->modulo;
$funcao = $data[0]->funcao;

if ($modulo === "salvar") {
    if ($funcao === "cadastrarVeiculo") {
        include_once "ws/salvar_cadastrarVeiculo.php";
    }
} else if ($modulo === "editar") {
    if ($funcao === "atualizarVeiculo") {
        include_once "ws/editar_atualizarVeiculo.php";
    }
} else if ($modulo === "load") {
    if ($funcao === "carregarVeiculos") {
        include_once "ws/load_carregarVeiculos.php";
    } else if ($funcao === "pesquisarVeiculoPorParametro") {
        include_once "ws/load_pesquisarVeiculoPorParametro.php";
    }
} else if ($modulo === "excluir") {
    if ($funcao === "excluirVeiculo") {
        include_once 'ws/excluir_excluirVeiculo.php';
    }
} else {
    error_log("chamada invalida detectada");
}