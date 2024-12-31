import React, { useState } from "react";
import { motion } from "framer-motion";
import { cn } from "@/lib/utils";
import { Image as ImageIcon } from "lucide-react";

const STYLE_VARIANTS = {
  default: {
    card: "bg-white/90 backdrop-blur-sm border border-gray-300 rounded-lg shadow-md",
    category: "bg-yellow-500/80 text-white px-2 py-1 rounded-md text-sm font-medium",
    title: "text-gray-900 font-semibold text-xl",
    description: "text-gray-700 text-base",
    price: "text-yellow-700 font-bold text-lg",
    button: "bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-4 py-2 rounded-md",
  },
  rustic: {
    card: "bg-green-900/90 backdrop-blur-md border border-green-700/50 rounded-xl shadow-lg",
    category: "bg-green-600/80 text-white px-2 py-1 rounded-md text-sm font-medium",
    title: "text-green-200 font-serif text-2xl",
    description: "text-green-300/90 text-base leading-relaxed",
    price: "text-green-300 font-bold text-lg",
    button: "bg-green-700 hover:bg-green-800 text-white font-medium px-4 py-2 rounded-md border border-green-600",
  },
  elegant: {
    card: "bg-gradient-to-br from-gray-900/95 to-gray-800/95 backdrop-blur-md border border-gray-700 rounded-xl shadow-lg",
    category: "bg-purple-600/80 text-white px-2 py-1 rounded-md text-sm font-medium",
    title: "text-white font-light tracking-wide text-2xl",
    description: "text-gray-400 font-light text-base leading-relaxed",
    price: "text-purple-400 font-light text-lg",
    button: "bg-purple-600/80 hover:bg-purple-700 text-white font-medium px-4 py-2 rounded-md",
  },
  spicy: {
    card: "bg-gradient-to-br from-red-900/90 to-orange-900/90 backdrop-blur-md border border-red-600/50 rounded-xl shadow-lg",
    category: "bg-red-600/80 text-white px-2 py-1 rounded-md text-sm font-medium",
    title: "text-orange-200 font-bold text-2xl",
    description: "text-orange-300/90 text-base leading-relaxed",
    price: "text-red-400 font-bold text-lg",
    button: "bg-red-700/80 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-md border border-red-600",
  },
  showcase: {
    card: "bg-gradient-to-br from-gray-900/95 to-gray-800/95 backdrop-blur-md border-2 border-yellow-600/50 rounded-2xl shadow-2xl",
    category: "bg-yellow-600/80 text-white px-3 py-2 rounded-md text-lg font-medium",
    title: "text-white font-extrabold text-3xl",
    description: "text-gray-400 text-xl leading-relaxed",
    price: "text-yellow-400 font-bold text-5xl",
    button: "bg-yellow-600 hover:bg-yellow-700 text-white font-medium px-6 py-3 rounded-md focus:ring-yellow-500 text-xl",
  },
  vibrant: {
    card: "bg-gradient-to-br from-orange-300/90 to-orange-200 backdrop-blur-md border border-orange-400 rounded-lg shadow-md",
    category: "bg-orange-500 text-orange-900 px-2 py-1 rounded-md text-sm font-medium",
    title: "text-orange-900 font-bold text-xl",
    description: "text-orange-800 text-base",
    price: "text-orange-900 font-bold text-lg",
    button: "bg-orange-600 hover:bg-orange-700 text-white font-medium px-4 py-2 rounded-md",
  },
  soothing: {
    card: "bg-gradient-to-br from-teal-200/90 to-teal-100 backdrop-blur-md border border-teal-400 rounded-lg shadow-md",
    category: "bg-teal-400 text-teal-900 px-2 py-1 rounded-md text-sm font-medium",
    title: "text-teal-900 font-semibold text-xl",
    description: "text-teal-800 text-base",
    price: "text-teal-900 font-bold text-lg",
    button: "bg-teal-600 hover:bg-teal-700 text-white font-medium px-4 py-2 rounded-md",
  },
  modern: {
    card: "bg-gradient-to-br from-gray-200/90 via-gray-100 to-gray-200 rounded-lg shadow-md border border-gray-300",
    category: "bg-gray-800 text-gray-100 px-2 py-1 rounded-md text-sm font-medium",
    title: "text-gray-900 font-semibold tracking-wide text-xl",
    description: "text-gray-700 text-base",
    price: "text-green-700 font-bold text-lg",
    button: "bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-md",
  },
  bold: {
    card: "bg-gradient-to-b from-red-200/90 via-pink-200 to-white rounded-lg shadow-md border border-pink-300",
    category: "bg-red-600 text-white px-2 py-1 rounded-md text-sm font-medium",
    title: "text-red-700 font-extrabold text-2xl",
    description: "text-red-500 text-base",
    price: "text-red-600 font-bold text-lg",
    button: "bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-md",
  },
};

