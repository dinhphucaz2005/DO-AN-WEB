// public/js/app.js - Meme Creator functionality
$(document).ready(function() {
  // Add any global meme creator functionality here
  console.log('🎨 Meme Creator loaded!');
  
  // Add keyboard shortcuts for meme editor
  $(document).keydown(function(e) {
    // Ctrl+S to download meme (if on meme editor page)
    if (e.ctrlKey && e.which === 83 && $('#downloadMeme').length) {
      e.preventDefault();
      $('#downloadMeme').click();
    }
  });
});
