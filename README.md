# PHServed
PHServed: Computer Parts E-Commerce Platform. This repository contains the source code and database for a custom-built e-commerce website developed using native PHP and MySQL. The system is designed with a dual-purpose architecture, featuring a comprehensive Seller Part for administrative management and a Buyer Part for customer transactions.

# Project Overview
PHPServed is a specialized web application engineered for the digital procurement of computer hardware. Developed as a robust, two-sided marketplace, the platform facilitates a seamless transaction lifecycle between Buyers and Sellers. This project is a core requirement for the Applications Development and Emerging Technologies course (Section BSITWMA -TW22)

The architecture is built using a "native" development philosophy. This approach is a specific pedagogical constraint designed to ensure mastery of core programming fundamentals, data validation, and database management before progressing to high-level frameworks.

# Core System Features

3.1. Buyer Experience

The buyer-facing application provides a comprehensive hardware shopping workflow, emphasizing data integrity and user verification:
* Detailed User Registration: Secure account creation requiring Complete Name, a valid E-mail Address, Password (with confirmation), Complete Physical Address, and Contact Numbers.
* SMTP-Based Verification: A professional confirmation workflow that triggers an automated email to the user for account validation.
* Categorized Product Listings: An organized hardware catalog for efficient navigation and discovery of specific components.
* Interactive Shopping Cart: Real-time "Add to Cart" functionality for managing potential purchases.
* Checkout & Payment Workflow: A complete transition from Store to Cart, through to a simulated Payment page (note: payment processing is currently non-functional and intended for educational demonstration only).

3.2. Seller & Administrative Control (SellerPart)

The administrative backend, designated as SellerPart, serves as an internal management tool for platform operations:
* Internal User Management: Capabilities to add or modify system users specifically for administrative roles.
* Inventory Control: Centralized tools to add new stock, modify existing hardware listings, and adjust real-time pricing.
* Reporting Suite:
  * Inventory Reports: Real-time visibility into remaining stock levels.
  * User-Specific Audit Log: A security-focused report detailing all system activities performed by the currently authenticated user.

# Technical Stack
In accordance with course requirements, the platform utilizes a native stack to demonstrate fundamental coding proficiency.

# Component	Technology
Backend	Native PHP (strictly no frameworks permitted)
Database	MySQL (Standard relational management)
Frontend	HTML5, CSS3 (Bootstrap or Tailwind permitted), and JavaScript
Hosting	InfinityFree

# Code Authenticity Note: 
In alignment with the project's academic integrity policy, all code is authored to be fully explainable by the developers. AI tools are utilized strictly for debugging and conceptual depth rather than core logic generation.

# Project Roadmap & Timeline
The development schedule is partitioned into three distinct phases of increasing complexity.
Phase	Timeline	Key Deliverables
Phase 1: Preparation	June 22 – June 28	Brainstorming, initialization, Sitemaps (Canva), and Wireframes (Canva).
Phase 2: Frontend	June 6-11	Implementation of CSS and JavaScript; UI/UX responsiveness and layout design.
Phase 3: Backend & Integration	July 11 – July 17	Native PHP development, Final GitHub Repository, MySQL database integration, and final deployment to InfinityFree. 
Final Deadline: Paper Finalization	July 18, 2026	Full system submission (11:59 PM).

# Installation & Usage
Prerequisites
* A local development environment (WAMP, XAMPP, or MAMP) or an active InfinityFree hosting account.
* PHP 7.4+ and MySQL 5.7+.

Setup Instructions
1. Repository Setup: Clone the project files from the GitHub repository into your local htdocs or www directory.
2. Database Configuration:
  * Access your MySQL management tool (e.g., phpMyAdmin).
  * Create a new database and import the provided .sql file found in the repository.
3. Connection Strings:
  * Locate the database configuration file (typically db.php or config.php).
  * Update the hostname, username, password, and database_name variables to match your environment credentials.
4. Testing: Access the application via localhost or your hosted URL using the sample user accounts provided in the test_accounts.txt file.

# Educational Disclaimer

Project Context: This website is developed strictly for educational purposes as a final project requirement for the Applications Development and Emerging Technologies course (BSITWMA -TW22), instructed by Sir Joseph Calleja.
Visual Identity: The group name (PHPServed) and project logo are integrated throughout all pages as a formal requirement.
Mandatory Disclosure: In accordance with the project guidelines, a disclaimer is present in the footer of ALL webpages stating that this site is for educational use only. All financial transactions are simulated and involve no real currency.
