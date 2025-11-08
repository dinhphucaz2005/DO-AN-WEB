// public/js/app.js - minimal jQuery interactions for FastFood Delivery
$(document).ready(function() {
  // simple cart counter
  var cartCount = 0;
  $('.add-to-cart').on('click', function(e) {
    e.preventDefault();
    cartCount++;
    $('#cart-count').text(cartCount);
    // visual feedback
    $(this).text('Đã thêm').prop('disabled', true).css('opacity', 0.8);
  });
});
