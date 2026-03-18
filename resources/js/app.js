import './bootstrap';

import Swal from 'sweetalert2';
import katex from 'katex';
import Quill from 'quill';
import TomSelect from 'tom-select';
import ApexCharts from 'apexcharts';
import { openMathPopup, attachFormulaClickHandler } from './math-popup';

window.Swal = Swal;
window.katex = katex;
window.Quill = Quill;
window.TomSelect = TomSelect;
window.ApexCharts = ApexCharts;
window.openMathPopup = openMathPopup;

function bindClickOnce(element, key, handler) {
    if (!element || element.dataset[key] === 'true') return;
    element.addEventListener('click', handler);
    element.dataset[key] = 'true';
}

function initializeUserMenu() {
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');

    bindClickOnce(userMenuButton, 'userMenuBound', (event) => {
        event.stopPropagation();
        userMenu?.classList.toggle('hidden');
    });

    if (document.body.dataset.userMenuDocumentBound !== 'true') {
        document.addEventListener('click', (event) => {
            if (
                userMenu &&
                !userMenu.classList.contains('hidden') &&
                !userMenu.contains(event.target) &&
                !userMenuButton?.contains(event.target)
            ) {
                userMenu.classList.add('hidden');
            }
        });
        document.body.dataset.userMenuDocumentBound = 'true';
    }
}

