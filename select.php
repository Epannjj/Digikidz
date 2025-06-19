// Ambil data program dari database
<?php
$program_query = "SELECT DISTINCT PROGRAM, category FROM program ORDER BY category ASC, PROGRAM ASC";
$program_result = mysqli_query($db, $program_query);
$program_data = [];

// Tambahkan opsi "Semua Kategori"
$program_data[] = ['value' => '', 'text' => '-- Semua Program --', 'category' => null];

while ($row = mysqli_fetch_array($program_result)) {
    $program_data[] = [
        'value' => $row['PROGRAM'],
        'text' => $row['PROGRAM'],
        'category' => $row['category']
    ];
}
?>

const programData = <?php echo json_encode($program_data); ?>;

const selectInput = document.getElementById('searchableSelect');
const hiddenInput = document.getElementById('hiddenInput');
const selectedText = document.getElementById('selectedText');
const dropdown = document.getElementById('selectDropdown');

let isOpen = false;
let highlightedIndex = -1;
let filteredData = [];

// Event listeners
selectInput.addEventListener('click', toggleDropdown);
selectInput.addEventListener('input', handleSearch);
selectInput.addEventListener('keydown', handleKeyDown);
document.addEventListener('click', handleClickOutside);

function toggleDropdown() {
if (isOpen) {
closeDropdown();
} else {
openDropdown();
}
}

function openDropdown() {
selectInput.removeAttribute('readonly');

if (selectInput.value === '-- Semua Program --') {
selectInput.value = '';
}

lastSelectedText = selectInput.value;
selectInput.focus();
handleSearch();
isOpen = true;
}

function closeDropdown() {
dropdown.style.display = 'none';
selectInput.setAttribute('readonly', true);

// Jika tidak memilih apapun dan input kosong, kembalikan ke teks terakhir
if (!hiddenInput.value && selectInput.value.trim() === '') {
selectInput.value = lastSelectedText || '-- Semua Program --';
}

isOpen = false;
highlightedIndex = -1;
}
function handleSearch() {
const query = selectInput.value.toLowerCase();

if (query === '') {
filteredData = programData;
} else {
filteredData = programData.filter(item =>
item.text.toLowerCase().includes(query) ||
(item.category && item.category.toLowerCase().includes(query))
);
}

displayOptions(filteredData);
}

function displayOptions(data) {
dropdown.innerHTML = '';
highlightedIndex = -1;

if (data.length === 0) {
dropdown.innerHTML = '<div class="no-results">Tidak ada hasil yang ditemukan</div>';
dropdown.style.display = 'block';
return;
}

// Group by category
const grouped = {};
data.forEach(item => {
if (item.category === null) {
// Special case for "Semua Kategori"
const option = createOption(item, 0);
dropdown.appendChild(option);
} else {
if (!grouped[item.category]) {
grouped[item.category] = [];
}
grouped[item.category].push(item);
}
});

// Add grouped options
let optionIndex = 1;
Object.keys(grouped).forEach(category => {
// Category header
const categoryHeader = document.createElement('div');
categoryHeader.className = 'category-group';
categoryHeader.textContent = category;
dropdown.appendChild(categoryHeader);

// Options in category
grouped[category].forEach(item => {
const option = createOption(item, optionIndex);
option.classList.add('program-option');
dropdown.appendChild(option);
optionIndex++;
});
});

dropdown.style.display = 'block';
}

function createOption(item, index) {
const option = document.createElement('div');
option.className = 'select-option';
option.textContent = item.text;
option.dataset.value = item.value;
option.dataset.text = item.text;
option.dataset.index = index;

option.addEventListener('click', () => selectOption(item));
return option;
}

function selectOption(item) {
selectInput.value = item.text;
hiddenInput.value = item.value;
selectedText.value = item.text;
closeDropdown();
}

function handleKeyDown(e) {
const options = dropdown.querySelectorAll('.select-option');

switch(e.key) {
case 'ArrowDown':
e.preventDefault();
highlightedIndex = Math.min(highlightedIndex + 1, options.length - 1);
updateHighlight(options);
break;
case 'ArrowUp':
e.preventDefault();
highlightedIndex = Math.max(highlightedIndex - 1, -1);
updateHighlight(options);
break;
case 'Enter':
e.preventDefault();
if (highlightedIndex >= 0 && options[highlightedIndex]) {
const value = options[highlightedIndex].dataset.value;
const text = options[highlightedIndex].dataset.text;
selectOption({ value, text });
}
break;
case 'Escape':
closeDropdown();
break;
}
}

function updateHighlight(options) {
options.forEach((option, index) => {
option.classList.toggle('highlighted', index === highlightedIndex);
});
}

function handleClickOutside(e) {
if (!selectInput.contains(e.target) && !dropdown.contains(e.target)) {
closeDropdown();
}
}
const programDataSiswa = <?php echo json_encode($program_data); ?>;

