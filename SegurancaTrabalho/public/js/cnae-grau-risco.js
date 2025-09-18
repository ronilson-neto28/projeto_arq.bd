/**
 * Script para garantir que o grau de risco seja atualizado quando um CNAE é selecionado
 */
(function() {
  'use strict';
  
  // Função para atualizar o grau de risco
  function atualizarGrauRisco() {
    const cnaeSelect = document.getElementById('cnae_id');
    const grauRiscoInput = document.getElementById('grau_risco');
    
    console.log('Atualizando grau de risco...');
    
    if (!cnaeSelect || !grauRiscoInput) {
      console.log('Elementos não encontrados:', { cnaeSelect, grauRiscoInput });
      return;
    }
    
    const selectedOption = cnaeSelect.options[cnaeSelect.selectedIndex];
    if (selectedOption && selectedOption.value && selectedOption.hasAttribute('data-grau')) {
      const grau = selectedOption.getAttribute('data-grau');
      console.log('Grau de risco encontrado:', grau);
      if (grau) {
        grauRiscoInput.value = grau;
        console.log('Grau de risco atualizado para:', grau);
      }
    } else {
      console.log('Nenhuma opção válida selecionada ou sem data-grau');
    }
  }
  
  // Função para configurar eventos do Select2
  function configurarSelect2() {
    if (window.jQuery && jQuery.fn.select2) {
      console.log('Configurando eventos do Select2...');
      
      // Remover eventos anteriores para evitar duplicação
      jQuery('#cnae_id').off('select2:select.grauRisco select2:clear.grauRisco');
      
      // Adicionar eventos com namespace para facilitar remoção
      jQuery('#cnae_id').on('select2:select.grauRisco', function(e) {
        console.log('Select2 select event triggered', e.params.data);
        setTimeout(atualizarGrauRisco, 10); // Pequeno delay para garantir que o DOM foi atualizado
      });
      
      jQuery('#cnae_id').on('select2:clear.grauRisco', function() {
        console.log('Select2 clear event triggered');
        const grauRiscoInput = document.getElementById('grau_risco');
        if (grauRiscoInput) {
          grauRiscoInput.value = '';
        }
      });
      
      return true;
    }
    return false;
  }
  
  // Inicialização quando o DOM estiver pronto
  function inicializar() {
    console.log('Inicializando script CNAE-Grau de Risco...');
    
    // Adicionar evento para o select nativo
    const cnaeSelect = document.getElementById('cnae_id');
    if (cnaeSelect) {
      cnaeSelect.addEventListener('change', atualizarGrauRisco);
      console.log('Evento change adicionado ao select nativo');
    }
    
    // Tentar configurar Select2 imediatamente
    if (!configurarSelect2()) {
      // Se não conseguiu, aguardar a inicialização do Select2
      let tentativas = 0;
      const maxTentativas = 50; // 5 segundos
      
      const checkSelect2 = setInterval(function() {
        tentativas++;
        
        if (configurarSelect2()) {
          clearInterval(checkSelect2);
          console.log('Select2 configurado após', tentativas, 'tentativas');
          // Executar uma vez para garantir que o valor inicial seja definido
          setTimeout(atualizarGrauRisco, 100);
        } else if (tentativas >= maxTentativas) {
          clearInterval(checkSelect2);
          console.log('Timeout: Select2 não foi inicializado após', maxTentativas, 'tentativas');
        }
      }, 100);
    } else {
      // Se conseguiu configurar imediatamente, executar uma vez
      setTimeout(atualizarGrauRisco, 100);
    }
    
    // Executar uma vez para garantir que o valor inicial seja definido
    atualizarGrauRisco();
  }
  
  // Aguardar o DOM estar pronto
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializar);
  } else {
    inicializar();
  }
})();