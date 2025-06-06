
document.addEventListener('DOMContentLoaded', function() {
  // Confirmation pour la suppression
  document.querySelectorAll('button[name="action"][value="supprimer"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Confirmer la suppression ? Cette action est irréversible.')) {
        e.preventDefault();
      }
    });
  });

  // Confirmation pour la modification
  document.querySelectorAll('button[name="action"][value="modifier"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (!confirm('Confirmer la modification ?')) {
        e.preventDefault();
      }
    });
  });

  // Auto-resize des textareas
  document.querySelectorAll('.auto-resize').forEach(function(textarea) {
    function resize() {
      textarea.style.height = 'auto';
      textarea.style.height = textarea.scrollHeight + 'px';
    }
    textarea.addEventListener('input', resize);
    resize(); // Redimensionne au chargement
  });
});