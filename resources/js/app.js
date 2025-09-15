import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


const userType = document.getElementById('user-type');
const otherFieldContainer = document.getElementById('other-field-container');

userType.addEventListener('change', function () {
    if (this.value === 'Autres') {
        otherFieldContainer.classList.remove('hidden');
    } else {
        otherFieldContainer.classList.add('hidden');
    }
});
if(localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)){
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}
const toggleButton = document.getElementById('darkModeToggle');
toggleButton.addEventListener('click', () => {
    document.documentElement.classList.toggle('dark');
    if(document.documentElement.classList.contains('dark')){
        localStorage.setItem('theme', 'dark');
        toggleButton.textContent = 'Mode Clair';
    } else {
        localStorage.setItem('theme', 'light');
        toggleButton.textContent = 'Mode Sombre';
    }
});
 const html = document.documentElement;
    const btn = document.getElementById('toggleDark');

    btn.addEventListener('click', () => {
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    });

    // Charger la préférence utilisateur
    if (localStorage.getItem('theme') === 'dark') {
        html.classList.add('dark');
    }


 
document.addEventListener('DOMContentLoaded', () => {
    // --- Gestion du Menu Mobile ---
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const openIcon = document.getElementById('menu-icon-open');
    const closeIcon = document.getElementById('menu-icon-close');

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', () => {
            const isExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
            mobileMenuToggle.setAttribute('aria-expanded', !isExpanded);
            
            // Toggle des classes pour l'animation
            mobileMenu.classList.toggle('opacity-0');
            mobileMenu.classList.toggle('scale-95');
            mobileMenu.classList.toggle('pointer-events-none');
            
            // Toggle des icônes
            openIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });
    }

    // --- Gestion du Thème (Dark/Light Mode) ---
    const themeToggle = document.getElementById('theme-toggle');
    const lightIcon = document.getElementById('theme-icon-light');
    const darkIcon = document.getElementById('theme-icon-dark');
    const html = document.documentElement;

    // Appliquer le thème au chargement
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
        lightIcon.classList.add('hidden');
        darkIcon.classList.remove('hidden');
    } else {
        html.classList.remove('dark');
        lightIcon.classList.remove('hidden');
        darkIcon.classList.add('hidden');
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            lightIcon.classList.toggle('hidden');
            darkIcon.classList.toggle('hidden');
            
            if (html.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        });
    }

    // --- Effet de l'en-tête au défilement (Optionnel) ---
    const header = document.getElementById('app-header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            header.classList.add('shadow-md');
        } else {
            header.classList.remove('shadow-md');
        }
    });
});
