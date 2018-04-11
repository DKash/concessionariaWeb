'use strict';

angular.module('concessionariaweb.painel', ['ngRoute'])

    .config(['$routeProvider', function ($routeProvider) {
        $routeProvider.when('/painel', {
            templateUrl: 'templates/painel.php',
            controller: 'painelCtrl'
        });
    }])

    .controller('painelCtrl', ['$scope', '$rootScope', '$http', 'notyService',
        function ($scope, $rootScope, $http, notyService) {
            $scope.veiculos = [];
            $scope.veiculoSelecionado = undefined;
            $scope.statusModal = 1;
            $scope.originalVeiculos = [];

            $scope.$watch("veiculos.length", function (data) {
                if (data === 0) {
                    $scope.carregarVeiculos();
                }
            });

            $scope.exibirModalCadastro = function () {
                $scope.statusModal = 1;
                $('#modalCadastroVeiculo').modal("show");
            };

            $scope.exibirModalEditar = function () {
                $scope.statusModal = 2;
                $scope.novoVeiculo = angular.copy($scope.veiculoSelecionado);
                $scope.novoVeiculo.vendido = $scope.novoVeiculo.vendido === 1 ? true : false;
                $('#modalCadastroVeiculo').modal("show");
            };

            $scope.exibirModalExcluir = function () {
                $('#modalExcluirVeiculo').modal("show");
            };

            $scope.selecionarVeiculo = function (idVeiculo) {
                angular.forEach($scope.veiculos, function (value) {
                    if (value.idVeiculo === idVeiculo) {
                        $scope.veiculoSelecionado = value;
                    }
                });
            };

            $scope.salvarVeiculo = function (veiculo) {

                veiculo.vendido = $('input#veiculoStatus').prop("checked") === true ? 1 : 0;
                var dados;

                if ($scope.statusModal === 1) {
                    dados = [{"modulo": "salvar", "funcao": "cadastrarVeiculo"},
                        {"dados": JSON.stringify(veiculo)}];

                    $http.post("/templates/ws.php", JSON.stringify(dados)).then(function (result) {
                        var resultado = result.data;
                        var className = "";

                        if (resultado.error) {
                            className = "warning";
                        } else {
                            className = "success";
                            limparCampos();
                            $('#modalCadastroVeiculo').modal("hide");
                            $scope.carregarVeiculos();
                        }
                        notyService.exibir(className, resultado.mensagem);
                    });
                } else if ($scope.statusModal === 2) {
                    dados = [{"modulo": "editar", "funcao": "atualizarVeiculo"},
                        {"dados": JSON.stringify(veiculo)}];

                    $http.post("/templates/ws.php", JSON.stringify(dados)).then(function (result) {
                        var resultado = result.data;
                        var className = "";

                        if (resultado.error) {
                            className = "warning";
                        } else {
                            className = "success";
                            $('#modalCadastroVeiculo').modal("hide");
                            $scope.carregarVeiculos();
                            $scope.$applyAsync(function () {
                                $scope.veiculoSelecionado = veiculo;
                            });
                        }
                        notyService.exibir(className, resultado.mensagem);
                    });
                }
            };

            $scope.pesquisarVeiculosPor = function (parametro) {
                if (parametro !== undefined && parametro !== "") {
                    var dados = [{"modulo": "load", "funcao": "pesquisarVeiculoPorParametro"},
                        {"dados": JSON.stringify(parametro)}];

                    $http.post("/templates/ws.php", JSON.stringify(dados)).then(function (result) {
                        var resultado = result.data;

                        if (resultado.error) {
                            notyService.exibir("warning", resultado.mensagem);
                        } else {
                            $scope.originalVeiculos = angular.copy($scope.veiculos);
                            $scope.veiculos = resultado.veiculos.length > 0 ? resultado.veiculos : null;
                            $scope.veiculoSelecionado = undefined;
                        }
                    });
                } else {
                    notyService.exibir("warning", "O campo está vazio, não é possível fazer a pesquisa");
                }
            };

            $scope.$watch('searchVeiculo', function (data) {
                if ((data === undefined || data === "") && $scope.originalVeiculos.length > 0) {
                    $scope.veiculos = angular.copy($scope.originalVeiculos);
                }
            });

            $scope.excluirVeiculo = function (idVeiculo) {

                $('#modalExcluirVeiculo').modal("hide");

                var dados = [{"modulo": "excluir", "funcao": "excluirVeiculo"},
                    {"dados": JSON.stringify(idVeiculo)}];

                $http.post("/templates/ws.php", JSON.stringify(dados)).then(function (result) {
                    var resultado = result.data;
                    var className = "";

                    if (resultado.error) {
                        className = "warning";
                    } else {
                        className = "success";
                        limparCampos();
                        $scope.carregarVeiculos();
                    }
                    notyService.exibir(className, resultado.mensagem);
                });
            };

            var limparCampos = function () {
                $scope.novoVeiculo = undefined;
                $scope.veiculoSelecionado = undefined;
                $('input.form-control.cadastro').val('');
                $('textarea.form-control.cadastro').val('');
                $scope.formCadastrarNovoVeiculo.$setPristine();
                $scope.formCadastrarNovoVeiculo.$setUntouched();
            };

            $('div#modalCadastroVeiculo').on('hide.bs.modal', function () {
                if ($scope.statusModal === 1) {
                    limparCampos();
                }
            });

            $('div#modalCadastroVeiculo').on('show.bs.modal', function () {
                if ($scope.statusModal === 1) {
                    limparCampos();
                }
            });

            $scope.carregarVeiculos = function () {
                var dados = [{"modulo": "load", "funcao": "carregarVeiculos"}];

                $http.post("/templates/ws.php", JSON.stringify(dados)).then(function (result) {
                    var resultado = result.data;
                    if (resultado.error) {
                        notyService.exibir("warning", resultado.mensagem);
                    } else {
                        $scope.veiculos = resultado.veiculos;
                    }
                });
            };
        }]);