const ProductCard = ({
  product,
  index,
  featured = false,
  className,
  isGrid = false,
  isList = false,
  variant = "default"
}) => {
  const [imageError, setImageError] = useState(false);
  const styles = STYLE_VARIANTS[variant] || STYLE_VARIANTS.default;

  const handleImageError = () => {
    setImageError(true);
  };

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      exit={{ opacity: 0, y: -20 }}
      className={cn(
        styles.card,
        "transition-all duration-700",
        "rounded-2xl relative",
        isGrid ? "h-full flex flex-col p-4 gap-y-4" : isList ? "p-8 flex flex-row gap-x-6 items-center" : "p-8 flex flex-row gap-x-6 items-center",
        featured ? "col-span-2 row-span-2" : "",
        className
      )}
      transition={{
        type: "spring",
        stiffness: 300,
        damping: 20,
        delay: index * 0.1,
      }}
    >
      {/* Background Gradient Overlay for Featured Items */}
      {featured && variant === 'showcase' && (
        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent" />
      )}

      {/* Image Section */}
      <div className={cn(
        isGrid ? "w-full h-[60%] mb-4" : featured ? "w-full h-[70%]" : "w-1/2 pr-6",
        "relative overflow-hidden"
      )}>
        {!imageError && product.image ? (
          <motion.img
            src={product.image}
            alt={product.name}
            onError={handleImageError}
            className={cn(
              "rounded-xl shadow-lg object-cover",
              isGrid ? "w-full h-full" : featured ? "w-full h-full object-center" : "w-full h-full"
            )}
            layoutId={`product-image-${product.id}`}
          />
        ) : (
          <div className={cn(
            "flex items-center justify-center bg-gray-800/50 rounded-xl",
            isGrid ? "w-full h-full" : featured ? "w-full h-[500px]" : "w-full h-64"
          )}>
            <ImageIcon className={cn("text-gray-400", featured ? "w-32 h-32" : "w-20 h-20")} />
          </div>
        )}
      </div>

      {/* Information Section */}
      <div className={cn(
        "flex flex-col",
        isGrid ? "w-full justify-between" : featured ? "w-full" : "w-1/2 justify-between",
        variant === 'showcase' && "relative z-10"
      )}>
        {/* Category */}
        {product.category && (
          <motion.span
            className={cn(
              styles.category,
              "rounded-full font-medium px-4 py-1 inline-block mb-2",
              featured && "text-lg px-5 py-1.5"
            )}
            layoutId={`product-category-${product.id}`}
          >
            {product.category.name}
          </motion.span>
        )}

        {/* Name */}
        <motion.h1
          className={cn(
            styles.title,
            "font-bold tracking-wide",
            isGrid ? "text-2xl" : featured ? "text-5xl" : "text-3xl"
          )}
          layoutId={`product-name-${product.id}`}
        >
          {product.name}
        </motion.h1>

        {/* Description - Only show in carousel view or if featured */}
        {(!isGrid || featured) && product.description && (
          <motion.p
            className={cn(
              styles.description,
              "leading-relaxed",
              featured ? "text-xl" : "text-base"
            )}
            layoutId={`product-description-${product.id}`}
          >
            {featured ? product.description : product.description.slice(0, 80) + "..."}
          </motion.p>
        )}

        {/* Price */}
        <motion.div
          className={cn(
            styles.price,
            "font-bold",
            isGrid ? "text-3xl mt-auto" : featured ? "text-6xl mt-6" : "text-4xl mt-4"
          )}
          layoutId={`product-price-${product.id}`}
        >
          {product.prices?.length > 0 ? (
            <span>From {Math.min(...product.prices.map((p) => p.price))} DH</span>
          ) : (
            <span>{product.price} DH</span>
          )}
        </motion.div>

        {/* Price Buttons - Only show in carousel view */}
        {!isGrid && product.prices?.length > 0 && (
          <div className="grid grid-cols-2 gap-3 mt-4">
            {product.prices.map((price) => (
              <button
                key={price.size}
                className={cn(
                  styles.button,
                  "px-4 py-2 rounded-full transform transition duration-300",
                  "focus:outline-none focus:ring-2 focus:ring-opacity-50",
                  featured && "text-lg py-3"
                )}
              >
                {price.size} - {price.price} DH
              </button>
            ))}
          </div>
        )}
      </div>
    </motion.div>
  );
};

export default ProductCard;