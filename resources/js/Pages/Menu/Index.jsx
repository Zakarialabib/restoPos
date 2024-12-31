import React, { useState, useMemo, useCallback } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Head } from '@inertiajs/react';
import { ShoppingCart, Search, ChevronDown, ChevronUp, Plus, Minus, Image as ImageIcon } from 'lucide-react';
import { useCart } from '@/Contexts/CartContext';
import CartDrawer from '@/Components/Cart/CartDrawer';

const ProductCard = ({ product, onAddToCart }) => {
    const [isExpanded, setIsExpanded] = useState(false);
    const [selectedSize, setSelectedSize] = useState(null);
    const [quantity, setQuantity] = useState(1);
    const [imageError, setImageError] = useState(false);

    const handleAddToCart = () => {
        if (product.prices?.length > 0 && !selectedSize) {
            return;
        }
        onAddToCart(product, quantity, selectedSize);
        setIsExpanded(false);
        setSelectedSize(null);
        setQuantity(1);
    };

    const handleImageError = () => {
        setImageError(true);
    };

    return (
        <motion.div
            whileHover={{ y: -5 }}
            className="bg-white/90 backdrop-blur-sm rounded-3xl shadow-Lg border border-gray-400 overflow-hidden relative group hover:shadow-2xl transition-all duration-300"
        >
            <div className="relative h-56 overflow-hidden bg-gradient-to-br from-orange-50 to-orange-100" onClick={() => setIsExpanded(!isExpanded)}>
                {!imageError && product.image ? (
                    <img
                        src={product.image}
                        alt={product.name}
                        onError={handleImageError}
                        className="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                        loading="lazy" // Lazy load images for performance
                    />
                ) : (
                    <div className="w-full h-full flex items-center justify-center bg-gradient-to-br from-orange-50 to-orange-100">
                        <ImageIcon className="w-20 h-20 text-orange-200" />
                    </div>
                )}
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent" />
            </div>

            <div className="p-6">
                <h3 className="text-2xl font-bold text-orange-600 mb-2">{product.name}</h3>
                <p className="text-base text-gray-600 line-clamp-2 mb-4">{product.description}</p>

                <div className="flex items-center justify-between">
                    {product.prices?.length > 0 ? (
                        <div className="space-y-1">
                            <span className="text-sm text-gray-500">Starting from</span>
                            <div className="text-2xl font-bold text-orange-600">
                                {Math.min(...product.prices.map(p => p.price))} DH
                            </div>
                        </div>
                    ) : (
                        <div className="text-2xl font-bold text-orange-600">
                            {product.price} DH
                        </div>
                    )}
                    <button
                        onClick={() => setIsExpanded(!isExpanded)}
                        className="p-2 rounded-2xl bg-orange-50 text-orange-600 hover:bg-orange-100 transition-colors"
                        aria-label={isExpanded ? "Collapse details" : "Expand details"}
                    >
                        {isExpanded ? <ChevronUp className="w-6 h-6" /> : <ChevronDown className="w-6 h-6" />}
                    </button>
                </div>

                <AnimatePresence>
                    {isExpanded && (
                        <motion.div
                            initial={{ height: 0, opacity: 0 }}
                            animate={{ height: "auto", opacity: 1 }}
                            exit={{ height: 0, opacity: 0 }}
                            transition={{ duration: 0.3 }}
                            className="mt-6 space-y-4 overflow-hidden"
                        >
                            {product.prices?.length > 0 && (
                                <div>
                                    <h4 className="font-medium text-orange-700 mb-3">Select Size</h4>
                                    <div className="flex flex-wrap gap-2">
                                        {product.prices.map((price) => (
                                            <button
                                                key={price.size}
                                                onClick={() => setSelectedSize(price.size)}
                                                className={`px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 ${selectedSize === price.size
                                                    ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30'
                                                    : 'bg-orange-50 text-orange-700 hover:bg-orange-100'
                                                    }`}
                                                aria-label={`Select size ${price.size} for ${price.price} DH`}
                                            >
                                                {price.size} - {price.price} DH
                                            </button>
                                        ))}
                                    </div>
                                </div>
                            )}

                            <div className="flex items-center justify-between pt-4 border-t border-orange-100">
                                <div className="flex items-center gap-3">
                                    <button
                                        onClick={() => setQuantity(Math.max(1, quantity - 1))}
                                        className="w-10 h-10 flex items-center justify-center rounded-xl bg-orange-50 text-orange-600 hover:bg-orange-100 transition-colors"
                                        aria-label="Decrease quantity"
                                    >
                                        <Minus className="w-5 h-5" />
                                    </button>
                                    <span className="font-medium text-orange-600 w-8 text-center text-xl">{quantity}</span>
                                    <button
                                        onClick={() => setQuantity(quantity + 1)}
                                        className="w-10 h-10 flex items-center justify-center rounded-xl bg-orange-50 text-orange-600 hover:bg-orange-100 transition-colors"
                                        aria-label="Increase quantity"
                                    >
                                        <Plus className="w-5 h-5" />
                                    </button>
                                </div>

                                <button
                                    onClick={handleAddToCart}
                                    className="px-6 py-3 bg-orange-500 text-white rounded-xl hover:bg-orange-600 transition-colors shadow-lg shadow-orange-500/30 font-medium"
                                    aria-label="Add to cart"
                                >
                                    Add to Cart
                                </button>
                            </div>
                        </motion.div>
                    )}
                </AnimatePresence>
            </div>
        </motion.div>
    );
};

