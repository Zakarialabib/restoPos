import React from "react";
import { motion, AnimatePresence } from "framer-motion";
import { cn } from "@/lib/utils";

const Grid = ({
  items,
  breakpointCols = {
    default: 4,
    1536: 6,
    1280: 5,
    1024: 4,
    768: 3,
    640: 2,
  },
  className,
  itemClassName,
  loading = false,
}) => {
  // Animation variants for the container - simplified
  const containerVariants = {
    show: {
      transition: {
        staggerChildren: 0.05
      }
    }
  };

  // Responsive grid classes based on breakpoints
  const gridClasses = cn(
    "grid gap-x-6 mx-auto",
    `grid-cols-${breakpointCols.default}`,
    `md:grid-cols-${breakpointCols[768]}`,
    `lg:grid-cols-${breakpointCols[1024]}`,
    `xl:grid-cols-${breakpointCols[1280]}`,
    `2xl:grid-cols-${breakpointCols[1536]}`,
    className
  );

  return (
    <motion.div
      variants={containerVariants}
      animate="show"
      className="w-full"
    >
      <AnimatePresence mode="wait">
        {loading ? (
          <motion.div
            key="loading"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="w-full flex justify-center py-12"
          >
            <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
          </motion.div>
        ) : (
          <div className={gridClasses}>
            {items.map((item, index) => (
              <motion.div
                key={index}
                className={cn(
                  "rounded-lg",
                  itemClassName
                )}
              >
                {React.cloneElement(item, {
                  index,
                  featured: index === 0,
                })}
              </motion.div>
            ))}
          </div>
        )}
      </AnimatePresence>
    </motion.div>
  );
};

export default Grid;