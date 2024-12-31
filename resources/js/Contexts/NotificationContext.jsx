import React, { createContext, useContext, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { X, CheckCircle, AlertCircle, Info } from 'lucide-react';

const NotificationContext = createContext();

export function NotificationProvider({ children }) {
    const [notifications, setNotifications] = useState([]);

    const showNotification = (message, type = 'success') => {
        const id = Date.now();
        setNotifications((prev) => [...prev, { id, message, type }]);
        setTimeout(() => {
            setNotifications((prev) => prev.filter((notification) => notification.id !== id));
        }, 3000);
    };

    const Notification = ({ type = 'success', message, onClose }) => {
        const types = {
            success: {
                icon: CheckCircle,
                bg: 'bg-green-50',
                border: 'border-green-200',
                text: 'text-green-800',
                iconColor: 'text-green-400',
            },
            error: {
                icon: AlertCircle,
                bg: 'bg-red-50',
                border: 'border-red-200',
                text: 'text-red-800',
                iconColor: 'text-red-400',
            },
            info: {
                icon: Info,
                bg: 'bg-blue-50',
                border: 'border-blue-200',
                text: 'text-blue-800',
                iconColor: 'text-blue-400',
            },
        };

        const style = types[type];
        const Icon = style.icon;

        return (
            <motion.div
                initial={{ opacity: 0, y: -50, scale: 0.9 }}
                animate={{ opacity: 1, y: 0, scale: 1 }}
                exit={{ opacity: 0, y: -50, scale: 0.9 }}
                transition={{ duration: 0.3, ease: 'easeInOut' }}
                className={`fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 ${style.bg} ${style.border} ${style.text} border rounded-lg shadow-xl z-[1000] max-w-md w-full`}
            >
                <div className="p-4">
                    <div className="flex items-start">
                        <div className="flex-shrink-0">
                            <Icon className={`h-6 w-6 ${style.iconColor}`} />
                        </div>
                        <div className="ml-3 flex-1">
                            <p className="text-sm font-medium">{message}</p>
                        </div>
                        <div className="ml-4 flex-shrink-0">
                            <button
                                type="button"
                                onClick={onClose}
                                className={`inline-flex rounded-md focus:outline-none ${style.text} hover:opacity-75`}
                            >
                                <X className="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </motion.div>
        );
    };

    return (
        <NotificationContext.Provider value={{ showNotification }}>
            {children}
            <AnimatePresence>
                {notifications.map((notification) => (
                    <Notification
                        key={notification.id}
                        type={notification.type}
                        message={notification.message}
                        onClose={() =>
                            setNotifications((prev) =>
                                prev.filter((n) => n.id !== notification.id)
                            )
                        }
                    />
                ))}
            </AnimatePresence>
        </NotificationContext.Provider>
    );
}

export function useNotification() {
    const context = useContext(NotificationContext);
    if (!context) {
        throw new Error('useNotification must be used within a NotificationProvider');
    }
    return context;
}