const CategoryFilter = ({ categories, selectedCategory, onSelect }) => {
    return (
        <div className="flex gap-4 overflow-x-auto hide-scrollbar py-4 px-6">
            <button
                onClick={() => onSelect(null)}
                className={`px-6 py-3 rounded-2xl whitespace-nowrap transition-all duration-300 text-base font-medium ${!selectedCategory
                    ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30'
                    : 'bg-white/80 backdrop-blur-sm text-orange-700 hover:bg-orange-50 border border-orange-100'
                    }`}
                aria-label="Show all items"
            >
                All Items
            </button>
            {categories.map((category) => (
                <button
                    key={category.id}
                    onClick={() => onSelect(category.id)}
                    className={`px-6 py-3 rounded-2xl whitespace-nowrap transition-all duration-300 text-base font-medium ${selectedCategory === category.id
                        ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30'
                        : 'bg-white/80 backdrop-blur-sm text-orange-700 hover:bg-orange-50 border border-orange-100'
                        }`}
                    aria-label={`Filter by ${category.name}`}
                >
                    {category.name}
                </button>
            ))}
        </div>
    );
};

const Notification = ({ message }) => (
    <motion.div
        initial={{ opacity: 0, y: 50 }}
        animate={{ opacity: 1, y: 0 }}
        exit={{ opacity: 0, y: 50 }}
        className="fixed bottom-24 right-4 bg-orange-500 text-white px-6 py-3 rounded-2xl shadow-lg shadow-orange-500/30 z-50"
        aria-live="polite"
    >
        {message}
    </motion.div>
);

