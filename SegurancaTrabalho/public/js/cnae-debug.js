// Script para depurar o problema do CNAE e grau de risco
console.log('Script de depuração CNAE carregado');

document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM carregado, verificando elementos...');
  
  // Verificar se o jQuery está disponível
  if (typeof jQuery === 'undefined') {
    console.error('jQuery não está disponível!');
    return;
  }
  
  console.log('jQuery está disponível:', jQuery.fn.jquery);
  
  // Verificar se os elementos existem
  const cnaeSelect = document.getElementById('cnae_id');
  const grauRiscoInput = document.getElementById('grau_risco');
  
  console.log('Elemento select CNAE:', cnaeSelect);
  console.log('Elemento input grau_risco:', grauRiscoInput);
  
  if (!cnaeSelect || !grauRiscoInput) {
    console.error('Elementos não encontrados!');
    return;
  }
  
  // Adicionar listener manual para verificar se o evento está sendo capturado
  cnaeSelect.addEventListener('change', function() {
    console.log('Evento change capturado via JavaScript nativo');
    const selectedOption = this.options[this.selectedIndex];
    const grau = selectedOption.getAttribute('data-grau');
    console.log('Grau de risco selecionado:', grau);
    
    if (grau) {
      grauRiscoInput.value = grau;
      console.log('Valor do input atualizado para:', grau);
    }
  });
  
  // Verificar se o evento jQuery está funcionando
  jQuery('#cnae_id').on('change', function() {
    console.log('Evento change capturado via jQuery');
    const grau = jQuery(this).find('option:selected').data('grau');
    console.log('Grau de risco via jQuery:', grau);
    
    if (grau) {
      jQuery('#grau_risco').val(grau);
      console.log('Valor do input atualizado via jQuery para:', grau);
    }
  });
});