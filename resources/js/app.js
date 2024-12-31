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
import './bootstrap';
import NotificationHandler from './handlers/notifications';
import ModalHandler from './handlers/modal';

// Initialize handlers
document.addEventListener('DOMContentLoaded', () => {
    window.notificationHandler = new NotificationHandler();
    window.modalHandler = new ModalHandler();
});

// Alpine.js directives
document.addEventListener('alpine:init', () => {
    Alpine.directive('sortable', (el, { expression }, { evaluate }) => {
        const options = evaluate(expression);
        new Sortable(el, {
            animation: 150,
            ghostClass: 'bg-gray-100',
            onEnd: ({ oldIndex, newIndex }) => {
                if (oldIndex === newIndex) return;
                
                const items = Array.from(el.children).map(item => 
                    parseInt(item.dataset.id)
                );

                if (options.onSort) {
                    options.onSort(items);
                }
            }
        });
    });

    Alpine.directive('tooltip', (el, { expression, modifiers }, { evaluate }) => {
        const content = evaluate(expression);
        const position = modifiers[0] || 'top';

        tippy(el, {
            content,
            placement: position,
            arrow: true,
            theme: 'light-border'
        });
    });
});

// Custom events
window.addEventListener('livewire:initialized', () => {
    // Handle form submissions
    Livewire.on('form-submitted', ({ message }) => {
        window.dispatchEvent(new CustomEvent('notify', {
            detail: {
                type: 'success',
                message
            }
        }));
    });

    // Handle form errors
    Livewire.on('form-error', ({ message }) => {
        window.dispatchEvent(new CustomEvent('notify', {
            detail: {
                type: 'error',
                message
            }
        }));
    });

    // Handle modal events
    Livewire.on('show-modal', ({ id, options }) => {
        window.dispatchEvent(new CustomEvent('show-modal', {
            detail: { id, options }
        }));
    });

    Livewire.on('hide-modal', ({ id }) => {
        window.dispatchEvent(new CustomEvent('hide-modal', {
            detail: { id }
        }));
    });
}); 

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

    const isRtl = () => {
        return document.documentElement.getAttribute('dir') === 'rtl' || isRtl();
    };

    return {
        isRtl,
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
