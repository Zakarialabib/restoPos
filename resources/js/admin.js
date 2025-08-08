import './bootstrap';
// import './echo';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import flatpickr from "flatpickr";
window.flatpickr = flatpickr;

import PerfectScrollbar from "perfect-scrollbar";
import "perfect-scrollbar/css/perfect-scrollbar.css";
window.PerfectScrollbar = PerfectScrollbar;

import mask from '@alpinejs/mask'; 
Alpine.plugin(mask);


// Admin theme configuration
Alpine.data("mainTheme", () => {

    const loadingMask = {
        pageLoaded: false,
        showText: false,
        isDarkMode: localStorage.getItem('darkMode') === 'true',

        init() {
            window.onload = () => {
                this.pageLoaded = true;
            };
            this.animateCharge();
        },
        animateCharge() {
            setInterval(() => {
                this.showText = true;
                setTimeout(() => {
                    this.showText = false;
                }, 2000);
            }, 4000);
        },
    };

    const isRtl = () => {
        return document.documentElement.getAttribute('dir') === 'rtl';
    };

    return {
        isRtl,
        loadingMask,
        isSidebarOpen: window.innerWidth >= 1024 ? sessionStorage.getItem("sidebarOpen") !== "false" : false,
        isSidebarHovered: false,
        viewMode: localStorage.getItem('viewMode') || 'grid',

        init() {
            // Initialize sidebar state based on screen size
            this.handleWindowResize();
            
            // Listen for window resize events
            window.addEventListener('resize', this.handleWindowResize.bind(this));

            // Watch for viewMode changes and save to localStorage
            this.$watch('viewMode', (newValue) => {
                localStorage.setItem('viewMode', newValue);
            });
        },

        handleSidebarToggle() {
            this.isSidebarOpen = !this.isSidebarOpen;
            if (window.innerWidth >= 1024) {
                sessionStorage.setItem("sidebarOpen", this.isSidebarOpen.toString());
            }
        },

        handleSidebarHover(value) {
            if (window.innerWidth < 1024) return;
            this.isSidebarHovered = value;
        },

        handleWindowResize() {
            if (window.innerWidth < 1024) {
                this.isSidebarOpen = false;
                this.isSidebarHovered = false;
            }
        },
        
        scrollingDown: false,
        scrollingUp: false,
    };
});

Livewire.start();