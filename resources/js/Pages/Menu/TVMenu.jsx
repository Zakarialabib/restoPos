import React, { useState, useEffect } from "react";
import { motion } from "framer-motion";
import { Head } from "@inertiajs/react";
import AutoSlide from "@/Components/AutoSlide";
import Grid from "@/Components/Grid";
import ProductCard from "@/Components/ProductCard";
import { cn } from "@/lib/utils";
import {
    LayoutGrid,
    GalleryHorizontal,
    List,
    Settings,
    Maximize2,
    Minimize2
} from "lucide-react";

// Template Modes
const VIEW_MODES = {
    GRID: "grid",
    CAROUSEL: "carousel",
    LIST: "list",
};

// Theme Variants (from your existing ProductCard)
const THEMES = {
    default: {
        name: "Default",
        background: "from-gray-100 to-gray-200",
        variant: "default"
    },
    rustic: {
        name: "Rustic",
        background: "from-green-900 to-green-950",
        variant: "rustic"
    },
    elegant: {
        name: "Elegant",
        background: "from-gray-900 to-gray-950",
        variant: "elegant"
    },
    spicy: {
        name: "Spicy",
        background: "from-red-900 to-orange-900",
        variant: "spicy"
    },
    showcase: {
        name: "Showcase",
        background: "from-gray-900 to-gray-950",
        variant: "showcase"
    },
    vibrant: {
        name: "Vibrant",
        background: "from-orange-200 to-orange-100",
        variant: "vibrant"
    },
    soothing: {
        name: "Soothing",
        background: "from-teal-100 to-teal-50",
        variant: "soothing"
    },
    modern: {
        name: "Modern",
        background: "from-gray-100 to-gray-50",
        variant: "modern"
    },
    bold: {
        name: "Bold",
        background: "from-red-100 via-pink-100 to-white",
        variant: "bold"
    },
};

