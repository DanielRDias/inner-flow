# Inner Flow WordPress Website

A bilingual WordPress website for managing hiking events with Google Maps integration and user registration.

## Overview

Inner Flow is a custom WordPress theme and plugin designed for organizing hiking events with the following features:

- **Bilingual Support**: Portuguese and English
- **Color Scheme**: Blue and green gradient theme
- **Event Management**: Create hiking events with detailed information
- **Google Maps Integration**: Display routes and stop points on interactive maps
- **User Registration**: Users can register via Google and sign up for events
- **Stop Points**: Define multiple stop points with times and descriptions
- **Participant Management**: View who's joining and at which stop point

## Installation

### Prerequisites

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Google Maps API key
- Google OAuth credentials (for social login)

### Step 1: Install WordPress

1. Download WordPress from [wordpress.org](https://wordpress.org/)
2. Install WordPress in the `c:\Users\Danie\projects\inner-flow` directory
3. Complete the WordPress installation wizard

### Step 2: Install the Theme

1. Copy the `wp-content/themes/inner-flow` folder to your WordPress themes directory
2. Go to **Appearance > Themes** in WordPress admin
3. Activate the "Inner Flow" theme

### Step 3: Install the Plugin

1. Copy the `wp-content/plugins/inner-flow-events` folder to your WordPress plugins directory
2. Go to **Plugins** in WordPress admin
3. Activate the "Inner Flow Hiking Events" plugin

### Step 4: Configure Google Maps API

1. Get a Google Maps API key from [Google Cloud Console](https://console.cloud.google.com/)
2. Enable the following APIs:
   - Maps JavaScript API
   - Places API
   - Geocoding API
3. Edit `wp-content/themes/inner-flow/functions.php`
4. Replace `YOUR_API_KEY` with your actual Google Maps API key

### Step 5: Install Language Support Plugin

Install one of these multilingual plugins:

- **Polylang** (Recommended - Free)
  1. Go to **Plugins > Add New**
  2. Search for "Polylang"
  3. Install and activate
  4. Go to **Languages** and add Portuguese (pt_BR) and English (en_US)

- **WPML** (Premium alternative)

### Step 6: Install Google Login Plugin

Install a Google OAuth plugin for social login:

- **Nextend Social Login** (Recommended - Free)
  1. Go to **Plugins > Add New**
  2. Search for "Nextend Social Login"
  3. Install and activate
  4. Configure Google OAuth in the plugin settings
  
OR

- **WP Social Login** (Alternative)

## Configuration

### Set Up Languages

1. Go to **Languages** in WordPress admin
2. Add Portuguese (Português) and English
3. Set your default language
4. Translate strings using the .po files in `wp-content/themes/inner-flow/languages/`

### Configure Site Settings

1. Go to **Settings > General**
2. Set site title to "Inner Flow"
3. Set tagline/description
4. Configure timezone

### Create Menu

1. Go to **Appearance > Menus**
2. Create a new menu named "Primary Menu"
3. Add pages:
   - Home
   - Hiking Events (link to `/hiking_event/`)
   - About
   - Contact
4. Assign to "Primary Menu" location

## Usage

### Creating a Hiking Event

1. Go to **Hiking Events > Add New** in WordPress admin
2. Enter event title and description
3. Fill in **Event Details**:
   - Event Date
   - Event Time
   - Event Location (use autocomplete)
   - Map will auto-populate
4. Configure **Route & Stop Points**:
   - Enter route name
   - Select difficulty level
   - Add stop points with:
     - Name
     - Description
     - Time
     - Duration
     - Coordinates (can be set via map)
5. Publish the event

### User Registration Flow

1. Users visit an event page
2. Click "Login with Google" if not logged in
3. After login, select registration options:
   - Will join or won't join
   - Select which stop point to join at (optional)
4. Click "Register for Event"
5. View all participants in the participants section

## File Structure

```
inner-flow/
├── wp-content/
│   ├── themes/
│   │   └── inner-flow/
│   │       ├── style.css
│   │       ├── functions.php
│   │       ├── index.php
│   │       ├── header.php
│   │       ├── footer.php
│   │       ├── single-hiking_event.php
│   │       ├── archive-hiking_event.php
│   │       ├── languages/
│   │       │   ├── pt_BR.po
│   │       │   └── en_US.po
│   │       └── js/
│   │           └── main.js
│   └── plugins/
│       └── inner-flow-events/
│           ├── inner-flow-events.php
│           ├── includes/
│           │   ├── class-post-types.php
│           │   ├── class-meta-boxes.php
│           │   ├── class-ajax-handlers.php
│           │   ├── class-google-auth.php
│           │   └── class-database.php
│           └── assets/
│               ├── css/
│               │   ├── styles.css
│               │   └── admin-styles.css
│               └── js/
│                   ├── scripts.js
│                   └── admin-scripts.js
```

## Database Schema

The plugin creates three custom tables:

### ife_event_routes
Stores route information for each event.

### ife_stop_points
Stores individual stop points along routes.

### ife_event_registrations
Stores user registrations for events.

## Customization

### Colors

To change the color scheme, edit `wp-content/themes/inner-flow/style.css`:

```css
:root {
    --primary-blue: #2C5F8D;
    --secondary-blue: #4A90C9;
    --primary-green: #3A7D44;
    --secondary-green: #5FA869;
}
```

### Translations

Edit the .po files in `wp-content/themes/inner-flow/languages/` to modify translations.

## Features

✅ Bilingual (Portuguese/English)
✅ Blue and green color scheme
✅ Custom post type for hiking events
✅ Google Maps integration with routes
✅ Multiple stop points with times
✅ Google OAuth login
✅ Event registration system
✅ Participant management
✅ Responsive design
✅ Admin interface for event creation

## Support

For issues or questions, please refer to the WordPress documentation or plugin documentation.

## License

This theme and plugin are provided as-is for the Inner Flow project.
