let stickers = document.querySelectorAll( '.sticker, .wp-block-image img' );

console.log( stickers.length );

Array.prototype.forEach.call( stickers, function( sticker ) {

  let rotation = Math.random() * ( 2 - -2) + -2;
  sticker.style.transform = 'rotate( ' + rotation + 'deg )';

});