function initializeApp() {
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const storedTheme = localStorage.getItem('theme');

    function applyTheme(mode) {
        if (mode === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
            mode = 'light';
        }

        const darkToggle = document.getElementById('darkToggle');
        if (darkToggle) {
            darkToggle.checked = mode === 'dark';
        }

        localStorage.setItem('theme', mode);
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { mode } }));
    }

    function toggleTheme() {
        const mode = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
        applyTheme(mode);
    }

    const initialMode = storedTheme ? storedTheme : (prefersDark ? 'dark' : 'light');
    applyTheme(initialMode === 'dark' ? 'dark' : 'light');

    const darkToggle = document.getElementById('darkToggle');
    if (darkToggle) {
        darkToggle.onchange = () => {
            applyTheme(darkToggle.checked ? 'dark' : 'light');
        };
    }

    document.querySelectorAll('[data-trigger-theme-toggle]').forEach((trigger) => {
        trigger.onclick = () => toggleTheme();
    });

    window.toggleTheme = toggleTheme;

    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebar-overlay');
    const sidebarContent = document.querySelector('[data-sidebar-content]');

    function markActiveLinks() {
        if (!sidebar) return;

        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach((link) => {
            const href = link.getAttribute('href');
            const isActive = href === currentPath;

            link.classList.toggle('active-link', isActive);
            if (isActive) {
                link.classList.add('bg-[#dfe3e8]', 'text-gray-950', 'dark:bg-gray-800', 'dark:text-white', 'font-semibold');
                const parentSubmenu = link.closest('.submenu');
                if (parentSubmenu && !sidebar.classList.contains('w-20')) {
                    const submenuItems = parentSubmenu.querySelector('.submenu-items');
                    const arrow = parentSubmenu.querySelector('.arrow');
                    if (submenuItems) submenuItems.style.maxHeight = submenuItems.scrollHeight + 'px';
                    arrow?.classList.add('rotate-180');
                }
            } else {
                link.classList.remove('bg-[#dfe3e8]', 'text-gray-950', 'dark:bg-gray-800', 'dark:text-white', 'font-semibold', 'active-link');
            }
        });
    }

    function setSidebarState(collapsed) {
        if (!sidebar || !sidebarContent) return;

        const sidebarTextEls = document.querySelectorAll('.sidebar-text');
        const navLinks = document.querySelectorAll('.nav-link, .submenu-toggle');
        const submenus = document.querySelectorAll('.submenu');

        if (collapsed) {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');
            sidebarContent.classList.remove('md:ml-64');
            sidebarContent.classList.add('md:ml-20');
            sidebarTextEls.forEach((el) => el.classList.add('hidden'));
            navLinks.forEach((link) => link.classList.add('justify-center'));
            submenus.forEach((menu) => {
                const items = menu.querySelector('.submenu-items');
                const arrow = menu.querySelector('.arrow');
                arrow?.classList.add('hidden');
                if (items) {
                    items.style.maxHeight = null;
                    items.classList.add('absolute', 'left-full', 'top-0', 'ml-2', 'z-20', 'bg-white', 'dark:bg-gray-700', 'rounded-md', 'shadow-lg', 'p-2', 'min-w-[150px]', 'invisible', 'opacity-0', 'scale-95');
                }
            });
            localStorage.setItem('sidebar', 'collapsed');
        } else {
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');
            sidebarContent.classList.remove('md:ml-20');
            sidebarContent.classList.add('md:ml-64');
            sidebarTextEls.forEach((el) => el.classList.remove('hidden'));
            navLinks.forEach((link) => link.classList.remove('justify-center'));
            submenus.forEach((menu) => {
                const items = menu.querySelector('.submenu-items');
                const arrow = menu.querySelector('.arrow');
                arrow?.classList.remove('hidden');
                if (items) {
                    items.classList.remove('absolute', 'left-full', 'top-0', 'ml-2', 'z-20', 'bg-white', 'dark:bg-gray-700', 'rounded-md', 'shadow-lg', 'p-2', 'min-w-[150px]', 'invisible', 'opacity-0', 'scale-95');
                    if (items.querySelector('.active-link')) {
                        items.style.maxHeight = items.scrollHeight + 'px';
                    } else {
                        items.style.maxHeight = null;
                    }
                }
            });
            localStorage.setItem('sidebar', 'expanded');
        }
    }

    if (localStorage.getItem('sidebar') === 'collapsed') {
        setSidebarState(true);
    } else {
        setSidebarState(false);
    }

    bindClickOnce(sidebarCollapse, 'sidebarCollapseBound', () => {
        const isCollapsed = sidebar?.classList.contains('w-20');
        setSidebarState(!isCollapsed);
    });

    bindClickOnce(sidebarToggle, 'sidebarToggleBound', () => {
        if (!sidebar) return;

        if (window.innerWidth >= 768) {
            const isCollapsed = sidebar.classList.contains('w-20');
            setSidebarState(!isCollapsed);
            return;
        }

        sidebar.classList.toggle('-translate-x-full');
        overlay?.classList.toggle('hidden');
    });

    bindClickOnce(overlay, 'overlayBound', () => {
        sidebar?.classList.add('-translate-x-full');
        overlay?.classList.add('hidden');
    });

    document.querySelectorAll('.submenu-toggle').forEach((toggleBtn) => {
        if (toggleBtn.dataset.submenuBound === 'true') return;

        const submenuItems = toggleBtn.nextElementSibling;
        const arrow = toggleBtn.querySelector('svg.arrow');
        if (submenuItems && arrow) {
            toggleBtn.addEventListener('click', () => {
                if (sidebar && sidebar.classList.contains('w-20')) return;
                if (submenuItems.style.maxHeight) {
                    submenuItems.style.maxHeight = null;
                    arrow.classList.remove('rotate-180');
                } else {
                    submenuItems.style.maxHeight = submenuItems.scrollHeight + 'px';
                    arrow.classList.add('rotate-180');
                }
            });
            toggleBtn.dataset.submenuBound = 'true';
        }
    });

    initializeUserMenu();
    markActiveLinks();
    attachFormulaClickHandler();
}

function renderMath() {
    if (window.MathJax && typeof window.MathJax.typesetPromise === 'function') {
        window.MathJax.typesetPromise();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initializeApp();
    renderMath();
});

document.addEventListener('livewire:navigated', () => {
    initializeApp();
    renderMath();
});

document.addEventListener('livewire:update', renderMath);
