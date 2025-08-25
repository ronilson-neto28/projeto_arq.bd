// npm package: select2
// github link: https://github.com/select2/select2

'use strict';

// Importar jQuery e atribuir ao escopo global
import $ from 'jquery';
window.$ = $;
window.jQuery = $;

// Importar o plugin select2
import 'select2';

(function () {

  if ($(".js-example-basic-single").length) {
    $(".js-example-basic-single").select2();
  }
  if ($(".js-example-basic-multiple").length) {
    $(".js-example-basic-multiple").select2();
  }

})();