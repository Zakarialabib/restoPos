# TV Display System Setup

The RestoPos TV Display System provides a non-touch, display-only interface perfect for digital menu boards and customer-facing displays.

## Overview

The TV display system is designed for:
- **Digital Menu Boards** - Display menus without customer interaction
- **Kitchen Displays** - Show orders and kitchen status
- **Information Displays** - Show promotions, announcements, and branding
- **Multi-location Support** - Manage displays across multiple restaurant locations

## System Requirements

### Hardware Requirements
- **Display Device**: Any TV or monitor with HDMI input
- **Media Player**: 
  - Raspberry Pi 4 (recommended)
  - Android TV Box
  - Dedicated PC/Mini PC
  - Smart TV with web browser
- **Network**: Stable internet connection (WiFi or Ethernet)
- **Power**: Reliable power supply with surge protection

### Software Requirements
- **Web Browser**: Chrome, Firefox, or Safari (latest versions)
- **Operating System**: Any OS that supports modern web browsers
- **Network Access**: Access to your RestoPos installation

## Quick Setup Guide

### Step 1: Hardware Setup

1. **Connect your display device** to the media player via HDMI
2. **Connect to network** (WiFi or Ethernet)
3. **Power on** both display and media player
4. **Configure display settings** (resolution, orientation)

### Step 2: Access TV Display URL

The TV display system uses a dedicated URL structure:

```
https://your-restopos-domain.com/menu/tv
```

**URL Parameters:**
- `?theme=theme-name` - Select specific theme
- `?location=location-id` - Display specific location menu
- `?mode=fullscreen` - Enable fullscreen mode
- `?refresh=30` - Auto-refresh interval in seconds

**Example URLs:**
```
# Basic TV display
https://your-restopos-domain.com/menu/tv

# With custom theme
https://your-restopos-domain.com/menu/tv?theme=modern-dark

# Specific location with auto-refresh
https://your-restopos-domain.com/menu/tv?location=branch-1&refresh=60
```

### Step 3: Browser Configuration

#### For Chrome/Chromium:
```bash
# Launch in kiosk mode
chrome --kiosk --disable-web-security --disable-features=TranslateUI --no-first-run --fast --fast-start --disable-default-apps "https://your-restopos-domain.com/menu/tv"
```

#### For Firefox:
```bash
# Launch in fullscreen
firefox --kiosk "https://your-restopos-domain.com/menu/tv"
```

### Step 4: Auto-Start Configuration

#### Raspberry Pi (Raspbian):

1. **Install Chromium:**
```bash
sudo apt update
sudo apt install chromium-browser unclutter
```

2. **Create startup script:**
```bash
sudo nano /home/pi/start_tv_display.sh
```

3. **Add script content:**
```bash
#!/bin/bash

# Wait for network
sleep 10

# Hide cursor
unclutter -idle 0.1 &

# Start Chromium in kiosk mode
chromium-browser --kiosk --disable-web-security --disable-features=TranslateUI --no-first-run --fast --fast-start --disable-default-apps "https://your-restopos-domain.com/menu/tv?refresh=60" &
```

4. **Make executable:**
```bash
chmod +x /home/pi/start_tv_display.sh
```

5. **Add to autostart:**
```bash
sudo nano /etc/xdg/lxsession/LXDE-pi/autostart
```

Add line:
```
@/home/pi/start_tv_display.sh
```

## Display Modes

### 1. Menu Display Mode
- **Purpose**: Show current menu items with prices
- **Features**: Category navigation, item images, pricing
- **Best for**: Customer-facing displays, entrance displays

### 2. Kitchen Display Mode
- **Purpose**: Show active orders and kitchen status
- **Features**: Order queue, preparation times, status updates
- **Best for**: Kitchen displays, staff areas

### 3. Promotional Mode
- **Purpose**: Show promotions, specials, and announcements
- **Features**: Rotating banners, special offers, events
- **Best for**: Waiting areas, promotional displays

### 4. Mixed Mode
- **Purpose**: Combine menu items with promotions
- **Features**: Menu sections with promotional overlays
- **Best for**: Main dining area displays

## Theme Configuration

