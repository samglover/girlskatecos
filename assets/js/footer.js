let stickers = document.querySelectorAll('.sticker, .wp-block-image img');

// Randomly rotates each sticker.
if (stickers) {
  stickers.forEach(function(sticker) {
    let rotation = Math.random() * (2 - -2) + -2;
    sticker.style.transform = 'rotate(' + rotation + 'deg)';
  });
}

// Handles the Sort A to Z and Randomize buttons.
let sortButton    = document.getElementById('sort-stickers');
let randomButton  = document.getElementById('randomize-stickers');

if (sortButton && stickers) {
  let stickersId    = document.getElementById('stickers');
  let stickersArray = Array.from(stickersId.children);

  sortButton.addEventListener('click', function() {
    stickersArray.sort(function(a, b) {
      let titleA = a.getAttribute('title').toLowerCase();
      let titleB = b.getAttribute('title').toLowerCase();
      return titleA == titleB ? 0 : (titleA > titleB ? 1 : -1);
    });

    for (let i = 0; i < stickersArray.length; i++) {
      stickersId.appendChild(stickersArray[i]);
    }

    sortButton.setAttribute('disabled', '');
  });
}

if (randomButton && stickers) {
  let stickersId    = document.getElementById('stickers');
  let stickersArray = Array.from(stickersId.children);

  randomButton.addEventListener('click', function() {
    for (let i = stickersArray.length - 1; i > 0; i--) {
      let j = Math.floor(Math.random() * (i + 1));
      let temp = stickersArray[i];
      stickersArray[i] = stickersArray[j];
      stickersArray[j] = temp;
    }

    for (let i = 0; i < stickersArray.length; i++) {
      stickersId.appendChild(stickersArray[i]);
    }

    sortButton.removeAttribute('disabled');
  });
}

// Handles the search field.
let searchField = document.getElementById('search-cos');
let closeButton = document.getElementById('clear-search-cos');
let noStickersMessage = document.getElementById('no-stickers');

if (searchField && closeButton) {
  searchField.addEventListener('input', function(e) {
    let entry = e.target.value.toLowerCase();
    
    if (entry) {
      stickers.forEach(function(sticker) {
        let title = sticker.getAttribute('title').toLowerCase();
        if (title.indexOf(entry) == -1) {
          sticker.classList.add('hidden');
        } else {
          sticker.classList.remove('hidden');
        }
      });
      closeButton.classList.remove('hidden');
      let stickersShown = 0;
      stickers.forEach(function(sticker) {
        if (!sticker.classList.contains('hidden')) stickersShown++;
      });
      if (stickersShown == 0) noStickersMessage.classList.remove('hidden');
    } else {
      showAllStickers();
      closeButton.classList.add('hidden');
      noStickersMessage.classList.add('hidden');
    }
  });

  closeButton.addEventListener('click', function() {
    searchField.value = '';
    showAllStickers();
    closeButton.classList.add('hidden');
  });
}

function showAllStickers() {
  stickers.forEach(function(sticker) {
    sticker.classList.remove('hidden');
    noStickersMessage.classList.add('hidden');
  });
}
