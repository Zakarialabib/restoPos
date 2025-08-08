# First Steps

Welcome to RestoPos! This guide will help you get started with your new restaurant point of sale system.

## Initial Setup Wizard

When you first access RestoPos, you'll be greeted with a setup wizard that guides you through the essential configuration steps.

### Step 1: Restaurant Information

Provide basic information about your restaurant:

- **Restaurant Name**: The name that will appear on receipts and reports
- **Address**: Complete address including street, city, state, and postal code
- **Phone Number**: Primary contact number
- **Email**: Business email address
- **Website**: Your restaurant's website (optional)
- **Logo**: Upload your restaurant logo (recommended size: 200x200px)

### Step 2: Currency and Tax Settings

Configure your financial settings:

- **Currency**: Select your local currency (USD, EUR, GBP, etc.)
- **Tax Rate**: Set your default tax/VAT rate
- **Tax Inclusive**: Choose whether prices include tax
- **Decimal Places**: Number of decimal places for currency display

### Step 3: Create Admin Account

Set up your administrator account:

- **Full Name**: Your name
- **Email**: Admin email address
- **Password**: Strong password (minimum 8 characters)
- **Confirm Password**: Confirm your password

## Dashboard Overview

After completing the setup wizard, you'll be taken to the main dashboard.

### Key Dashboard Elements

#### 📊 **Quick Stats**
- Today's sales total
- Number of orders processed
- Average order value
- Active tables/orders

#### 📈 **Sales Chart**
- Real-time sales visualization
- Hourly, daily, or weekly views
- Revenue trends and patterns

#### 🔔 **Notifications**
- New orders
- Low stock alerts
- System notifications
- Payment confirmations

#### ⚡ **Quick Actions**
- Create new order
- Add menu item
- View reports
- Manage tables

## Essential Configuration

### 1. Set Up Your Menu

Before taking orders, you need to configure your menu:

::: tabs

== Categories

1. Navigate to **Menu → Categories**
2. Click **Add Category**
3. Fill in the details:
   - **Name**: Category name (e.g., "Appetizers", "Main Courses")
   - **Description**: Brief description
   - **Sort Order**: Display order
   - **Status**: Active/Inactive
4. Click **Save**

== Menu Items

1. Go to **Menu → Items**
2. Click **Add Item**
3. Complete the form:
   - **Name**: Item name
   - **Description**: Detailed description
   - **Category**: Select category
   - **Price**: Base price
   - **Image**: Upload item photo
   - **Ingredients**: List ingredients
   - **Allergens**: Mark allergens
   - **Availability**: Set availability
4. Click **Save**

== Modifiers

1. Navigate to **Menu → Modifiers**
2. Click **Add Modifier Group**
3. Set up modifier group:
   - **Name**: Group name (e.g., "Size", "Extras")
   - **Type**: Single or Multiple selection
   - **Required**: Whether selection is mandatory
4. Add modifier options:
   - **Option Name**: (e.g., "Large", "Extra Cheese")
   - **Price Adjustment**: Additional cost
5. Assign to menu items

:::

### 2. Configure Tables (Dine-in)

If you offer dine-in service:

1. Go to **Settings → Tables**
2. Click **Add Table**
3. Configure each table:
   - **Table Number**: Unique identifier
   - **Capacity**: Number of seats
   - **Location**: Area/section
   - **QR Code**: Generate for contactless ordering
4. Save and repeat for all tables

### 3. Set Up Payment Methods

Configure accepted payment methods:

1. Navigate to **Settings → Payments**
2. Enable payment methods:
   - **Cash**: Always enabled
   - **Credit/Debit Cards**: Configure card processor
   - **Digital Wallets**: Apple Pay, Google Pay, etc.
   - **Gift Cards**: If applicable
3. Configure payment processor settings
4. Test payment processing

### 4. Staff Management

Add your team members:

1. Go to **Staff → Users**
2. Click **Add User**
3. Fill in user details:
   - **Name**: Full name
   - **Email**: Work email
   - **Role**: Select appropriate role
   - **Permissions**: Set specific permissions
   - **PIN**: For quick login
4. Send invitation email

#### User Roles

- **Admin**: Full system access
- **Manager**: Management functions, reports
- **Cashier**: Order processing, payments
- **Kitchen**: Order viewing, status updates
- **Waiter**: Table management, order taking

## Taking Your First Order

### Dine-in Order