const TVMenu = ({ products = [] }) => {
    // State Management
    const [viewMode, setViewMode] = useState(VIEW_MODES.GRID);
    const [slideInterval, setSlideInterval] = useState(5000);
    const [transitionType, setTransitionType] = useState("slide");
    const [isPaused, setIsPaused] = useState(false);
    const [currentProducts, setCurrentProducts] = useState([]);
    const [currentTheme, setCurrentTheme] = useState("default");
    const [showSettings, setShowSettings] = useState(false);
    const [focusedIndex, setFocusedIndex] = useState(0);

    // New states for full-screen and orientation
    const [isFullScreen, setIsFullScreen] = useState(false);
    const [screenOrientation, setScreenOrientation] = useState('landscape');

    // Initialize currentProducts when products prop changes
    useEffect(() => {
        if (Array.isArray(products) && products.length > 0) {
            setCurrentProducts(products.slice(0, 3));
        }
    }, [products]);

    // Full-screen toggle
    const toggleFullScreen = () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            setIsFullScreen(true);
        } else {
            document.exitFullscreen();
            setIsFullScreen(false);
        }
    };

    // Orientation detection
    useEffect(() => {
        const checkOrientation = () => {
            setScreenOrientation(
                window.innerWidth > window.innerHeight ? 'landscape' : 'portrait'
            );
        };

        // Check initial orientation
        checkOrientation();

        // Add event listener for orientation changes
        window.addEventListener('resize', checkOrientation);

        // Cleanup listener
        return () => {
            window.removeEventListener('resize', checkOrientation);
        };
    }, []);

    // View Mode Handlers
    const handleViewModeChange = (mode) => {
        setViewMode(mode);
        // Reset products for different views
        if (mode === VIEW_MODES.GRID && Array.isArray(products)) {
            setCurrentProducts(products.slice(0, 3));
        }
    };

    // Auto-slide effect for grid mode
    useEffect(() => {
        if (viewMode !== VIEW_MODES.GRID || isPaused) return;

        const timer = setInterval(() => {
            handleSwipe("right");
            setFocusedIndex((prev) => (prev + 1) % 3);
        }, slideInterval);

        return () => clearInterval(timer);
    }, [viewMode, isPaused, slideInterval, currentProducts]);

    // Swipe Handler (for grid/carousel modes)
    const handleSwipe = (direction) => {
        if (viewMode !== VIEW_MODES.GRID || !Array.isArray(products) || products.length === 0) return;

        let newIndex;
        const currentIndex = products.findIndex(p => p.id === currentProducts[0]?.id) || 0;

        if (direction === "right") {
            newIndex = (currentIndex + 3) % products.length;
        } else if (direction === "left") {
            newIndex = (currentIndex - 3 + products.length) % products.length;
        }

        setCurrentProducts(products.slice(newIndex, Math.min(newIndex + 3, products.length)));
    };

    // Keyboard Controls
    useEffect(() => {
        const handleKeyPress = (e) => {
            switch (e.key) {
                case " ":
                    setIsPaused((prev) => !prev);
                    break;
                case "v":
                    handleViewModeChange(
                        viewMode === VIEW_MODES.GRID
                            ? VIEW_MODES.CAROUSEL
                            : VIEW_MODES.GRID
                    );
                    break;
                case 's':
                    setSlideInterval((prev) => {
                        const intervals = [3000, 5000, 7000];
                        const currentIndex = intervals.indexOf(prev);
                        return intervals[(currentIndex + 1) % intervals.length];
                    });
                    break;
                case "ArrowRight":
                    if (viewMode === "grid") handleSwipe("right");
                    break;
                case "ArrowLeft":
                    if (viewMode === "grid") handleSwipe("left");
                    break;
                default:
                    break;
                case "t":
                    const themeKeys = Object.keys(THEMES);
                    const currentIndex = themeKeys.indexOf(currentTheme);
                    const nextTheme = themeKeys[(currentIndex + 1) % themeKeys.length];
                    setCurrentTheme(nextTheme);
                    break;
                case "n":
                    setTransitionType((prev) =>
                        prev === "slide" ? "fade" : "slide"
                    );
                    break;
                case "f":
                    toggleFullScreen();
                    break;
                case "Escape":
                    setShowSettings(false);
                    break;
            }
        };

        window.addEventListener('keydown', handleKeyPress);
        return () => window.removeEventListener('keydown', handleKeyPress);
    }, [viewMode, currentTheme]);

    // Render Helpers
    const renderGridView = () => (
        <Grid
            items={currentProducts.map((product) => (
                <motion.div
                    key={product.id}
                    initial={{ opacity: 0, scale: 0.9 }}
                    animate={{ opacity: 1, scale: 1 }}
                    exit={{ opacity: 0, scale: 0.9 }}
                    transition={{ duration: 0.5 }}
                >
                    <ProductCard
                        product={product}
                        isGrid={true}
                        variant={THEMES[currentTheme].variant}
                    />
                </motion.div>
            ))}
            className="h-full max-w-full mx-auto"
            breakpointCols={{
                default: 3,
                1920: 3,
                1536: 3,
                1280: 3,
                1024: 2,
                640: 1,
            }}
        />
    );

    const renderCarouselView = () => (
        <AutoSlide
            items={products.map((product) => (
                <motion.div
                    key={product.id}
                    className="w-full h-full flex items-center justify-center"
                    initial={{ opacity: 0, scale: 0.95 }}
                    animate={{ opacity: 1, scale: 1 }}
                    exit={{ opacity: 0, scale: 0.95 }}
                    transition={{ duration: 0.5 }}
                >
                    <ProductCard
                        product={product}
                        isGrid={false}
                        variant={THEMES[currentTheme].variant}
                    />
                </motion.div>
            ))}
            interval={isPaused ? null : slideInterval}
            transitionType={transitionType}
            className="h-full max-w-full mx-auto"
            pauseOnHover={false}
        />
    );

    const renderListView = () => {
        // Get all unique sizes once
        const allSizes = [...new Set(products.flatMap(product =>
            product.prices ? product.prices.map(price => price.size) : []
        ))];

        return (
            <div className="w-full h-full p-6">
                {/* Header */}
                <div className={`
                    w-full mb-4 p-3 rounded-lg
                    ${THEMES[currentTheme].accentColor}
                    text-white text-xl font-bold
                `}>
                    Menu Items
                </div>

                {/* Products Grid */}
                <div className="w-full grid grid-cols-6 auto-rows-auto gap-4">
                    {products.map((product) => (
                        <motion.div
                            key={product.id}
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            transition={{ duration: 0.3 }}
                            className="bg-white/90 backdrop-blur-sm rounded-lg p-4 flex flex-col justify-between"
                        >
                            {/* Product Name and Description */}
                            <div>
                                <h3 className="font-bold text-2xl text-gray-800 mb-2">
                                    {product.name}
                                </h3>
                                <p className="text-gray-600 text-lg line-clamp-2">
                                    {product.description}
                                </p>
                            </div>

                            {/* Prices */}
                            <div className="mt-3 flex flex-wrap gap-2">
                                {product.prices ? (
                                    product.prices.map((price) => (
                                        <div
                                            key={`${product.id}-${price.size}`}
                                            className="flex items-center gap-2"
                                        >
                                            <span className="text-gray-500 text-lg">
                                                {price.size}:
                                            </span>
                                            <span className="font-bold text-xl text-gray-800">
                                                {price.price} DH
                                            </span>
                                        </div>
                                    ))
                                ) : (
                                    <span className="font-bold text-xl text-gray-800">
                                        {product.price} DH
                                    </span>
                                )}
                            </div>
                        </motion.div>
                    ))}
                </div>
            </div>
        );
    };


    // View Mode Renderer
    const renderView = () => {
        switch (viewMode) {
            case VIEW_MODES.CAROUSEL:
                return renderCarouselView();
            case VIEW_MODES.LIST:
                return renderListView();
            case VIEW_MODES.GRID:
            default:
                return renderGridView();
        }
    };

    return (
        <div className={cn(
            "min-h-screen min-w-screen relative overflow-hidden",
            "bg-gradient-to-br transition-colors duration-700",
            THEMES[currentTheme].background
        )}>
            <Head title="Digital Menu" />
            {/* Restaurant Logo/Branding */}
            <div className="absolute top-6 left-6 z-10">
                <h1 className="text-2xl font-bold text-white">RestoPos</h1>
            </div>
            {/* Settings Overlay */}
            {showSettings && (
                <motion.div
                    className="fixed inset-0 bg-black/50 z-40 flex items-center justify-center"
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    exit={{ opacity: 0 }}
                >
                    <motion.div
                        className="bg-white p-6 rounded-lg shadow-xl"
                        initial={{ scale: 0.9, opacity: 0 }}
                        animate={{ scale: 1, opacity: 1 }}
                        exit={{ scale: 0.9, opacity: 0 }}
                    >
                        <h2 className="text-xl font-bold mb-4">Menu Display Settings</h2>
                        <div className="space-y-4">
                            <div>
                                <label className="block mb-2">View Mode</label>
                                <div className="flex gap-2">
                                    {Object.values(VIEW_MODES).map((mode) => (
                                        <button
                                            key={mode}
                                            onClick={() => handleViewModeChange(mode)}
                                            className={cn(
                                                "p-2 rounded-md flex items-center gap-2",
                                                viewMode === mode
                                                    ? "bg-orange-500 text-white"
                                                    : "bg-gray-200 text-gray-700"
                                            )}
                                        >
                                            {mode === VIEW_MODES.GRID && <LayoutGrid className="w-5 h-5" />}
                                            {mode === VIEW_MODES.CAROUSEL && <GalleryHorizontal className="w-5 h-5" />}
                                            {mode === VIEW_MODES.LIST && <List className="w-5 h-5" />}
                                            {mode.charAt(0).toUpperCase() + mode.slice(1)}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            <div className="flex items-center gap-4">
                                <select
                                    value={slideInterval}
                                    onChange={(e) => setSlideInterval(Number(e.target.value))}
                                    className={cn(
                                        "p-2 rounded-md flex items-center gap-2 bg-orange-500 text-white"
                                    )}
                                >
                                    <option value={3000}>3s</option>
                                    <option value={5000}>5s</option>
                                    <option value={7000}>7s</option>
                                    <option value={10000}>10s</option>
                                </select>

                                <select
                                    value={transitionType}
                                    onChange={(e) => setTransitionType(e.target.value)}
                                    className={cn(
                                        "p-2 rounded-md flex items-center gap-2 bg-orange-500 text-white"
                                    )}
                                >
                                    <option value="slide">Slide</option>
                                    <option value="fade">Fade</option>
                                </select>

                                <button
                                    onClick={() => setIsPaused((prev) => !prev)}
                                    className="p-2 rounded-md flex items-center gap-2 bg-orange-500 text-white"
                                >
                                    {isPaused ? "Resume" : "Pause"}
                                </button>
                            </div>

                            <div>
                                <label className="block mb-2">Theme</label>
                                <select
                                    value={currentTheme}
                                    onChange={(e) => setCurrentTheme(e.target.value)}
                                    className="w-full p-2 border rounded-md"
                                >
                                    {Object.keys(THEMES).map((theme) => (
                                        <option key={theme} value={theme}>
                                            {THEMES[theme].name}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            <div>
                                <label className="block mb-2">Screen Settings</label>
                                <div className="flex items-center gap-4">
                                    <button
                                        onClick={toggleFullScreen}
                                        className={cn(
                                            "p-2 rounded-md flex items-center gap-2",
                                            "bg-orange-500 text-white"
                                        )}
                                    >
                                        {isFullScreen ? <Minimize2 className="w-5 h-5" /> : <Maximize2 className="w-5 h-5" />}
                                        {isFullScreen ? 'Exit Full Screen' : 'Full Screen'}
                                    </button>

                                    <div className="text-gray-700 flex items-center gap-2">
                                        <span>Orientation:</span>
                                        <span className="font-bold">
                                            {screenOrientation.charAt(0).toUpperCase() + screenOrientation.slice(1)}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button
                            onClick={() => setShowSettings(false)}
                            className="mt-4 w-full bg-orange-500 text-white p-2 rounded-md"
                        >
                            Close Settings
                        </button>
                    </motion.div>
                </motion.div>
            )
            }

            {/* Settings Button */}
            <button
                onClick={() => setShowSettings(true)}
                className="fixed top-4 right-4 z-50 bg-orange-500 text-white p-2 rounded-full shadow-lg hover:bg-orange-600 transition-colors"
            >
                <Settings className="w-6 h-6" />
            </button>

            {/* Main Content */}
            <div
                className={cn(
                    "h-screen mx-auto px-8",
                    "flex items-center justify-center transition-all duration-500",
                    viewMode === VIEW_MODES.GRID ? "py-2" : "py-6"
                )}
            >
                {renderView()}
            </div>
        </div >
    );
};

export default TVMenu;