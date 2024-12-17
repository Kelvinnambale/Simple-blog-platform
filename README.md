# Blog Platform

A simple PHP-based blog platform that allows users to create posts and add comments. 
This application provides a clean, user-friendly interface for managing blog content with a MySQL backend.

## Features

- Create and publish blog posts
- Comment system for each post
- Responsive design
- Real-time form validation
- Secure database operations with prepared statements
- Clean and modern UI with Bootstrap-like styling

## Prerequisites

- PHP 7.0 or higher
- MySQL 5.6 or higher
- Web server (Apache/Nginx)
- mysqli PHP extension enabled

## Installation

1. Clone this repository to your web server's directory:
```bash
git clone [https://github.com/kelvinnambale/Simple-blog-platform]
cd Simple-blog-platform
```

2. Create a new MySQL database:
```sql
CREATE DATABASE blog_platform;
```

3. Create the required tables:
```sql
USE blog_platform;

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id)
);
```

4. Update the database connection settings in the PHP file:
```php
$conn = new mysqli("localhost", "root", "", "blog_platform");
```
Replace the connection parameters with your database credentials.

## Configuration

The default configuration uses these database connection parameters:
- Host: localhost
- Username: root
- Password: (empty)
- Database: blog_platform

Modify these values in the PHP file according to your environment.

## Security Considerations

- The platform implements SQL injection prevention using prepared statements
- HTML output is escaped to prevent XSS attacks
- Form validation is implemented on both client and server sides
- It's recommended to:
  - Use a secure password for your database
  - Implement user authentication
  - Configure proper file permissions
  - Keep PHP and all dependencies up to date

## Features to Add

- User authentication system
- Image upload functionality
- Rich text editor for post content
- Post categories and tags
- Search functionality
- Pagination for posts and comments

## Contributing

Feel free to fork this repository and submit pull requests for any improvements.

## License

This project is open-source and available under the MIT License.
