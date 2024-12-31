import React from 'react';
import { usePage } from '@inertiajs/react';
import { NotificationProvider, useNotification } from '@/Context/NotificationContext';

const AppLayout = ({ children }) => {
    const { flash } = usePage().props;
    const { showNotification } = useNotification();

    // Trigger notifications based on flash messages
    React.useEffect(() => {
        if (flash.success) {
            showNotification(flash.success, 'success');
        }
        if (flash.error) {
            showNotification(flash.error, 'error');
        }
    }, [flash, showNotification]);

    return <div>{children}</div>;
};

const App = ({ children }) => (
    <NotificationProvider>
        <AppLayout>{children}</AppLayout>
    </NotificationProvider>
);

export default App;
