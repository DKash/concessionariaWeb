<?php

$obj = json_decode($data[1]->dados);

$veiculo = new \banco\Veiculo();

/**
 * Bloco que faz a consulta dos dados do veículo no banco
 */
try {
    $controle = false;
    $retorno = null;
    $Obj_veiculo = null;

    $Obj_veiculo = $veiculo->banco->queryObject("select * from Veiculo as v where v.idVeiculo =:idVeiculo",
                                                array("idVeiculo" => $obj->idVeiculo));

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
         * Bloco que faz a atualização dos dados do Veículo
         */
        try {
            $controle1 = false;
            $tipoPDO = false;
            $retorno = null;
            $agora = new DateTime();

            $veiculo_params = array("veiculo", "marca", "ano", "descricao", "vendido", "updated");
            $veiculo_values = array($obj->veiculo, $obj->marca, $obj->ano, $obj->descricao,
                $obj->vendido, $agora->format("Y-m-d H:i:s"));
            $veiculo->banco->update($veiculo_params, $veiculo_values, $obj->idVeiculo);

        } catch (\PDOException $p) {
            $controle1 = true;
            $tipoPDO = true;
            $mensagem = "Erro interno: 001";
            error_log("Excessao capturada -> " . $p->getMessage());
        } catch (\Exception $e) {
            $controle1 = true;
            $mensagem = $e->getMessage();
            error_log("Excessao capturada -> " . $e->getMessage());
        } finally {
            if ($controle1) {
                if (!$tipoPDO) {
                    /**
                     * Bloco que faz a reversão da atualização do Veículo
                     */
                    try {
                        $controle1 = false;
                        $tipoPDO = false;
                        $retorno = null;

                        $veiculo_params = array("veiculo", "marca", "ano", "descricao", "vendido", "updated");
                        $veiculo_values = array($Obj_veiculo->getVeiculo(), $Obj_veiculo->getMarca(), $Obj_veiculo->getAno(), $Obj_veiculo->getDescricao(),
                            $Obj_veiculo->getVendido(), $Obj_veiculo->getUpdated());
                        $veiculo->banco->update($veiculo_params, $veiculo_values, $obj->idVeiculo);
                    } catch (\PDOException $p) {
                        error_log("ExcecaoPDO -> " . $p->getMessage());
                    } catch (\Exception $e) {
                        error_log("Excecao -> " . $e->getMessage());
                    }
                    $mensagem = "Falha ao atualizar os dados do Veículo";
                }
                $veiculo->banco->desconectar();
                $retorno = array("error" => true, "mensagem" => $mensagem);
            } else {
                $mensagem = "Veículo atualizado com sucesso";
                $retorno = array("error" => false, "mensagem" => $mensagem);
            }
        }
    }
    echo json_encode($retorno, JSON_NUMERIC_CHECK);
}