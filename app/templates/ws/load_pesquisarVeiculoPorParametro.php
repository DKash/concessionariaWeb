<?php

$parametro = json_decode($data[1]->dados);

$mensagem = "";

/**
 * Bloco que faz a consulta de todos os veiculos com a caracterica defina em parametro
 */
try {
    $controle = false;
    $retorno = null;
    $veiculo = new \banco\Veiculo();
    $list_veiculos = null;

    $list_veiculos = $veiculo->banco->queryList("select * from Veiculo as v where v.veiculo =:parametro or 
                                                v.marca =:parametro or v.ano =:parametro",
                                                array("parametro" => $parametro));

    if (!is_array($list_veiculos)) {
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
        if (count($list_veiculos) > 0) {
            foreach ($list_veiculos as $value) {

                $obj_veiculo = (object) array("idVeiculo" => $value->getIdVeiculo(), "veiculo" => $value->getVeiculo(),
                    "marca" => $value->getMarca(), "ano" => $value->getAno(), "descricao" => $value->getDescricao(),
                    "vendido" => $value->getVendido(), "created" => $value->getCreated(), "updated" => $value->getUpdated());

                array_push($dados_veiculos, $obj_veiculo);
            }
        }
        $veiculo->banco->desconectar();
        $retorno = array("error" => false, "veiculos" => json_decode(json_encode($dados_veiculos), true));
    }
    echo json_encode($retorno, JSON_NUMERIC_CHECK);
}
