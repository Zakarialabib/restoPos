import React, { useState, useEffect } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { cn } from "@/lib/utils";

// Animation variants for slide transition
const slideVariants = {
    enter: (direction) => ({
        x: direction > 0 ? "100%" : "-100%",
        opacity: 0,
    }),
    center: {
        zIndex: 1,
        x: 0,
        opacity: 1,
    },
    exit: (direction) => ({
        zIndex: 0,
        x: direction < 0 ? "100%" : "-100%",
        opacity: 0,
    }),
};

// Animation variants for fade transition
const fadeVariants = {
    enter: {
        opacity: 0,
        scale: 0.95,
    },
    center: {
        opacity: 1,
        scale: 1,
    },
    exit: {
        opacity: 0,
        scale: 1.05,
    },
};

const AutoSlide = ({
    items,
    interval = 5000,
    transitionType = "slide",
    className,
    pauseOnHover = true,
}) => {
    const [[page, direction], setPage] = useState([0, 0]);
    const [isPaused, setIsPaused] = useState(false);

    const itemIndex = Math.abs(page % items.length);
    const variants = transitionType === "slide" ? slideVariants : fadeVariants;

    const paginate = (newDirection) => {
        setPage([page + newDirection, newDirection]);
    };

    useEffect(() => {
        if (!interval || isPaused) return;

        const timer = setInterval(() => {
            paginate(1);
        }, interval);

        return () => clearInterval(timer);
    }, [interval, isPaused, page]);

    return (
        <div
            className={cn("relative overflow-hidden w-full", className)}
            onMouseEnter={() => pauseOnHover && setIsPaused(true)}
            onMouseLeave={() => pauseOnHover && setIsPaused(false)}
        >
            <AnimatePresence initial={false} custom={direction} mode="wait">
                <motion.div
                    key={page}
                    custom={direction}
                    variants={variants}
                    initial="enter"
                    animate="center"
                    exit="exit"
                    transition={{
                        x: { type: "spring", stiffness: 300, damping: 30 },
                        opacity: { duration: 0.4 },
                    }}
                    className="absolute inset-0 flex items-center justify-center"
                >
                    {items[itemIndex]}
                </motion.div>
            </AnimatePresence>
        </div>
    );
};

export default AutoSlide;