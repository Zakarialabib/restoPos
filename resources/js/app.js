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
Alpine.plugin(mask);

// TV Menu Store and Components
Alpine.store('tvMenu', {
    // State
    displayMode: 'SHOWCASE',
    currentTheme: 'default',
    autoRotate: false,
    rotationInterval: 10000,
    isFullScreen: false,
    showSettings: false,
    isMuted: true,
    currentCategory: null,
    currentSlideIndex: 0,
    isTransitioning: false,
    
    // Theme and display mode configurations
    displayModes: {},
    themes: {},
    
    // Initialize store
    init() {
        this.loadConfigurations();
        this.setupEventListeners();
    },
    
    // Load configurations from ThemeService
    loadConfigurations() {
        this.displayModes = window.tvMenuConfig?.displayModes || {};
        this.themes = window.tvMenuConfig?.themes || {};
    },
    
    // Setup global event listeners
    setupEventListeners() {
        window.addEventListener('displayModeChanged', (event) => {
            this.displayMode = event.detail.mode;
        });
        
        window.addEventListener('themeChanged', (event) => {
            this.currentTheme = event.detail.theme;
            this.applyThemeVariables();
        });
        
        window.addEventListener('autoRotateChanged', (event) => {
            this.autoRotate = event.detail.status;
        });
    },
    
    // Apply theme CSS variables
    applyThemeVariables() {
        const theme = this.themes[this.currentTheme];
        if (!theme) return;
        
        const root = document.documentElement;
        Object.entries(theme).forEach(([key, value]) => {
            if (key !== 'name') {
                root.style.setProperty(`--tv-${key}`, value);
            }
        });
    },
    
    // Get current display mode configuration
    getCurrentDisplayMode() {
        return this.displayModes[this.displayMode] || this.displayModes['SHOWCASE'];
    },
    
    // Get current theme configuration
    getCurrentTheme() {
        return this.themes[this.currentTheme] || this.themes['default'];
    },
    
    // Auto rotate methods
    setupAutoRotate() {
        // This will be handled by the autoRotateManager component
    },
    
    toggleAutoRotate() {
        this.autoRotate = !this.autoRotate;
    }
});

// TV Menu Controller Component
Alpine.data('tvMenuController', () => ({
    // Local state
    currentTime: '',
    weather: {
        temperature: '23°C',
        condition: 'sunny',
        icon: 'wb_sunny'
    },
    
    // Initialize component
    init() {
        this.setupClock();
        this.setupKeyboardShortcuts();
        this.updateCurrentTime();
        
        // Initialize store
        this.$store.tvMenu.init();
    },
    
    // Clock functionality
    setupClock() {
        this.updateCurrentTime();
        setInterval(() => this.updateCurrentTime(), 60000);
    },
    
    updateCurrentTime() {
        const now = new Date();
        const format = this.$wire.uiConfig?.clockFormat === '24h' ? 
            { hour: '2-digit', minute: '2-digit', hour12: false } : 
            { hour: 'numeric', minute: '2-digit', hour12: true };
        this.currentTime = now.toLocaleTimeString([], format);
    },
    
    // Keyboard shortcuts
    setupKeyboardShortcuts() {
        window.addEventListener('keydown', (e) => {
            switch(e.key) {
                case 'Escape':
                    this.$wire.toggleSettings();
                    break;
                case 'f':
                case 'F':
                    this.toggleFullScreen();
                    break;
                case 'm':
                case 'M':
                    this.$wire.toggleMute();
                    break;
                case 'r':
                case 'R':
                    this.$store.tvMenu.autoRotate = !this.$store.tvMenu.autoRotate;
                    this.$wire.toggleAutoRotate();
                    break;
                case 'ArrowRight':
                    this.rotateDisplayMode();
                    break;
                case 'ArrowLeft':
                    this.previousDisplayMode();
                    break;
                case 'ArrowUp':
                case 'ArrowDown':
                    this.navigateCategories(e.key === 'ArrowUp' ? -1 : 1);
                    break;
            }
        });
    },
    
    // Fullscreen functionality
    toggleFullScreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            this.$store.tvMenu.isFullScreen = true;
        } else {
            document.exitFullscreen();
            this.$store.tvMenu.isFullScreen = false;
        }
    },
    
    // Category navigation
    navigateCategories(direction) {
        const categories = this.$wire.categories?.map(cat => cat.id) || [];
        if (!categories.length) return;
        
        const currentIndex = categories.indexOf(this.$store.tvMenu.currentCategory);
        let newIndex;
        
        if (currentIndex === -1) {
            newIndex = direction > 0 ? 0 : categories.length - 1;
        } else {
            newIndex = (currentIndex + direction + categories.length) % categories.length;
        }
        
        this.$wire.setCategory(categories[newIndex]);
    }
}));

