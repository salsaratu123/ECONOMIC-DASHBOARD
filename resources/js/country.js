import { formatNumber, setText } from './utils';

export function updateCountrySelect(countries, selectedIso) {
    const select = document.getElementById('countrySelect');
    if (!select || !countries || !countries.length) return;

    // Simpan posisi pilihan saat ini agar tidak ter-reset saat re-render
    const currentSelection = selectedIso || select.value;
    
    // Bersihkan isi opsi bawaan hardcoded lama
    select.innerHTML = '';
    
    // Urutkan nama negara secara alfabetis (A-Z) agar rapi dilihat Dosen Penguji
    const sortedCountries = [...countries].sort((a, b) => (a.name || '').localeCompare(b.name || ''));

    // Masukkan SEMUA negara hasil fetch API database ke dalam select option
    sortedCountries.forEach((country) => {
        if (country && country.iso_code) {
            const option = new Option(country.name, country.iso_code);
            select.add(option);
        } else if (country && country.cca3) {
            const option = new Option(country.name, country.cca3);
            select.add(option);
        }
    });

    select.value = currentSelection;
}

export function updateCountryPanel(country) {
    if (!country) return;
    
    const flag = document.getElementById('countryFlag');
    if (flag && country.flag) {
        flag.src = country.flag;
        flag.alt = `${country.name} Flag`;
        flag.classList.remove('d-none');
    }

    setText('countryName', country.name ?? '-');
    setText('countryRegion', country.region ?? '-');
    setText('countryCapital', country.capital ?? '-');
    setText('countryCurrency', country.currency ?? country.currency_code ?? '-');
    setText('countryPopulation', formatNumber(country.population ?? 0));
    setText('countryLanguage', country.language ?? '-');
}