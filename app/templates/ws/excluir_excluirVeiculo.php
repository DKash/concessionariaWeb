<?php

$idVeiculo = json_decode($data[1]->dados);

$veiculo = new \banco\Veiculo();

/**
 * Bloco que faz a consulta dos dados do veículo no banco
 */
try {
    $controle = false;
    $retorno = null;
    $Obj_veiculo = null;

    $Obj_veiculo = $veiculo->banco->queryObject("select idVeiculo from Veiculo as v where v.idVeiculo =:idVeiculo",
                                                array("idVeiculo" => $idVeiculo));

    if (!is_object($Obj_veiculo)) {
        throw new \Exception("Falha ao recuperar os dados do veículo");
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
        $veiculo->banco->desconectar();
        $retorno = array("error" => true, "mensagem" => $mensagem);
    } else {
        /**
         * Bloco que faz a exclusão do Veículo
         */
        try {
            $controle1 = false;
            $retorno = null;

            $deleted = $veiculo->banco->delete("delete v from Veiculo as v where v.idVeiculo =:idVeiculo",
                                               array("idVeiculo" => $idVeiculo));

            if (is_null($deleted) || $deleted === 0) {
                throw new \Exception("Falha ao excluir o Veículo");
            }
        } catch (\PDOException $p) {
            $controle1 = true;
            $mensagem = "Erro interno: 001";
            error_log("Excessao capturada -> " . $p->getMessage());
        } catch (\Exception $e) {
            $controle1 = true;
            $mensagem = $e->getMessage();
            error_log("Excessao capturada -> " . $e->getMessage());
        } finally {
            if ($controle1) {
                $veiculo->banco->desconectar();
                $retorno = array("error" => true, "mensagem" => $mensagem);
            } else {
                $mensagem = "Veículo excluído com sucesso";
                $retorno = array("error" => false, "mensagem" => $mensagem);
            }
        }
    }
    echo json_encode($retorno, JSON_NUMERIC_CHECK);
}