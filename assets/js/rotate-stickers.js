let stickers = document.querySelectorAll('.sticker, .is-style-sticker');

// Randomly rotates each sticker.
if (stickers) {
  stickers.forEach(function(sticker) {
    let rotation = Math.random() * (2 - -2) + -2;
    sticker.style.transform = 'rotate(' + rotation + 'deg)';
  });
}