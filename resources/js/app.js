import './bootstrap';

import {
    Livewire,
    Alpine
} from '../../vendor/livewire/livewire/dist/livewire.esm';


import flatpickr from "flatpickr";
window.flatpickr = flatpickr;

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
        loadingMask
    };
});

Livewire.start()