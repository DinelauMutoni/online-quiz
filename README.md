# Online Quiz System

A modern web-based quiz system built with Laravel, featuring separate interfaces for administrators and students. The system allows for creating, managing, and taking quizzes with automatic grading and detailed result tracking.

## Features

### For Administrators
- Create and manage quizzes
- Add multiple-choice questions with options
- Set quiz parameters (time limit, passing score)
- View student attempts and results
- Monitor student performance
- Manage student accounts

### For Students
- Take available quizzes
- View quiz instructions and rules
- Real-time quiz progress tracking
- Immediate feedback on completion
- Review detailed results and correct answers
- Track personal performance history

## Default Login Credentials

### Administrator Account
```
Email: admin@example.com
Password:password
```

### Student Account
```
Email: student@example.com
Password:password
```

You can create additional accounts through the registration page or admin panel.

## Technical Requirements

- PHP >= 8.0
- Laravel 10.x
- MySQL 5.7+
- Composer
- Node.js & NPM

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/online-quiz.git
cd online-quiz
```

2. Install PHP dependencies:
```bash
composer install
```

3. Copy the environment file and configure your database:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Run database migrations and seeders:
```bash
php artisan migrate
php artisan db:seed
```

6. Start the development server:
```bash
php artisan serve
```

## Database Structure

The system uses the following main tables:
- `users` - User accounts and roles
- `quizzes` - Quiz details and settings
- `questions` - Quiz questions and correct answers
- `quiz_attempts` - Student quiz attempts
- `answers` - Student answers for each attempt

## Usage

### Administrator Access
1. Login with admin credentials
2. Access the admin dashboard
3. Create and manage quizzes
4. Monitor student performance

### Student Access
1. Register/Login with student credentials
2. Browse available quizzes
3. Start a quiz attempt
4. Submit answers within time limit
5. View results and performance history

## Security Features

- Authentication and authorization
- CSRF protection
- XSS prevention
- Session management
- Input validation
- Secure password hashing

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please create an issue in the GitHub repository or contact the development team.

## Acknowledgments

- Laravel Framework
- Bootstrap CSS Framework
- Font Awesome Icons
- jQuery Library
