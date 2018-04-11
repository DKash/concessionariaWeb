<div class="container-fluid painel">
    <div class="row painel">
        <div class="col-md-6 form-inline">
            <img ng-src="/assets/global/img/littletear.png" class="imagem">
            <div class="title">FULLSTACK</div>
        </div
        <div class="col-md-6">
            <input ng-disabled="veiculos.length === 0" class="col-md-5 search" ng-model="searchVeiculo"
                   placeholder="BUSCA por um veículo"/>
            <button ng-disabled="veiculos.length === 0" class="col-md-1 btn btn-outline-info" ng-click="pesquisarVeiculosPor(searchVeiculo)">Pesquisar</button>
        </div>
    </div>
    <div class="container-fluid veiculo" style="background-color: #e3e6e3;min-height: max-content">
        <div class="row">
            <h3 class="col-md-11 headVeiculo">VEÍCULO</h3>

            <i data-toggle='collapse'
               ng-click="exibirModalCadastro()"
               class="fa fa-plus-circle iconeNovoVeiculo" aria-hidden="true"></i>
        </div>
        <div class="borderLineBottom"></div>

        <br/><br/>

        <div class="row">
            <div class="col-md-6">
                <h4>Lista de Veículos</h4>

                <div class="listaVeiculos">
                    <div ng-if="veiculos.length === 0" class="textCenter">
                        <b>Não há veículos cadastrados</b></div>

                    <table class="table table-bordered veiculos">
                        <tbody>
                        <tr ng-repeat="v in veiculos | orderBy:'marca'">
                            <td>
                                <div class="row">
                                    <div class="col-md-11 control-label">
                                        {{v.marca | uppercase}}
                                        <div class="modeloVeiculo">{{v.veiculo}}</div>
                                        <div class="anoVeiculo">{{v.ano}}</div>
                                    </div>
                                    <img ng-if="veiculoSelecionado === undefined || v.idVeiculo !== veiculoSelecionado.idVeiculo" id="imageNormal"
                                         ng-src="/assets/global/img/tag.png" style="cursor: pointer"
                                         ng-click="selecionarVeiculo(v.idVeiculo)" width="30px" height="30px"/>

                                    <img ng-if="veiculoSelecionado !== undefined && v.idVeiculo === veiculoSelecionado.idVeiculo" id=" imageVeiculoSelected"
                                         ng-src="/assets/global/img/tag_green.png" style="cursor: pointer"
                                         ng-click="selecionarVeiculo(v.idVeiculo)" width="30px" height="30px"/>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="animate-repeat" style="text-align: center;margin-bottom: 20px" ng-if="veiculos === null"><b>Nenhum resultado foi encontrado.</b></div>
                </div>
            </div>
            <div class="col-md-6">
                <h4>Detalhes</h4>
                <div ng-if="veiculoSelecionado === undefined && veiculos.length > 0" style="margin-top: 50px" class="textCenter"><b>Selecione um veículo para visualizar os detalhes<b>
                </div>
                <div class="whiteBG" ng-if="veiculoSelecionado !== undefined">
                    <div class="detalheVeiculo">{{veiculoSelecionado.veiculo}}
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Marca</label>
                                <div class="anoVeiculo">{{veiculoSelecionado.marca | uppercase}}</div>
                            </div>
                            <div class="col-md-6">
                                <label>Ano</label>
                                <div class="anoVeiculo">{{veiculoSelecionado.ano}}</div>
                            </div>
                            <br/>
                            <p class="descricaoVeiculo">{{veiculoSelecionado.descricao}}</p>
                        </div>
                        <br/>
                        <div class="borderLineBottom"></div>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-10">
                                    <button type="button" class="btn btn-outline-primary acaoBotaoDetalhe" ng-click="exibirModalEditar()">Editar
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger acaoBotaoDetalhe" ng-click="exibirModalExcluir()">Excluir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------------------------Modal de cadastro de Veículo----------------------------------------->
<div class="modal fade" id="modalCadastroVeiculo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="min-width:45%">
        <form name="formCadastrarNovoVeiculo" method="post" autocomplete="off" novalidate class="form-horizontal"
              ng-submit="formCadastrarNovoVeiculo.$valid && salvarVeiculo(novoVeiculo)">
            <div class="modal-content modalCadastro">
                <div>
                    <div class="col-md-12" ng-if="statusModal == 1">
                        <h4 class="modal-title" id="myModalLabel">
                            <b>Novo Veículo</b>
                        </h4>
                    </div>
                    <div class="col-md-12" ng-if="statusModal == 2">
                        <h4 class="modal-title" id="myModalLabel">
                            Editar Veículo
                        </h4>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="veiculoCadastro" class="control-label">Veículo</label>
                                <input type="text" class="form-control cadastro" id="veiculoCadastro" name="veiculoCadastro"
                                       ng-model="novoVeiculo.veiculo" placeholder="Veículo" ng-minlength="2" ng-maxlength="60" required/>
                                <div class="msgerro cadastro" ng-messages="formCadastrarNovoVeiculo.veiculoCadastro.$error"
                                     ng-show="formCadastrarNovoVeiculo.$submitted">
                                    <div ng-message="required" class="invalido">* Preencha o nome do Veiculo</div>
                                    <div ng-message="minlength" class="invalido">* O veículo precisa conter ao menos 2
                                                                                 caracteres
                                    </div>
                                    <div ng-message="maxlength" class="invalido">* Limite de caracteres excedido</div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="marcaCadastro" class="control-label">Marca</label>
                                <input type="text" class="form-control cadastro" id="marcaCadastro" name="marcaCadastro"
                                       ng-model="novoVeiculo.marca" placeholder="Marca" ng-minlength="2" ng-maxlength="60" required/>
                                <div class="msgerro cadastro" ng-messages="formCadastrarNovoVeiculo.marcaCadastro.$error"
                                     ng-show="formCadastrarNovoVeiculo.$submitted">
                                    <div ng-message="required" class="invalido">* Preencha a marca do Veiculo</div>
                                    <div ng-message="minlength" class="invalido">* A marca precisa conter ao menos 2
                                                                                 caracteres
                                    </div>
                                    <div ng-message="maxlength" class="invalido">* Limite de caracteres excedido</div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="anoCadastro" class="control-label">Ano</label>
                                <input type="text" ng-pattern="/^([0-9])+$/" class="form-control cadastro" id="anoCadastro" name="anoCadastro"
                                       ng-model="novoVeiculo.ano" placeholder="Ano" ng-minlength="4" ng-maxlength="4"
                                       required/>
                                <div class="msgerro cadastro" ng-messages="formCadastrarNovoVeiculo.anoCadastro.$error"
                                     ng-show="formCadastrarNovoVeiculo.$submitted">
                                    <div ng-message="required" class="invalido">* Preencha o Ano do Veiculo</div>
                                    <div ng-message="minlength" class="invalido">* O Ano precisa conter 4 caracteres</div>
                                    <div ng-message="pattern" class="invalido">* O Ano só deve conter números</div>
                                    <div ng-message="maxlength" class="invalido">* Limite de caracteres excedido</div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="checkbox-inline" style="margin-top: 35px;cursor: pointer">
                                    <input style="cursor: pointer" type="checkbox" id="veiculoStatus" ng-checked="novoVeiculo.vendido" ng-model="novoVeiculo.vendido">&nbsp;Vendido
                                </label>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="descricaoCadastro" class="control-label">Descrição</label>
                                <textarea rows="7" class="form-control cadastro" id="descricaoCadastro" name="descricaoCadastro"
                                          ng-model="novoVeiculo.descricao" ng-maxlength="500" required>
                                </textarea>
                                <div class="msgerro cadastro" ng-messages="formCadastrarNovoVeiculo.descricaoCadastro.$error"
                                     ng-show="formCadastrarNovoVeiculo.$submitted">
                                    <div ng-message="required" class="invalido">* Preencha a descrição do Veiculo</div>
                                    <div ng-message="maxlength" class="invalido">* Limite de caracteres excedido</div>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-10">
                                        <button ng-if="statusModal === 1" type="submit" class="btn btn-success">Adicionar</button>
                                        <button ng-if="statusModal === 2" type="submit" class="btn btn-success">Atualizar</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button data-dismiss="modal" class="btn btn-danger">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!------------------------------------Fim Modal de cadastro de Veículo----------------------------------------->
<!------------------------------------Modal de exclusão de Veículo----------------------------------------->
<div class="modal fade" id="modalExcluirVeiculo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 18%">
        <form name="formExcluirVeiculo" method="post" autocomplete="off" novalidate class="form-horizontal"
              ng-submit="formExcluirVeiculo.$valid && excluirVeiculo(veiculoSelecionado.idVeiculo)">
            <div class="modal-content modalExcluir">
                <div>
                    <div class="col-md-12">
                        <h4 class="modal-title" id="myModalLabel">
                            <b class="excluirModal">Confirma a Exclusão?</b>
                        </h4>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="anoVeiculo">{{veiculoSelecionado.veiculo}} {{veiculoSelecionado.ano}}</div>
                        </div>
                    </div>
                    <br/>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-9">
                                <button type="submit" class="btn btn-outline-success">Sim</button>
                            </div>
                            <div class="col-md-1">
                                <button data-dismiss="modal" class="btn btn-outline-danger">Não</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!------------------------------------Fim Modal de exclusão de Veículo----------------------------------------->