const selectInputSiswa = document.getElementById('searchableSelectSiswa');
const hiddenInputSiswa = document.getElementById('hiddenInputSiswa');
const selectedTextSiswa = document.getElementById('selectedTextSiswa');
const dropdownSiswa = document.getElementById('selectDropdownSiswa');

let isOpenSiswa = false;
let highlightedIndexSiswa = -1;
let filteredDataSiswa = [];

selectInputSiswa.addEventListener('click', toggleDropdownSiswa);
selectInputSiswa.addEventListener('input', handleSearchSiswa);
selectInputSiswa.addEventListener('keydown', handleKeyDownSiswa);
document.addEventListener('click', handleClickOutsideSiswa);

function toggleDropdownSiswa() {
if (isOpenSiswa) {
closeDropdownSiswa();
} else {
openDropdownSiswa();
}
}

function openDropdownSiswa() {
selectInputSiswa.removeAttribute('readonly');
if (selectInputSiswa.value === '-- Semua Program --') {
selectInputSiswa.value = '';
}
lastSelectedTextSiswa = selectInputSiswa.value;
selectInputSiswa.focus();
handleSearchSiswa();
isOpenSiswa = true;
}

function closeDropdownSiswa() {
dropdownSiswa.style.display = 'none';
selectInputSiswa.setAttribute('readonly', true);
if (!hiddenInputSiswa.value && selectInputSiswa.value.trim() === '') {
selectInputSiswa.value = lastSelectedTextSiswa || '-- Semua Program --';
}
isOpenSiswa = false;
highlightedIndexSiswa = -1;
}

function handleSearchSiswa() {
const query = selectInputSiswa.value.toLowerCase();
if (query === '') {
filteredDataSiswa = programDataSiswa;
} else {
filteredDataSiswa = programDataSiswa.filter(item =>
item.text.toLowerCase().includes(query) ||
(item.category && item.category.toLowerCase().includes(query))
);
}
displayOptionsSiswa(filteredDataSiswa);
}

function displayOptionsSiswa(data) {
dropdownSiswa.innerHTML = '';
highlightedIndexSiswa = -1;

if (data.length === 0) {
dropdownSiswa.innerHTML = '<div class="no-results">Tidak ada hasil yang ditemukan</div>';
dropdownSiswa.style.display = 'block';
return;
}

const grouped = {};
data.forEach(item => {
if (item.category === null) {
const option = createOptionSiswa(item, 0);
dropdownSiswa.appendChild(option);
} else {
if (!grouped[item.category]) {
grouped[item.category] = [];
}
grouped[item.category].push(item);
}
});

let optionIndex = 1;
Object.keys(grouped).forEach(category => {
const categoryHeader = document.createElement('div');
categoryHeader.className = 'category-group';
categoryHeader.textContent = category;
dropdownSiswa.appendChild(categoryHeader);

grouped[category].forEach(item => {
const option = createOptionSiswa(item, optionIndex);
option.classList.add('program-option');
dropdownSiswa.appendChild(option);
optionIndex++;
});
});

dropdownSiswa.style.display = 'block';
}

function createOptionSiswa(item, index) {
const option = document.createElement('div');
option.className = 'select-option';
option.textContent = item.text;
option.dataset.value = item.value;
option.dataset.text = item.text;
option.dataset.index = index;

option.addEventListener('click', () => selectOptionSiswa(item));
return option;
}

function selectOptionSiswa(item) {
selectInputSiswa.value = item.text;
hiddenInputSiswa.value = item.value;
selectedTextSiswa.value = item.text;
closeDropdownSiswa();
}

function handleKeyDownSiswa(e) {
const options = dropdownSiswa.querySelectorAll('.select-option');

switch (e.key) {
case 'ArrowDown':
e.preventDefault();
highlightedIndexSiswa = Math.min(highlightedIndexSiswa + 1, options.length - 1);
updateHighlightSiswa(options);
break;
case 'ArrowUp':
e.preventDefault();
highlightedIndexSiswa = Math.max(highlightedIndexSiswa - 1, -1);
updateHighlightSiswa(options);
break;
case 'Enter':
e.preventDefault();
if (highlightedIndexSiswa >= 0 && options[highlightedIndexSiswa]) {
const value = options[highlightedIndexSiswa].dataset.value;
const text = options[highlightedIndexSiswa].dataset.text;
selectOptionSiswa({ value, text });
}
break;
case 'Escape':
closeDropdownSiswa();
break;
}
}

function updateHighlightSiswa(options) {
options.forEach((option, index) => {
option.classList.toggle('highlighted', index === highlightedIndexSiswa);
});
}

function handleClickOutsideSiswa(e) {
if (!selectInputSiswa.contains(e.target) && !dropdownSiswa.contains(e.target)) {
closeDropdownSiswa();
}
}