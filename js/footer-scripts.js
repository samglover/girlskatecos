( function( $ ) {

  let stickers = document.getElementsByClassName( 'sticker' );

  Array.prototype.forEach.call( stickers, function( sticker ) {

    let rotation = Math.random() * ( 2 - -2) + -2;
    sticker.style.transform = 'rotate( ' + rotation + 'deg )';

  });

})( jQuery );
