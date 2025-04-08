import './bootstrap';
import './echo';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import flatpickr from "flatpickr";
window.flatpickr = flatpickr;

import PerfectScrollbar from "perfect-scrollbar";
import "perfect-scrollbar/css/perfect-scrollbar.css";
window.PerfectScrollbar = PerfectScrollbar;

import mask from '@alpinejs/mask'; 
Alpine.plugin(mask);

// Admin theme configuration
Alpine.data("mainTheme", () => ({
    loadingMask: {
        pageLoaded: false,
        showText: false,
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
    },

    isRtl() {
        return document.documentElement.getAttribute('dir') === 'rtl';
    },

    isSidebarOpen: window.innerWidth >= 1024 ? sessionStorage.getItem("sidebarOpen") !== "false" : false,
    isSidebarHovered: false,
    scrollingDown: false,
    scrollingUp: false,

    init() {
        this.handleWindowResize();
        window.addEventListener('resize', this.handleWindowResize.bind(this));
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
    }
}));

Livewire.start(); 