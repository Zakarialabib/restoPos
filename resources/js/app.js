import './bootstrap';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

import flatpickr from "flatpickr";
window.flatpickr = flatpickr;

import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
window.Swiper = Swiper;

import PerfectScrollbar from "perfect-scrollbar";
import "perfect-scrollbar/css/perfect-scrollbar.css";
window.PerfectScrollbar = PerfectScrollbar;

import mask from '@alpinejs/mask'; 
Alpine.plugin(mask)

Alpine.data("mainTheme", () => {

    const loadingMask = {
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
    };


    return {
        loadingMask,
        isSidebarOpen: sessionStorage.getItem("sidebarOpen") === "true",
        handleSidebarToggle() {
            this.isSidebarOpen = !this.isSidebarOpen;
            sessionStorage.setItem("sidebarOpen", this.isSidebarOpen.toString());
        },
        isSidebarHovered: false,
        handleSidebarHover(value) {
            if (window.innerWidth < 1024) {
                return;
            }
            this.isSidebarHovered = value;
        },
        handleWindowResize() {
            if (window.innerWidth <= 1024) {
                this.isSidebarOpen = false;
            } else {
                this.isSidebarOpen = true;
            }
        },
        scrollingDown: false,
        scrollingUp: false,
    };
});
Livewire.start()
