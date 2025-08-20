import './bootstrap';

import Swal from 'sweetalert2';
import katex from 'katex';
import Quill from 'quill';
import TomSelect from 'tom-select';
import ApexCharts from 'apexcharts';

import { openMathPopup, attachFormulaClickHandler } from './math-popup';

// global expose (যদি Blade থেকে দরকার হয়)
window.Swal = Swal;
window.katex = katex;
window.Quill = Quill;
window.TomSelect = TomSelect;
window.ApexCharts = ApexCharts;
window.openMathPopup = openMathPopup;

// formula click event globally attaches করা
attachFormulaClickHandler();

// --- Dark mode persistence ---
function applyThemeFromStorage() {
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
        const toggle = document.getElementById('darkToggle');
        if (toggle) toggle.checked = true;
    } else {
        document.documentElement.classList.remove('dark');
        const toggle = document.getElementById('darkToggle');
        if (toggle) toggle.checked = false;
    }
}

function initThemeListener() {
    applyThemeFromStorage();
    const darkToggle = document.getElementById('darkToggle');
    if (darkToggle) {
        darkToggle.onchange = () => {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem(
                'theme',
                document.documentElement.classList.contains('dark') ? 'dark' : 'light'
            );
        };
    }
}

if (document.readyState !== 'loading') {
    initThemeListener();
} else {
    document.addEventListener('DOMContentLoaded', initThemeListener);
}
document.addEventListener('livewire:navigated', initThemeListener);
