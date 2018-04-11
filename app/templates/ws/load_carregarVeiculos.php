<?php

$mensagem = "";

try {
    $controle = false;
    $retorno = null;
    $veiculo = new \banco\Veiculo();
    $list_veiculos = null;

    $list_veiculos = $veiculo->banco->queryAll("select * from Veiculo");

    if (!is_array($list_veiculos) || count($list_veiculos) == 0) {
        throw new \Exception("Falha ao recuperar os dados dos veículos");
    }
} catch (\PDOException $p) {
    $controle = true;
    $mensagem = "Erro interno: 001";
    error_log("Excessao capturada -> " . $p->getMessage());
} catch (\Exception $e) {
    $controle = true;
    $mensagem = $e->getMessage();
    error_log("Excessao capturada -> " . $e->getMessage());
} finally {
    if ($controle) {
        $retorno = array("error" => true, "mensagem" => $mensagem);
    } else {
        $dados_veiculos = array();
        foreach ($list_veiculos as $value) {

            $obj_veiculo = (object) array("idVeiculo" => $value->getIdVeiculo(), "veiculo" => $value->getVeiculo(),
                "marca" => $value->getMarca(), "ano" => $value->getAno(), "descricao" => $value->getDescricao(),
                "vendido" => $value->getVendido(), "created" => $value->getCreated(), "updated" => $value->getUpdated());

            array_push($dados_veiculos, $obj_veiculo);
        }

        $retorno = array("error" => false, "veiculos" => json_decode(json_encode($dados_veiculos), true));
    }
    $veiculo->banco->desconectar();

    echo json_encode($retorno, JSON_NUMERIC_CHECK);
}