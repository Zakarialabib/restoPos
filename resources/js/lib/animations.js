import { interpolate } from 'framer-motion';

// Slide animations
export const slideVariants = {
    enter: (direction) => ({
        x: direction > 0 ? 1000 : -1000,
        opacity: 0
    }),
    center: {
        zIndex: 1,
        x: 0,
        opacity: 1
    },
    exit: (direction) => ({
        zIndex: 0,
        x: direction < 0 ? 1000 : -1000,
        opacity: 0
    })
};

// Fade animations
export const fadeVariants = {
    enter: {
        opacity: 0,
        scale: 0.9
    },
    center: {
        opacity: 1,
        scale: 1
    },
    exit: {
        opacity: 0,
        scale: 1.1
    }
};

export const bounceVariants = {
    enter: {
        scale: 0,
        y: 100
    },
    center: {
        scale: 1,
        y: 0,
        transition: {
            type: "spring",
            stiffness: 200,
            damping: 15
        }
    },
    exit: {
        scale: 0,
        y: -100
    }
};

export const flipVariants = {
    enter: {
        rotateX: 90,
        opacity: 0
    },
    center: {
        rotateX: 0,
        opacity: 1,
        transition: {
            duration: 0.5
        }
    },
    exit: {
        rotateX: -90,
        opacity: 0
    }
};

// Stagger children animation
export const staggerContainerVariants = {
    hidden: { 
        opacity: 0 
    },
    show: {
        opacity: 1,
        transition: {
            staggerChildren: 0.1
        }
    }
};

export const staggerItemVariants = {
    hidden: { 
        opacity: 0,
        y: 20
    },
    show: { 
        opacity: 1,
        y: 0,
        transition: {
            type: "spring",
            stiffness: 260,
            damping: 20
        }
    }
};

export const zoomVariants = {
    enter: {
        scale: 1.5,
        opacity: 0
    },
    center: {
        scale: 1,
        opacity: 1,
        transition: {
            duration: 0.5
        }
    },
    exit: {
        scale: 0.5,
        opacity: 0
    }
};

// Utility functions for animations
export const interpolateValue = (input, inputRange, outputRange) => {
    return interpolate(input, inputRange, outputRange);
};

export const getSpringTransition = (stiffness = 100, damping = 10) => ({
    type: "spring",
    stiffness,
    damping
});

export const getEaseTransition = (duration = 0.5) => ({
    type: "tween",
    ease: "easeInOut",
    duration
}); 