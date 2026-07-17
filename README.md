# PHServed
PHServed: Computer Parts E-Commerce Platform. This repository contains the source code and database for a custom-built e-commerce website developed using native PHP and MySQL. The system is designed with a dual-purpose architecture, featuring a comprehensive Seller Part for administrative management and a Buyer Part for customer transactions. 

* Note: PHServed's is a soley website experience project, mobile responsiveness is still not fully developed in terms of proper layout display.

## Main Features

### Buyer System

- Buyer registration and email confirmation
- Products grouped into three categories
- Shopping cart with product quantities
- Checkout and simulated payment
- Buyer order history

### Seller System

- Admin sign in
- User management
- Product and stock management
- Inventory report
- Current admin audit log

# Core System Features

3.1. User Experience (Buyer Part)

The buyer-facing application provides a comprehensive hardware shopping workflow, emphasizing data integrity and user verification:
* Detailed User Registration: Secure account creation requiring Complete Name, a valid E-mail Address, Password (with confirmation), Complete Physical Address, and Contact Numbers.
* SMTP-Based Verification: A professional confirmation workflow that triggers an automated email to the user for account validation.
* Categorized Product Listings: An organized hardware catalog for efficient navigation and discovery of specific components.
* Interactive Shopping Cart: Real-time "Add to Cart" functionality for managing potential purchases.
* Checkout & Payment Workflow: A complete transition from Store to Cart, through to a simulated Payment page (note: payment processing is currently non-functional and intended for educational demonstration only).

3.2. Seller & Administrative Control (Seller Part)

The administrative backend, designated as SellerPart, serves as an internal management tool for platform operations:
* Internal User Management: Capabilities to add or modify system users specifically for administrative roles.
* Inventory Control: Centralized tools to add new stock, modify existing hardware listings, and adjust real-time pricing.
* Reporting Suite:
  * Inventory Reports: Real-time visibility into remaining stock levels.
  * User-Specific Audit Log: A security-focused report detailing all system activities performed by the currently authenticated user.

## Website Access

- Open the main domain for the Buyer Store.
- Open `/seller/` for Seller Administration.
- The root `index.php` automatically opens `buyer/index.php`.
- The Seller `index.php` opens the login page or User Management depending on
  the current session.

# Component	Technology
Backend	Native PHP (strictly no frameworks permitted)
Database	MySQL (Standard relational management)
Frontend	HTML5, CSS3 (Bootstrap or Tailwind permitted), and JavaScript
Hosting	InfinityFree

# Code Authenticity Note: 
In alignment with the project's academic integrity policy, all code is authored to be fully explainable by the developers. AI tools are utilized strictly for debugging and conceptual depth rather than core logic generation.

# Project Roadmap & Timeline
The development schedule is seperated into three distinct phases:
Phase	Timeline	Key Deliverables
* Phase 1: Preparation	June 22 – June 28	Brainstorming, initialization, Sitemaps (Canva), and Wireframes (Canva).
* Phase 2: Frontend	June 6-11	Implementation of CSS and JavaScript; UI/UX responsiveness and layout design.
* Phase 3: Backend & Integration	July 11 – July 17	Native PHP development, Final GitHub Repository, MySQL database integration, and final deployment to InfinityFree. 
* * Note: Most of the members is not yet that experience in using GitHub's tools, especially when pushing and pulling our code as a team. Furthermore, to avoid major mistakes that would lead to prolonging the project, we decied to do it manually by sending each other's codes (ZIP files), then the finalized code will finally be pushed to GitHub. 
* Final Deadline: Paper Finalization	July 18, 2026	Full system submission (11:59 PM).

# Installation & Usage
Prerequisites
* A local development environment (WAMP, XAMPP, or MAMP) or an active InfinityFree hosting account.
* PHP 7.4+ and MySQL 5.7+.

## Local Setup

1. Place the `PHServed` folder inside the XAMPP `htdocs` folder.
2. Start Apache and MySQL.
3. Import `database/phserved_db.sql` using phpMyAdmin.
4. Check the values in `config/db_connect.php`.
5. Open `http://localhost/PHServed/`.

# Educational Disclaimer

Project Context: This website is developed strictly for educational purposes as a final project requirement for the Applications Development and Emerging Technologies course (BSITWMA -TW22), instructed by Sir Joseph Calleja.
* Visual Identity: The group name (PHPServed) and project logo are integrated throughout all pages as a formal requirement.
* Mandatory Disclosure: In accordance with the project guidelines, a disclaimer is present in the footer of ALL webpages stating that this site is for educational use only. All financial transactions are simulated and involve no real currency.