1. **Select Table**: Click on table number or select from dropdown
2. **Add Items**: Browse menu and click items to add
3. **Customize**: Add modifiers, special instructions
4. **Review Order**: Check items, quantities, and total
5. **Submit**: Send order to kitchen
6. **Payment**: Process payment when ready

### Takeaway/Delivery Order

1. **New Order**: Click "New Order" button
2. **Order Type**: Select Takeaway or Delivery
3. **Customer Info**: Add customer details
4. **Add Items**: Select menu items
5. **Special Instructions**: Add any notes
6. **Payment**: Process payment
7. **Confirmation**: Print receipt or send SMS

## Kitchen Display System

Set up the kitchen display for order management:

### Kitchen Setup

1. **Dedicated Device**: Set up tablet/monitor in kitchen
2. **Kitchen View**: Navigate to `/kitchen` URL
3. **Auto-refresh**: Enable automatic order updates
4. **Sound Alerts**: Configure notification sounds

### Order Workflow

1. **New Orders**: Appear in "Pending" column
2. **Accept Order**: Kitchen staff accepts order
3. **In Progress**: Move to "Preparing" status
4. **Ready**: Mark as "Ready for Pickup/Delivery"
5. **Complete**: Order marked as completed

## Reporting and Analytics

### Daily Reports

Access key reports to monitor performance:

1. **Sales Summary**: Daily revenue overview
2. **Item Performance**: Best and worst selling items
3. **Payment Methods**: Breakdown by payment type
4. **Staff Performance**: Individual staff metrics
5. **Customer Analytics**: Customer behavior insights

### Setting Up Automated Reports

1. Go to **Reports → Scheduled Reports**
2. Configure report frequency:
   - **Daily**: End of day summary
   - **Weekly**: Weekly performance
   - **Monthly**: Monthly analytics
3. Set email recipients
4. Choose report format (PDF/Excel)

## Mobile App Setup

### Staff Mobile App

1. **Download**: Install RestoPos staff app
2. **Login**: Use staff credentials
3. **Sync**: Ensure data synchronization
4. **Permissions**: Configure mobile permissions

### Customer App (Optional)

1. **Enable**: Turn on customer app in settings
2. **Customize**: Brand with your logo/colors
3. **Features**: Enable online ordering, loyalty
4. **Publish**: Submit to app stores

## Backup and Security

### Daily Backups

1. **Automatic Backups**: Verify daily backups are running
2. **Cloud Storage**: Ensure backups are stored securely
3. **Test Restore**: Periodically test backup restoration

### Security Best Practices

- **Strong Passwords**: Enforce strong password policy
- **Two-Factor Authentication**: Enable 2FA for admin accounts
- **Regular Updates**: Keep system updated
- **Access Control**: Limit user permissions
- **Network Security**: Secure Wi-Fi and network access

## Getting Help

### Built-in Help

- **Help Center**: Access in-app help documentation
- **Video Tutorials**: Watch step-by-step guides
- **Tooltips**: Hover over elements for quick help

### Support Channels

- **Documentation**: Comprehensive online docs
- **Community Forum**: Connect with other users
- **Discord**: Real-time community support
- **Email Support**: Direct technical support
- **Phone Support**: For urgent issues (premium plans)

### Training Resources

- **Staff Training Videos**: Train your team
- **Best Practices Guide**: Optimize your workflow
- **Webinars**: Regular training sessions
- **Certification Program**: Become a RestoPos expert

## Next Steps

Now that you've completed the initial setup:

1. **Explore Features**: Familiarize yourself with all features
2. **Train Staff**: Ensure all team members are trained
3. **Customize**: Tailor the system to your needs
4. **Monitor Performance**: Use reports to optimize operations
5. **Gather Feedback**: Collect feedback from staff and customers

### Advanced Features to Explore

- **Inventory Management**: Track stock levels
- **Loyalty Programs**: Reward repeat customers
- **Online Ordering**: Enable web-based ordering
- **Delivery Integration**: Connect with delivery services
- **Multi-location**: Manage multiple restaurant locations
- **API Integration**: Connect with third-party services

## Troubleshooting Common Issues

### Order Not Appearing in Kitchen

1. Check internet connection
2. Verify kitchen display is logged in
3. Refresh kitchen display
4. Check order status in admin panel

### Payment Processing Errors

1. Verify payment gateway settings
2. Check internet connectivity
3. Confirm payment method is enabled
4. Test with different payment method

### Slow Performance

1. Clear browser cache
2. Check internet speed
3. Restart application
4. Contact support if issues persist

Congratulations! You're now ready to start using RestoPos to manage your restaurant operations efficiently.