<?php

$obj = json_decode($data[1]->dados);
$mensagem = "";

/**
 * Bloco que faz o cadastro do Veículo no banco
 */
try {
    $controle = false;
    $retorno = null;
    $veiculo = new \banco\Veiculo();
    $agora = new DateTime();

    $veiculo_params = array("veiculo", "marca", "ano", "descricao", "vendido", "created");
    $veiculo_values = array($obj->veiculo, $obj->marca, $obj->ano, $obj->descricao, $obj->vendido, $agora->format("Y-m-d H:i:s"));
    $idVeiculo = $veiculo->banco->insert($veiculo_params, $veiculo_values);

    if (is_null($idVeiculo) && $idVeiculo == 0) {
        throw new \Exception("Falha ao cadastrar o Veículo");
    }
} catch (\PDOException $p) {
    if ($veiculo->banco->pdo->inTransaction()) {
        $veiculo->banco->pdo->rollBack();
    }
    $controle = true;
    $mensagem = "Erro interno: 001";
    error_log("Excessao capturada -> " . $p->getMessage());
} catch (\Exception $e) {
    $controle = true;
    $mensagem = $e->getMessage();
    error_log("Excessao capturada -> " . $e->getMessage());
} finally {
    if ($controle) {
        if (!is_null($idVeiculo)) {
            /**
             * Bloco que faz a exclusão do Veículo
             */
            try {
                $deleted = $veiculo->banco->delete("delete v from Veiculo as v where v.idVeiculo =:idVeiculo",
                                                   array("idVeiculo" => $idVeiculo));

                if (is_null($deleted)) {
                    throw new \Exception("Proceso de reversão não concluido em Veículo");
                }
            } catch (\PDOException $p) {
                error_log("ExcecaoPDO: " . $p->getMessage());
            } catch (\Exception $e) {
                error_log("Excecao: " . $e->getMessage());
            }
        }
        $veiculo->banco->desconectar();
        $retorno = array("error" => true, "mensagem" => $mensagem);
    } else {
        $mensagem = "Veículo cadastrado com sucesso";
        $retorno = array("error" => false, "mensagem" => $mensagem);
    }
    echo json_encode($retorno, JSON_NUMERIC_CHECK);
}