angular.module('concessionariaweb').factory("notyService", [function () {
    return {
        exibir: function (className, mensagem, timeout) {
            timeout = timeout || 3000;
            new Noty({
                         type: className,
                         text: mensagem,
                         timeout: timeout
                     }).show();
        }
    };
}]);