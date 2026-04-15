const stickersId    = document.getElementById('stickers');
const sortButton    = document.getElementById('sort-stickers');
const randomButton  = document.getElementById('randomize-stickers');
const searchField   = document.getElementById('search-cos');
const clearButton   = document.getElementById('clear-search-cos');
const cosCount      = document.getElementById('cos-count');
const cosCountLabel = document.getElementById('cos-count-label');

if (stickersId) {
  sortButton.addEventListener('click', sortAtoZ);
  randomButton.addEventListener('click', randomize);
  searchField.addEventListener('input', search);
  clearButton.addEventListener('click', clearSearch);

  countCos();
}

function sortAtoZ() {
  let stickersArray = Array.from(stickersId.children);

  stickersArray.sort(function(a, b) {
    let titleA = a.dataset.brandName.toLowerCase();
    let titleB = b.dataset.brandName.toLowerCase();
    return titleA == titleB ? 0 : (titleA > titleB ? 1 : -1);
  });

  for (let i = 0; i < stickersArray.length; i++) {
    stickersId.appendChild(stickersArray[i]);
  }

  sortButton.setAttribute('disabled', '');
}

function randomize() {
  let stickersArray = Array.from(stickersId.children);

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
}

function search(e) {
  let entry = e.target.value.toLowerCase();
  
  if (entry) {
    let stickers = document.querySelectorAll('#stickers .sticker');
    
    clearButton.classList.remove('hidden');

    stickers.forEach(function(sticker) {
      let title = sticker.dataset.brandName.toLowerCase();

      if (title.indexOf(entry) == -1) {
        sticker.classList.add('hidden');
      } else {
        sticker.classList.remove('hidden');
      }
    });

    countCos();
  } else {
    clearSearch();
  }
}

function countCos() {
  let stickersShown = document.querySelectorAll('#stickers .sticker:not(.hidden)').length;

  if (stickersShown == 0) {
    cosCount.textContent = 'No';
    cosCountLabel.textContent = 'cos.';
  } else if (stickersShown == 1) {
    cosCount.textContent = stickersShown;
    cosCountLabel.textContent = 'co.';
  } else {
    cosCount.textContent = stickersShown;
    cosCountLabel.textContent = 'cos.';
  }
}

function clearSearch() {
  searchField.value = '';
  clearButton.classList.add('hidden');

  stickersId.classList.remove('hidden');
  
  let stickers = document.querySelectorAll('#stickers .sticker');
  stickers.forEach(function(sticker) {
    sticker.classList.remove('hidden');
  });

  countCos();
}
