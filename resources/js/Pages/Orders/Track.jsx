import React from 'react';
import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { 
  ShoppingBag, 
  CheckCircle2, 
  ChefHat, 
  Bike, 
  MapPin 
} from 'lucide-react';

const OrderStatus = {
  PENDING: 'pending',
  CONFIRMED: 'confirmed',
  PREPARING: 'preparing',
  ON_THE_WAY: 'on_the_way',
  DELIVERED: 'delivered'
};

const statusSteps = [
  { 
    status: OrderStatus.CONFIRMED, 
    label: 'Order Confirmed',
    icon: CheckCircle2,
    description: 'We have received your order'
  },
  { 
    status: OrderStatus.PREPARING, 
    label: 'Preparing',
    icon: ChefHat,
    description: 'Your food is being prepared'
  },
  { 
    status: OrderStatus.ON_THE_WAY, 
    label: 'On the Way',
    icon: Bike,
    description: 'Your order is on its way'
  },
  { 
    status: OrderStatus.DELIVERED, 
    label: 'Delivered',
    icon: MapPin,
    description: 'Enjoy your meal!'
  }
];

const Track = ({ order }) => {
  const currentStepIndex = statusSteps.findIndex(step => step.status === order.status);

  return (
    <>
      <Head title={`Track Order #${order.id}`} />
      <div className="min-h-screen bg-gray-50 py-12">
        <div className="container mx-auto px-4 max-w-3xl">
          {/* Order Header */}
          <div className="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div className="flex items-center justify-between mb-4">
              <div className="flex items-center gap-3">
                <ShoppingBag className="w-6 h-6 text-orange-500" />
                <h1 className="text-2xl font-bold">Order #{order.id}</h1>
              </div>
              <span className="px-4 py-2 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">
                {order.status.replace('_', ' ').toUpperCase()}
              </span>
            </div>
            <div className="grid grid-cols-2 gap-4 text-sm">
              <div>
                <p className="text-gray-500">Delivery Time</p>
                <p className="font-medium">{order.delivery_time}</p>
              </div>
              <div>
                <p className="text-gray-500">Total Amount</p>
                <p className="font-medium">${order.total_amount}</p>
              </div>
              <div>
                <p className="text-gray-500">Delivery Address</p>
                <p className="font-medium">{order.delivery_address}</p>
              </div>
              <div>
                <p className="text-gray-500">Contact Number</p>
                <p className="font-medium">{order.contact_number}</p>
              </div>
            </div>
          </div>

          {/* Order Progress */}
          <div className="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 className="text-xl font-semibold mb-6">Order Progress</h2>
            <div className="space-y-8">
              {statusSteps.map((step, index) => {
                const isCompleted = index <= currentStepIndex;
                const isCurrent = index === currentStepIndex;

                return (
                  <div key={step.status} className="relative">
                    {index !== statusSteps.length - 1 && (
                      <div 
                        className={`absolute left-6 top-10 w-0.5 h-16 ${
                          isCompleted ? 'bg-orange-500' : 'bg-gray-200'
                        }`} 
                      />
                    )}
                    <div className="flex items-start gap-4">
                      <motion.div
                        initial={false}
                        animate={{
                          scale: isCurrent ? 1.2 : 1,
                          backgroundColor: isCompleted ? '#f97316' : '#e5e7eb'
                        }}
                        className={`w-12 h-12 rounded-full flex items-center justify-center ${
                          isCompleted ? 'bg-orange-500' : 'bg-gray-200'
                        }`}
                      >
                        <step.icon className={`w-6 h-6 ${
                          isCompleted ? 'text-white' : 'text-gray-400'
                        }`} />
                      </motion.div>
                      <div>
                        <h3 className={`font-semibold ${
                          isCompleted ? 'text-orange-500' : 'text-gray-400'
                        }`}>
                          {step.label}
                        </h3>
                        <p className="text-gray-500">{step.description}</p>
                        {isCurrent && (
                          <p className="text-sm text-orange-500 mt-1">
                            Current Status
                          </p>
                        )}
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>

          {/* Order Items */}
          <div className="bg-white rounded-xl shadow-lg p-6">
            <h2 className="text-xl font-semibold mb-6">Order Items</h2>
            <div className="space-y-4">
              {order.items.map((item) => (
                <div 
                  key={item.id} 
                  className="flex items-center gap-4 p-4 bg-gray-50 rounded-lg"
                >
                  {item.product.image && (
                    <img
                      src={item.product.image}
                      alt={item.product.name}
                      className="w-16 h-16 object-cover rounded-lg"
                    />
                  )}
                  <div className="flex-1">
                    <h3 className="font-medium">{item.product.name}</h3>
                    {item.size && (
                      <p className="text-sm text-gray-500">Size: {item.size}</p>
                    )}
                  </div>
                  <div className="text-right">
                    <p className="font-medium">${item.subtotal}</p>
                    <p className="text-sm text-gray-500">
                      {item.quantity} × ${item.unit_price}
                    </p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default Track; 