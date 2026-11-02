let stickers = document.querySelectorAll('.sticker, .page-content img, .embed-container');

// Randomly rotates each sticker. Also used for images and video embeds.
if (stickers) {
  stickers.forEach(function(sticker) {
    let rotation = Math.random() * (2 - -2) + -2;
    sticker.style.transform = 'rotate(' + rotation + 'deg)';
  });
}