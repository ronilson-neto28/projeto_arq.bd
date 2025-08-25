import './bootstrap';

// importar o inputmask
import Inputmask from 'inputmask';

//acrescentar mascara no campo com inputmask
//domcontetloaded - disparar o evento quando o html foi completamente carregado
document.addEventListener("DOMContentLoaded", function() {

    var cpfMask = new Inputmask("999.999.999-99");
    cpfMask.mask(document.querySelectorAll('#cpf'));

    var cepMask = new Inputmask("99999-999");
    cepMask.mask(document.querySelectorAll('#cep'));

    var telefoneMask = new Inputmask("(99) 99999-9999");
    telefoneMask.mask(document.querySelectorAll('#telefone'));

    var cnpjMask = new Inputmask("99.999.999/9999-99");
    cnpjMask.mask(document.querySelectorAll('#cnpj'));

    var dataMask = new Inputmask("99/99/9999");
    dataMask.mask(document.querySelectorAll('#data_nascimento'))

    var dataMask = new Inputmask("99/99/9999");
    dataMask.mask(document.querySelectorAll('#data_admissao'))

 
})