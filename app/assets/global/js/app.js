'use strict';

angular.module('concessionariaweb', [
    'ngRoute',
    'ngMessages',
    'ui.utils.masks',
    'ngMaterial',
    'concessionariaweb.painel'
]).config(['$locationProvider', '$routeProvider', function ($locationProvider, $routeProvider) {
    $locationProvider.hashPrefix('');

    $routeProvider.otherwise({redirectTo: '/painel'});
}]);