const Index = ({ products, categories }) => {
    const [selectedCategory, setSelectedCategory] = useState(null);
    const [searchQuery, setSearchQuery] = useState('');
    const [showCart, setShowCart] = useState(false);
    const [showNotification, setShowNotification] = useState(false);

    const { addToCart, getCartCount, getCartTotal } = useCart();

    const filteredProducts = useMemo(() =>
        products.filter(product => {
            const matchesCategory = !selectedCategory || product.category_id === selectedCategory;
            const matchesSearch = !searchQuery ||
                product.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                product.description?.toLowerCase().includes(searchQuery.toLowerCase());
            return matchesCategory && matchesSearch;
        }),
        [products, selectedCategory, searchQuery]
    );

    const handleAddToCart = useCallback((product, quantity, selectedSize) => {
        if (!selectedSize && product.prices?.length > 0) {
            showNotificationMessage('Please select a size');
            return;
        }

        const price = selectedSize
            ? product.prices.find(p => p.size === selectedSize)?.price
            : product.price;

        if (!price) {
            showNotificationMessage('Invalid price configuration');
            return;
        }

        addToCart(product, quantity, selectedSize);
        showNotificationMessage('Added to cart!');
    }, [addToCart]);

    const showNotificationMessage = (message) => {
        setShowNotification({ message, show: true });
        setTimeout(() => setShowNotification(false), 3000);
    };

    return (
        <>
            <Head title="Menu" />
            <div className="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-100">
                {/* Header */}
                <header className="sticky top-0 z-40 bg-orange-500 backdrop-blur-md shadow-lg">
                    <div className="max-w-[2000px] mx-auto px-6 py-4">
                        <div className="w-full flex items-center justify-between gap-6">
                            {/* Logo */}
                            <div className="flex-shrink-0">
                                <h1 className="text-2xl font-bold text-orange-100">RestoPos</h1>
                            </div>

                            {/* Search */}
                            <div className="flex-1 relative max-w-3xl">
                                <input
                                    type="text"
                                    placeholder="Search menu..."
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                    className="w-full px-4 py-2 pl-10 rounded-xl border border-orange-400 focus:ring-2 focus:ring-orange-300 text-gray-800 bg-white/90 backdrop-blur-sm"
                                    aria-label="Search menu"
                                />
                            </div>

                            {/* Cart */}
                            <button
                                className="p-3 rounded-2xl bg-orange-400 text-white relative hover:bg-orange-700 transition-colors shadow-lg shadow-orange-500/30"
                                onClick={() => setShowCart(true)}
                                aria-label="Open cart"
                            >
                                <ShoppingCart className="w-6 h-6" />
                                {getCartCount() > 0 && (
                                    <span className="absolute -top-2 -right-2 bg-white text-orange-500 text-sm font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg">
                                        {getCartCount()}
                                    </span>
                                )}
                            </button>
                        </div>
                    </div>
                </header>

                {/* Categories */}
                <div className="sticky top-[85px] bg-white/50 backdrop-blur-sm z-30 shadow-sm border-y border-orange-100">
                    <div className="max-w-[2000px] mx-auto">
                        <CategoryFilter
                            categories={categories}
                            selectedCategory={selectedCategory}
                            onSelect={setSelectedCategory}
                        />
                    </div>
                </div>

                {/* Products Grid */}
                <main className="max-w-[2000px] mx-auto px-6 py-8">
                    <div className="grid grid-cols-2 lg:grid-cols-4 gap-8">
                        {filteredProducts.map((product) => (
                            <ProductCard
                                key={product.id}
                                product={product}
                                onAddToCart={handleAddToCart}
                            />
                        ))}
                    </div>
                </main>

                {/* Sticky Footer */}
                {getCartCount() > 0 && (
                    <motion.div
                        initial={{ y: 100 }}
                        animate={{ y: 0 }}
                        exit={{ y: 100 }}
                        className="fixed bottom-0 inset-x-0 bg-white/80 backdrop-blur-md border-t border-orange-100 shadow-lg z-30"
                    >
                        <div className="max-w-[2000px] mx-auto px-6 py-4 flex items-center justify-between">
                            <div className="flex items-center gap-6">
                                <div className="flex items-center gap-2">
                                    <ShoppingCart className="w-6 h-6 text-orange-500" />
                                    <span className="text-lg font-medium">{getCartCount()} items</span>
                                </div>
                                <div className="text-2xl font-bold text-orange-500">{getCartTotal()} DH</div>
                            </div>
                            <button
                                onClick={() => setShowCart(true)}
                                className="px-8 py-3 bg-orange-500 text-white rounded-xl hover:bg-orange-600 transition-colors shadow-lg shadow-orange-500/30 font-medium text-lg"
                                aria-label="View cart"
                            >
                                <ShoppingCart className="w-6 h-6" />
                            </button>
                        </div>
                    </motion.div>
                )}

                {/* Cart Drawer */}
                <CartDrawer isOpen={showCart} onClose={() => setShowCart(false)} />

                {/* Notification */}
                <AnimatePresence>
                    {showNotification && (
                        <Notification message={showNotification.message} />
                    )}
                </AnimatePresence>
            </div>
        </>
    );
};

export default Index;