// Display Mode Manager Component
Alpine.data('displayModeManager', () => ({
    autoRotateInterval: null,
    
    init() {
        this.setupAutoRotate();
        
        // Watch for auto rotate changes
        this.$watch('$store.tvMenu.autoRotate', () => {
            this.setupAutoRotate();
        });
    },
    
    setupAutoRotate() {
        if (this.autoRotateInterval) {
            clearInterval(this.autoRotateInterval);
        }
        
        if (this.$store.tvMenu.autoRotate) {
            this.autoRotateInterval = setInterval(() => {
                this.rotateDisplayMode();
            }, this.$store.tvMenu.rotationInterval);
        }
    },
    
    rotateDisplayMode() {
        const modes = Object.keys(this.$store.tvMenu.displayModes);
        const currentIndex = modes.indexOf(this.$store.tvMenu.displayMode);
        const nextIndex = (currentIndex + 1) % modes.length;
        
        this.$store.tvMenu.isTransitioning = true;
        
        setTimeout(() => {
            this.$wire.setDisplayMode(modes[nextIndex]);
            
            setTimeout(() => {
                this.$store.tvMenu.isTransitioning = false;
            }, 300);
        }, 200);
    },
    
    previousDisplayMode() {
        const modes = Object.keys(this.$store.tvMenu.displayModes);
        const currentIndex = modes.indexOf(this.$store.tvMenu.displayMode);
        const prevIndex = (currentIndex - 1 + modes.length) % modes.length;
        
        this.$wire.setDisplayMode(modes[prevIndex]);
    },
    
    setDisplayMode(mode) {
        this.$wire.setDisplayMode(mode);
    }
}));

// Theme Manager Component
Alpine.data('themeManager', () => ({
    init() {
        this.applyThemeVariables();
        
        // Watch for theme changes
        this.$watch('$store.tvMenu.currentTheme', () => {
            this.applyThemeVariables();
        });
    },
    
    applyThemeVariables() {
        const theme = this.$store.tvMenu.getCurrentTheme();
        if (!theme) return;
        
        const root = document.documentElement;
        Object.entries(theme).forEach(([key, value]) => {
            if (key !== 'name') {
                root.style.setProperty(`--tv-${key}`, value);
            }
        });
    },
    
    setTheme(themeKey) {
        this.$wire.setTheme(themeKey);
    },
    
    getThemeStyles() {
        const theme = this.$store.tvMenu.getCurrentTheme();
        const effect = this.$wire.uiConfig?.backgroundEffect || 'solid';
        
        switch(effect) {
            case 'gradient':
                return {
                    background: `linear-gradient(to bottom right, ${theme.primary}, ${theme.background})`
                };
            case 'pattern':
                return {
                    backgroundColor: theme.background,
                    backgroundImage: `radial-gradient(${theme.secondary}20 1px, transparent 1px)`,
                    backgroundSize: '20px 20px'
                };
            case 'particles':
                return {
                    backgroundColor: theme.background
                };
            default:
                return {
                    backgroundColor: theme.background
                };
        }
    }
}));

// Auto Rotate Manager Component
Alpine.data('autoRotateManager', () => ({
    interval: null,
    
    init() {
        this.setupRotation();
        
        // Watch for changes
        this.$watch('$store.tvMenu.autoRotate', () => {
            this.setupRotation();
        });
        
        this.$watch('$store.tvMenu.rotationInterval', () => {
            this.setupRotation();
        });
    },
    
    setupRotation() {
        this.clearRotation();
        
        if (this.$store.tvMenu.autoRotate) {
            this.interval = setInterval(() => {
                this.rotate();
            }, this.$store.tvMenu.rotationInterval);
        }
    },
    
    clearRotation() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    },
    
    rotate() {
        const modes = Object.keys(this.$store.tvMenu.displayModes);
        const currentIndex = modes.indexOf(this.$store.tvMenu.displayMode);
        const nextIndex = (currentIndex + 1) % modes.length;
        
        this.$store.tvMenu.isTransitioning = true;
        
        setTimeout(() => {
            this.$wire.setDisplayMode(modes[nextIndex]);
            
            setTimeout(() => {
                this.$store.tvMenu.isTransitioning = false;
            }, 300);
        }, 200);
    },
    
    toggle() {
        this.$store.tvMenu.autoRotate = !this.$store.tvMenu.autoRotate;
        this.$wire.toggleAutoRotate();
    }
}));

// Settings Panel Component
Alpine.data('settingsPanel', () => ({
    init() {
        // Setup click outside to close
        this.$el.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    },
    
    close() {
        this.$wire.toggleSettings();
    },
    
    updateConfig(key, value) {
        this.$wire.updateUiConfig({ [key]: value });
    }
}));

// Product Grid Component
Alpine.data('productGrid', () => ({
    activeIndex: 0,
    
    init() {
        // Initialize grid
    },
    
    nextItem() {
        const products = this.getProductsForCurrentView();
        this.activeIndex = (this.activeIndex + 1) % products.length;
    },
    
    prevItem() {
        const products = this.getProductsForCurrentView();
        this.activeIndex = (this.activeIndex - 1 + products.length) % products.length;
    },
    
    getProductsForCurrentView() {
        return this.$wire.organizedProducts?.all || [];
    },
    
    selectProduct(index) {
        this.activeIndex = index;
    }
}));

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
