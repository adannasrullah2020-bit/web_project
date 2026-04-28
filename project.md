# JourneyHub - Tourism Management System

**A comprehensive web-based journey booking system for flights, hotels, and trains**

---

## Table of Contents
1. [Project Overview](#project-overview)
2. [Technical Stack](#technical-stack)
3. [Key Features](#key-features)
4. [System Architecture](#system-architecture)
5. [Database Schema](#database-schema)
6. [Module Description](#module-description)
7. [User Workflows](#user-workflows)
8. [File Structure](#file-structure)
9. [Installation & Setup](#installation--setup)
10. [Admin Panel](#admin-panel)
11. [Security Features](#security-features)
12. [Future Enhancements](#future-enhancements)

---

## Project Overview

**JourneyHub** is a full-featured tourism management system that enables users to book flights, hotels, and trains online. The system provides a user-friendly interface for guests and registered users, a comprehensive admin panel for managing inventory, and secure payment processing.

### Project Scope
- Online flight booking with one-way and return trip options
- Hotel search and reservation
- Train ticket booking and management
- User account management with profile customization
- Admin dashboard for managing bookings and inventory
- Ticket generation and receipt management
- Payment processing integration

### Target Users
- **End Users**: Travel enthusiasts wanting to book flights, hotels, and trains
- **Admin Users**: Administrators managing flight/hotel/train inventory and bookings

---

## Technical Stack

### Backend
- **Language**: PHP 7.1.12+
- **Server**: Apache Server
- **Framework**: Custom PHP (procedural)

### Database
- **DBMS**: MySQL / MariaDB 10.1.21+
- **Connection**: MySQLi (improved MySQL extension)

### Frontend
- **Markup**: HTML5
- **Styling**: CSS3, Bootstrap 5.3.0
- **Icons**: Bootstrap Icons 1.10.5
- **JavaScript**: jQuery 2.1.1, jQuery 3.2.1, Vanilla JavaScript
- **UI Libraries**: 
  - Bootstrap Select
  - Bootstrap Datetimepicker
  - Moment.js with Locales

### Fonts
- Google Fonts: Courgette (branding), Inter (UI)
- Font Awesome 4.7.0 (icons)

### Development Environment
- **Server Software**: XAMPP / WAMP / LAMP
- **Web Browsers**: Mozilla Firefox, Google Chrome, Internet Explorer 8, Opera

---

## Key Features

### User Features (Non-Admin)

#### Authentication & Account Management
- User registration with email verification
- Secure login/logout functionality
- Password reset via email
- Account deletion with confirmation
- Forgot password recovery
- Check username availability during signup

#### Flight Booking
- One-way flight search
- Return trip flight search (outbound + inbound)
- Multi-passenger booking
- Real-time seat availability
- Flight details display
- Booking confirmation and tickets
- E-ticket generation

#### Hotel Booking
- Hotel search by location and dates
- Hotel details with amenities
- Room availability checking
- Multi-room/multi-guest booking
- Payment processing
- Booking confirmation

#### Train Booking
- Train search by route and date
- Train details and availability
- Seat selection
- Ticket generation
- E-ticket management

#### Booking Management
- View all bookings in user dashboard
- Cancel flights, trains, or hotel reservations
- View booking history
- Download e-tickets
- Generate receipts

#### User Dashboard
- Profile management
- Account settings customization
- View bookings
- View e-tickets
- Cancel tickets
- Account security settings

### Admin Features

#### Authentication
- Secure admin login/logout
- Session-based access control

#### User Management
- View all registered users
- Delete user accounts
- User information display (non-sensitive data)
- User list management

#### Flight Management
- Add new flights to system
- Update flight details (operators, schedules, pricing)
- View flight bookings
- Manage flight inventory

#### Hotel Management
- Add new hotels
- Update hotel information
- View hotel bookings
- Manage room availability

#### Train Management
- Add new train routes
- Update train schedules
- View train bookings
- Manage seat inventory

#### Booking Management
- View all flight bookings
- View all hotel bookings
- View all train bookings
- Track booking status

---

## System Architecture

### Application Flow

```
┌─────────────────┐
│   End User      │
└────────┬────────┘
         │
    ┌────▼─────────────────────┐
    │   Public Website          │
    │  - index.php              │
    │  - login.php              │
    │  - signup.php             │
    │  - flights.php            │
    │  - hotels.php             │
    │  - trains.php             │
    └────┬─────────────────────┘
         │
    ┌────▼────────────────────┐
    │  User Dashboard          │
    │  - userDashboard*.php    │
    │  - booking/ticket mgmt   │
    └────┬─────────────────────┘
         │
         └────────────────────┐
                              │
┌─────────────────────────────▼──┐
│   Authentication Layer          │
│  - loginAction.php              │
│  - signupAction.php             │
│  - logout.php                   │
└─────────────────────────────────┘
         │
    ┌────▼──────────────────────┐
    │   Database Layer           │
    │  - MySQL/MariaDB           │
    │  - Tables: users, flights, │
    │    hotels, trains, etc.    │
    └────────────────────────────┘

┌──────────────────────────────────┐
│   Admin Panel (Management/)       │
│  - adminLogin.php                │
│  - flights_add.php               │
│  - hotels_add.php                │
│  - trains_add.php                │
│  - users_add.php                 │
│  - *_view.php (bookings)         │
└──────────────────────────────────┘
```

### Module Architecture

#### Frontend Modules
1. **Public Module**: Accessible to all visitors
2. **User Module**: Requires authentication
3. **Admin Module**: Requires admin authentication

#### Backend Processing
- Request validation
- Database queries via MySQLi
- Session management
- Email notifications (password reset)
- Payment processing

---


#### Helper Functions (php/)
- `PasswordHash.php` - Password hashing and verification

#### Static Assets
- `css/` - Stylesheets (Bootstrap, custom themes, Font Awesome)
- `js/` - JavaScript files (jQuery, Bootstrap, custom scripts)
- `images/` - Image resources (carousel, destinations, user dashboard)
- `fonts/` - Font files (Font Awesome 4.7.0)



**Last Updated**: April 28, 2026  
