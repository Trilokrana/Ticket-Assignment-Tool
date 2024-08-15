# Ticket Assignment Tool

The Ticket Assignment Tool is a web-based application that allows teams to manage tasks or issues by creating, assigning, and tracking tickets. The tool supports user authentication, ticket creation, assignment, status management, and file uploads.

## Features
- User Registration & Login
- Create and assign tickets to users
- Manage ticket statuses (Pending, In Progress, Completed, On Hold)
- File uploads for attachments
- Responsive design for easy access across devices

## Technologies Used
- PHP
- MySQL
- HTML/CSS
- JavaScript

## Getting Started


### Installation

 Clone the repository:
    ```bash
    git clone https://github.com/Trilokrana/Ticket-Assignment-Tool.git
    ```
    
 Set up the database:
    - Open **phpMyAdmin** (or your preferred MySQL client).
    - Create a new database called `ticket_db`.
    - Import the database structure from the provided 
    - Example:
      ```sql
      CREATE DATABASE ticket_db;
      USE ticket_db;
      ```

 Update the database configuration:
    - Open `config/config.php` and ensure your database connection details are correct:
    ```php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'ticket_db';
    ```

 Start the development server:
    - If using PHP’s built-in server:
    ```bash
    php -S localhost:8000
    ```
    - Access the application at `http://localhost:8000` in your browser.

### Folder Structure
- `/app/` - Contains all the core application files (authentication, ticket management, etc.).
- `/config/` - Contains the database configuration.
- `/Images/` - Stores any file uploads associated with tickets.
- `/database/` - Contains the SQL file for setting up the database.

### Usage
1. Register a new user via the registration page.
2. Log in with your credentials.
3. Create new tickets, assign them to users, and manage their status.

### Database Structure

The `ticket_db` database contains the following tables:

- `users`:
  - `id` (INT) - Primary key
  - `name` (VARCHAR) - User name
  - `email` (VARCHAR) - User email
  - `password` (VARCHAR) - Hashed password

- `createticket`:
  - `id` (INT) - Primary key
  - `title` (VARCHAR) - Ticket title
  - `description` (TEXT) - Ticket description
  - `fileUpload` (VARCHAR) - Uploaded file name
  - `assignees` (VARCHAR) - Assigned user
  - `status` (VARCHAR) - Ticket status
  - `createdby` (VARCHAR) - Creator's name

Thank You!!




