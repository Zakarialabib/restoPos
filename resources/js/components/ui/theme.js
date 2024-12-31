// Color palette
export const colors = {
    primary: {
        50: '#f0f9f0',
        100: '#dcf0dc',
        200: '#bce3bc',
        300: '#92d392',
        400: '#60ba60',
        500: '#2e7d32',
        600: '#1b5e20',
        700: '#1a4731',
        800: '#173724',
        900: '#14261d',
    },
    secondary: {
        50: '#fff8e1',
        100: '#ffecb3',
        200: '#ffe082',
        300: '#ffd54f',
        400: '#ffca28',
        500: '#ffc107',
        600: '#ffb300',
        700: '#ffa000',
        800: '#ff8f00',
        900: '#ff6f00',
    },
    accent: {
        yellow: '#ffeb3b',
        orange: '#ff9800',
    },
    text: {
        primary: '#2e7d32',
        secondary: '#4b5563',
    }
};

// Animation variants
export const animations = {
    fadeIn: {
        initial: { opacity: 0 },
        animate: { opacity: 1 },
        exit: { opacity: 0 },
    },
    slideUp: {
        initial: { y: 20, opacity: 0 },
        animate: { y: 0, opacity: 1 },
        exit: { y: 20, opacity: 0 },
    },
    slideInRight: {
        initial: { x: '100%' },
        animate: { x: 0 },
        exit: { x: '100%' },
    },
    scale: {
        initial: { scale: 0.95, opacity: 0 },
        animate: { scale: 1, opacity: 1 },
        exit: { scale: 0.95, opacity: 0 },
    },
    slideUp: {
        initial: { y: 20, opacity: 0 },
        animate: { y: 0, opacity: 1 },
        exit: { y: 20, opacity: 0 },
    },
    stagger: {
        initial: { opacity: 0 },
        animate: {
            opacity: 1,
            transition: {
                staggerChildren: 0.1,
            },
        },
    },
};

// Transition presets
export const transitions = {
    spring: {
        type: "spring",
        stiffness: 200,
        damping: 20,
    },
    smooth: {
        duration: 0.4,
        ease: [0.43, 0.13, 0.23, 0.96],
    },
};

// Common component variants
export const componentVariants = {
    productCard: {
        initial: { scale: 1 },
        hover: { 
            scale: 1.05,
            transition: {
                duration: 0.2,
                ease: "easeInOut",
            },
        },
        tap: { 
            scale: 0.95,
            transition: {
                duration: 0.1,
            },
        },
    },
    button: {
        initial: { scale: 1 },
        hover: { scale: 1.05 },
        tap: { scale: 0.95 },
    },
    input: "w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent",
    badge: {
        base: "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium",
        success: "bg-primary-100 text-primary-800",
        warning: "bg-yellow-100 text-yellow-800",
    },
    container: "max-w-7xl mx-auto px-4 sm:px-6 lg:px-8",
}; 