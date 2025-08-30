import './bootstrap';

import Swal from 'sweetalert2';
import katex from 'katex';
import Quill from 'quill';
import TomSelect from 'tom-select';
import ApexCharts from 'apexcharts';
import { openMathPopup, attachFormulaClickHandler } from './math-popup';
import Alpine from 'alpinejs';

// global expose (যাতে Blade থেকে সরাসরি ব্যবহার করা যায়)
window.Swal = Swal;
window.katex = katex;
window.Quill = Quill;
window.TomSelect = TomSelect;
window.ApexCharts = ApexCharts;
window.openMathPopup = openMathPopup;
window.Alpine = Alpine;

// simple toast helper so other scripts can call it globally
window.showToast = function (message, type = 'success') {
    if (window.Swal) {
        Swal.fire({
            toast: true,
            icon: type,
            title: message,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }
};

// register an Alpine component for the media manager before Alpine starts
document.addEventListener('alpine:init', () => {
    Alpine.data('mediaManager', () => ({
        isUploaderOpen: false,
        isDetailsDrawerOpen: false,
        openUploader() {
            this.isUploaderOpen = true;
        },
        init() {
            window.addEventListener('open-details-drawer', () => {
                this.isUploaderOpen = false;
                this.isDetailsDrawerOpen = true;
            });

            window.addEventListener('mediaDeleted', (event) => {
                showToast(event.detail.message);
                this.isDetailsDrawerOpen = false;
            });
        },

        // copy URL to clipboard with graceful fallback
        copyToClipboard(buttonEl) {
            const urlToCopy = buttonEl.dataset.url;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard
                    .writeText(urlToCopy)
                    .then(() => {
                        const originalText = buttonEl.innerText;
                        buttonEl.innerText = 'Copied!';
                        showToast('URL Copied!');
                        setTimeout(() => {
                            buttonEl.innerText = originalText;
                        }, 2000);
                    })
                    .catch((err) => {
                        console.error('Modern copy failed: ', err);
                        this.fallbackCopyToClipboard(urlToCopy, buttonEl);
                    });
            } else {
                this.fallbackCopyToClipboard(urlToCopy, buttonEl);
            }
        },

        fallbackCopyToClipboard(text, buttonEl) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-9999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                const originalText = buttonEl.innerText;
                buttonEl.innerText = 'Copied!';
                showToast('URL Copied!');
                setTimeout(() => {
                    buttonEl.innerText = originalText;
                }, 2000);
            } catch (err) {
                console.error('Fallback copy failed: ', err);
                showToast('Failed to copy URL!', 'error');
            }
            document.body.removeChild(textArea);
        },
    }));
});

Alpine.start();

// delete confirmation handled globally
window.addEventListener('confirm-delete', (event) => {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch('deleteMediaConfirmed', { id: event.detail.id });
        }
    });
});

// show toast when media record is updated
window.addEventListener('mediaUpdated', (event) =>
    showToast(event.detail.message, event.detail.type || 'success')
);

// --- User Menu Dropdown ---
const userMenuButton = document.getElementById('userMenuButton');
const userMenu = document.getElementById('userMenu');
if (userMenuButton && userMenu) {
    userMenuButton.addEventListener('click', (event) => {
        event.stopPropagation();
        userMenu.classList.toggle('hidden');
    });
    document.addEventListener('click', (event) => {
        if (userMenu && !userMenu.classList.contains('hidden') && !userMenu.contains(event.target) && !userMenuButton.contains(event.target)) {
            userMenu.classList.add('hidden');
        }
    });
}
/**
 * এই ফাংশনটি আপনার অ্যাপ্লিকেশনের সমস্ত ইন্টারেক্টিভ এলিমেন্ট চালু করবে।
 */
function initializeApp() {
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
    applyThemeFromStorage();
    const darkToggle = document.getElementById('darkToggle');
    if (darkToggle) {
        darkToggle.onchange = () => {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        };
    }

    // --- Sidebar Logic ---
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const mainContent = document.getElementById('mainContent');

    function setSidebarState(collapsed) {
        if (!sidebar || !mainContent) return;

        const sidebarTextEls = document.querySelectorAll('.sidebar-text');
        const navLinks = document.querySelectorAll('.nav-link, .submenu-toggle');
        const submenus = document.querySelectorAll('.submenu');

        if (collapsed) {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');
            mainContent.classList.remove('md:ml-64');
            mainContent.classList.add('md:ml-20');
            sidebarTextEls.forEach(el => el.classList.add('hidden'));
            navLinks.forEach(link => link.classList.add('justify-center'));
            submenus.forEach(menu => {
                const items = menu.querySelector('.submenu-items');
                const arrow = menu.querySelector('.arrow');
                if (arrow) arrow.classList.add('hidden');
                if (items) {
                    items.style.maxHeight = null;
                    items.classList.add('absolute', 'left-full', 'top-0', 'ml-2', 'z-20', 'bg-white', 'dark:bg-gray-700', 'rounded-md', 'shadow-lg', 'p-2', 'min-w-[150px]', 'invisible', 'opacity-0', 'scale-95');
                }
            });
            localStorage.setItem('sidebar', 'collapsed');
        } else {
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');
            mainContent.classList.remove('md:ml-20');
            mainContent.classList.add('md:ml-64');
            sidebarTextEls.forEach(el => el.classList.remove('hidden'));
            navLinks.forEach(link => link.classList.remove('justify-center'));
            submenus.forEach(menu => {
                const items = menu.querySelector('.submenu-items');
                const arrow = menu.querySelector('.arrow');
                if (arrow) arrow.classList.remove('hidden');
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

    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.contains('w-20');
            setSidebarState(!isCollapsed);
        });
    }

    // --- Mobile Sidebar Toggle ---
    const sidebarToggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebar-overlay');
    if (sidebarToggle && sidebar && overlay) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });
        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    }

    // --- Submenu Toggle ---
    document.querySelectorAll('.submenu-toggle').forEach(toggleBtn => {
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
        }
    });

    // --- User Menu Dropdown ---
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');
    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', (event) => {
            event.stopPropagation();
            userMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', (event) => {
            if (userMenu && !userMenu.classList.contains('hidden') && !userMenu.contains(event.target) && !userMenuButton.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }

    // --- Math formula click handler ---
    attachFormulaClickHandler();
}

// Initial page load
document.addEventListener('DOMContentLoaded', initializeApp);
// After every Livewire navigation
document.addEventListener('livewire:navigated', initializeApp);
