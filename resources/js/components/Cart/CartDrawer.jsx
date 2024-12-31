import React, { useState, useMemo } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { useCart } from '@/Contexts/CartContext';
import { useNotification } from '@/Contexts/NotificationContext'; // Use the NotificationContext
import { X, Plus, Minus, ShoppingBag, Trash2, CheckCircle } from 'lucide-react';
import { router } from '@inertiajs/react';
import ConfirmationDialog from '@/Components/ConfirmationDialog';

const CartDrawer = ({ isOpen, onClose }) => {
    const { cart, removeFromCart, updateQuantity, getCartTotal, clearCart } = useCart();
    const { showNotification } = useNotification(); // Use the notification context
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [showClearCartDialog, setShowClearCartDialog] = useState(false);
    const [orderId, setOrderId] = useState(null); // Track the order ID

    const total = useMemo(() => getCartTotal(), [cart.items]);

    const handleCheckout = () => {
        if (isSubmitting) return;
        if (cart.items.length === 0) {
            showNotification('Your cart is empty', 'error'); // Use showNotification from context
            return;
        }

        setIsSubmitting(true);

        router.post('/checkout/order', {
            items: cart.items.map(item => ({
                id: parseInt(item.id),
                quantity: parseInt(item.quantity),
                size: item.size || null,
                price: parseFloat(item.price)
            })),
            total: parseFloat(total),
            payment_method: 'cash',
            order_type: 'in-store'
        }, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                clearCart(); 
                showNotification('Order placed successfully!', 'success');
                setIsSubmitting(false);
            },
            onError: (errors) => {
                console.error('Order error:', errors);
                setIsSubmitting(false);
                showNotification('Failed to place order. Please try again.', 'error');
            }
        });
    };

    const handleClearCart = () => {
        setShowClearCartDialog(true);
    };

    const confirmClearCart = () => {
        clearCart();
        setShowClearCartDialog(false);
        showNotification('Cart cleared successfully', 'success'); // Use showNotification from context
    };

    return (
        <AnimatePresence>
            {isOpen && (
                <>
                    {/* Backdrop */}
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        onClick={onClose}
                        className="fixed inset-0 bg-black bg-opacity-50 z-40"
                        role="presentation"
                        aria-label="Close cart drawer"
                    />

                    {/* Cart Drawer */}
                    <motion.div
                        initial={{ x: '100%' }}
                        animate={{ x: 0 }}
                        exit={{ x: '100%' }}
                        transition={{ type: 'spring', damping: 20 }}
                        className="fixed right-0 top-0 h-full w-full max-w-md bg-white shadow-xl z-50 flex flex-col"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="cart-drawer-title"
                    >
                        {/* Header */}
                        <div className="p-4 border-b flex justify-between items-center">
                            <div className="flex items-center gap-2">
                                <ShoppingBag className="w-6 h-6 text-orange-500" aria-hidden="true" />
                                <h2 id="cart-drawer-title" className="text-xl font-semibold">Your Cart</h2>
                                <span className="text-sm text-gray-500">
                                    ({cart.items.length} items)
                                </span>
                            </div>
                            <button
                                onClick={onClose}
                                className="p-2 hover:bg-gray-100 rounded-full"
                                aria-label="Close cart drawer"
                            >
                                <X className="w-6 h-6" aria-hidden="true" />
                            </button>
                        </div>

                        {/* Cart Items or Order Confirmation */}
                        <div className="flex-1 overflow-y-auto">
                            {orderId ? (
                                <div className="p-4 text-center">
                                    <CheckCircle className="w-16 h-16 mx-auto text-green-500 mb-4" aria-hidden="true" />
                                    <h3 className="text-xl font-semibold mb-2">Order Placed Successfully!</h3>
                                    <p className="text-gray-600 mb-4">
                                        Your order #<span className="font-semibold">{orderId}</span> has been placed.
                                    </p>
                                    <p className="text-gray-600">Total: <span className="font-semibold">{total.toFixed(2)} DH</span></p>
                                </div>
                            ) : cart.items.length === 0 ? (
                                <div className="text-center py-8">
                                    <ShoppingBag className="w-16 h-16 mx-auto text-gray-400 mb-4" aria-hidden="true" />
                                    <p className="text-gray-500">Your cart is empty</p>
                                </div>
                            ) : (
                                <div className="p-4 space-y-4">
                                    {cart.items.map((item) => (
                                        <motion.div
                                            key={`${item.id}-${item.size}`}
                                            layout
                                            initial={{ opacity: 0, y: 20 }}
                                            animate={{ opacity: 1, y: 0 }}
                                            exit={{ opacity: 0, y: -20 }}
                                            className="flex gap-4 bg-white rounded-lg p-4 shadow-sm border border-gray-100"
                                        >
                                            {item.image && (
                                                <img
                                                    src={item.image}
                                                    alt={item.name}
                                                    className="w-20 h-20 object-cover rounded-lg"
                                                    aria-hidden="true"
                                                />
                                            )}
                                            <div className="flex-1">
                                                <div className="flex justify-between">
                                                    <div>
                                                        <h3 className="font-semibold">{item.name}</h3>
                                                        {item.size && (
                                                            <p className="text-sm text-gray-500">
                                                                Size: {item.size}
                                                            </p>
                                                        )}
                                                    </div>
                                                    <button
                                                        onClick={() => removeFromCart(item.id, item.size)}
                                                        className="text-gray-400 hover:text-red-500 transition-colors"
                                                        aria-label={`Remove ${item.name} from cart`}
                                                    >
                                                        <Trash2 className="w-5 h-5" aria-hidden="true" />
                                                    </button>
                                                </div>
                                                <div className="mt-2 flex justify-between items-center">
                                                    <div className="flex items-center gap-2">
                                                        <button
                                                            onClick={() =>
                                                                updateQuantity(
                                                                    item.id,
                                                                    item.size,
                                                                    Math.max(1, item.quantity - 1)
                                                                )
                                                            }
                                                            disabled={item.quantity === 1}
                                                            className="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                            aria-label={`Decrease quantity of ${item.name}`}
                                                        >
                                                            <Minus className="w-4 h-4" aria-hidden="true" />
                                                        </button>
                                                        <span className="w-8 text-center">{item.quantity}</span>
                                                        <button
                                                            onClick={() =>
                                                                updateQuantity(
                                                                    item.id,
                                                                    item.size,
                                                                    item.quantity + 1
                                                                )
                                                            }
                                                            className="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors"
                                                            aria-label={`Increase quantity of ${item.name}`}
                                                        >
                                                            <Plus className="w-4 h-4" aria-hidden="true" />
                                                        </button>
                                                    </div>
                                                    <p className="font-semibold text-orange-500">
                                                        {(parseFloat(item.price) * item.quantity).toFixed(2)} DH
                                                    </p>
                                                </div>
                                            </div>
                                        </motion.div>
                                    ))}
                                </div>
                            )}
                        </div>

                        {/* Footer */}
                        {!orderId && cart.items.length > 0 && (
                            <div className="border-t p-4 bg-white">
                                <div className="mb-4 space-y-2">
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500">Subtotal</span>
                                        <span>{total.toFixed(2)} DH</span>
                                    </div>
                                    <div className="flex justify-between font-semibold text-lg pt-2 border-t">
                                        <span>Total</span>
                                        <span>{total.toFixed(2)} DH</span>
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <button
                                        onClick={handleCheckout}
                                        disabled={isSubmitting}
                                        className={`w-full py-3 bg-orange-500 text-white rounded-lg font-semibold 
                                            ${isSubmitting ? 'opacity-75 cursor-not-allowed' : 'hover:bg-orange-600'} 
                                            transition-colors`}
                                        aria-label="Place order"
                                    >
                                        {isSubmitting ? (
                                            <span className="flex items-center justify-center gap-2">
                                                <svg className="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
                                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                </svg>
                                                Processing...
                                            </span>
                                        ) : (
                                            <span className="flex items-center justify-center gap-2">
                                                Place Order
                                                <span className="text-sm opacity-90">({total.toFixed(2)} DH)</span>
                                            </span>
                                        )}
                                    </button>
                                    <button
                                        onClick={handleClearCart}
                                        className="w-full py-3 text-red-500 hover:bg-red-50 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2"
                                        aria-label="Clear cart"
                                    >
                                        <Trash2 className="w-5 h-5" aria-hidden="true" />
                                        Clear Cart
                                    </button>
                                </div>
                            </div>
                        )}
                    </motion.div>

                    {/* Clear Cart Confirmation Dialog */}
                    <ConfirmationDialog
                        isOpen={showClearCartDialog}
                        onClose={() => setShowClearCartDialog(false)}
                        onConfirm={confirmClearCart}
                        title="Clear Cart"
                        message="Are you sure you want to clear your cart?"
                        confirmText="Clear"
                        cancelText="Cancel"
                    />
                </>
            )}
        </AnimatePresence>
    );
};

export default CartDrawer;