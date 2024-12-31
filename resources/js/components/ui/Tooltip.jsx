import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

const Tooltip = ({ children, content, position = 'top' }) => {
    const [isVisible, setIsVisible] = useState(false);

    const positions = {
        top: '-top-2 left-1/2 -translate-x-1/2 -translate-y-full',
        bottom: '-bottom-2 left-1/2 -translate-x-1/2 translate-y-full',
        left: '-left-2 top-1/2 -translate-x-full -translate-y-1/2',
        right: '-right-2 top-1/2 translate-x-full -translate-y-1/2'
    };

    const arrows = {
        top: 'bottom-0 left-1/2 -translate-x-1/2 translate-y-full border-t-gray-800',
        bottom: 'top-0 left-1/2 -translate-x-1/2 -translate-y-full border-b-gray-800',
        left: 'right-0 top-1/2 -translate-y-1/2 translate-x-full border-l-gray-800',
        right: 'left-0 top-1/2 -translate-y-1/2 -translate-x-full border-r-gray-800'
    };

    return (
        <div
            className="relative inline-block"
            onMouseEnter={() => setIsVisible(true)}
            onMouseLeave={() => setIsVisible(false)}
        >
            {children}
            <AnimatePresence>
                {isVisible && (
                    <motion.div
                        initial={{ opacity: 0, scale: 0.9 }}
                        animate={{ opacity: 1, scale: 1 }}
                        exit={{ opacity: 0, scale: 0.9 }}
                        transition={{ duration: 0.15 }}
                        className={`absolute z-50 ${positions[position]}`}
                    >
                        <div className="relative">
                            <div className="bg-gray-800 text-white text-sm rounded-lg py-1 px-2 whitespace-nowrap">
                                {content}
                            </div>
                            <div
                                className={`absolute w-2 h-2 transform rotate-45 bg-gray-800 ${arrows[position]}`}
                            />
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
        </div>
    );
};

export default Tooltip; 