### Available Themes
- `modern-light` - Clean, bright design
- `modern-dark` - Dark theme for better visibility
- `classic` - Traditional menu board style
- `minimal` - Simple, text-focused design
- `colorful` - Vibrant, eye-catching design

### Custom Theme Setup

1. **Access Admin Panel** → Settings → Themes
2. **Select TV Display Themes**
3. **Configure theme options:**
   - Colors and fonts
   - Layout preferences
   - Animation settings
   - Logo and branding

## Network Configuration

### Static IP Setup (Recommended)

1. **Configure static IP** for reliable connection
2. **Set DNS servers** (8.8.8.8, 1.1.1.1)
3. **Test connectivity** to RestoPos server

### Firewall Configuration

**Required ports:**
- Port 80 (HTTP)
- Port 443 (HTTPS)
- Port 6001 (WebSocket for real-time updates)

## Real-time Updates

The TV display system supports real-time updates for:
- **Menu changes** - New items, price updates
- **Availability status** - Out of stock items
- **Promotions** - New offers and specials
- **Kitchen orders** - Order status updates

### WebSocket Configuration

Real-time updates use Laravel Reverb WebSocket server:

```javascript
// Automatic connection to WebSocket
window.Echo.channel('menu-updates')
    .listen('MenuUpdated', (e) => {
        // Auto-refresh display
        location.reload();
    });
```

## Troubleshooting

### Common Issues

#### Display Not Loading
1. **Check network connection**
2. **Verify URL accessibility**
3. **Clear browser cache**
4. **Check firewall settings**

#### Content Not Updating
1. **Verify WebSocket connection**
2. **Check refresh parameter**
3. **Restart browser**
4. **Check server status**

#### Performance Issues
1. **Reduce refresh frequency**
2. **Optimize network connection**
3. **Clear browser data**
4. **Check hardware specifications**

### Log Files

**Browser console logs:**
- Press F12 to open developer tools
- Check Console tab for errors
- Look for network connectivity issues

**System logs:**
```bash
# Raspberry Pi logs
sudo journalctl -u lightdm
tail -f /var/log/Xorg.0.log
```

## Maintenance

### Regular Tasks
- **Weekly**: Check display functionality
- **Monthly**: Update browser and system
- **Quarterly**: Clean hardware and check connections
- **Annually**: Review and update configuration

### Remote Management

**SSH Access (Raspberry Pi):**
```bash
ssh pi@display-ip-address
```

**Remote restart:**
```bash
sudo reboot
```

**Update system:**
```bash
sudo apt update && sudo apt upgrade
```

## Security Considerations

### Network Security
- Use HTTPS for all connections
- Configure firewall rules
- Regular security updates
- Monitor access logs

### Physical Security
- Secure mounting of displays
- Protected power connections
- Restricted physical access to media players

## Advanced Configuration

### Multiple Display Management

**Central configuration file:**
```json
{
  "displays": [
    {
      "id": "main-entrance",
      "url": "https://restopos.com/menu/tv?theme=modern-light",
      "location": "entrance",
      "refresh": 60
    },
    {
      "id": "kitchen-display",
      "url": "https://restopos.com/kitchen/display",
      "location": "kitchen",
      "refresh": 10
    }
  ]
}
```

### Scheduled Content

**Breakfast/Lunch/Dinner menus:**
```javascript
// Auto-switch based on time
const currentHour = new Date().getHours();
let menuType = 'all-day';

if (currentHour < 11) menuType = 'breakfast';
else if (currentHour < 16) menuType = 'lunch';
else menuType = 'dinner';

window.location.href = `/menu/tv?menu=${menuType}`;
```

## Integration Examples

### With Digital Signage Software
- **Screenly OSE** integration
- **Xibo** playlist management
- **Rise Vision** cloud management

### With Restaurant Management
- **POS system** integration
- **Kitchen display** synchronization
- **Inventory management** updates

## Support

For technical support:
- **Documentation**: [TV Display Documentation](../tv-display/)
- **Community**: [RestoPos Community Forum](https://community.restopos.com)
- **Support**: [Contact Support](mailto:support@restopos.com)

---

**Next Steps:**
- [Theme Customization](./themes.md)
- [Content Management](./content.md)
- [Display Controls](./controls.md)