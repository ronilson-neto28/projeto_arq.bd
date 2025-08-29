/**
 * Script para garantir que o grau de risco seja atualizado quando um CNAE é selecionado
 */
document.addEventListener('DOMContentLoaded', function() {
  // Função para atualizar o grau de risco
  function atualizarGrauRisco() {
    const cnaeSelect = document.getElementById('cnae_id');
    const grauRiscoInput = document.getElementById('grau_risco');
    
    if (!cnaeSelect || !grauRiscoInput) return;
    
    const selectedOption = cnaeSelect.options[cnaeSelect.selectedIndex];
    if (selectedOption && selectedOption.hasAttribute('data-grau')) {
      const grau = selectedOption.getAttribute('data-grau');
      if (grau) {
        grauRiscoInput.value = grau;
      }
    }
  }
  
  // Adicionar evento para o select nativo
  const cnaeSelect = document.getElementById('cnae_id');
  if (cnaeSelect) {
    cnaeSelect.addEventListener('change', atualizarGrauRisco);
  }
  
  // Verificar periodicamente se o Select2 foi inicializado
  const checkSelect2 = setInterval(function() {
    if (window.jQuery && jQuery.fn.select2) {
      clearInterval(checkSelect2);
      
      // Adicionar evento para o Select2
      jQuery('#cnae_id').on('select2:select', atualizarGrauRisco);
      jQuery('#cnae_id').on('select2:clear', function() {
        document.getElementById('grau_risco').value = '';
      });
      
      // Executar uma vez para garantir que o valor inicial seja definido
      atualizarGrauRisco();
    }
  }, 100);
  
  // Executar uma vez para garantir que o valor inicial seja definido
  atualizarGrauRisco();
});