# RaceHub

## Table of Contents
- [Project Overview](#project-overview)
- [Installation](#installation)
- [API Documentation](#api-documentation)
  - [Authentication](#authentication)
  - [User Management](#user-management)
  - [Race Events](#race-events)
  - [Participants](#participants)
- [Development](#development)
- [Contributing](#contributing)

## Project Overview

RaceHub is a comprehensive platform for managing racing events, including cycling, running, and trail running competitions. Built with Symfony 7.2, it provides a robust API for event management and participant tracking.

### Features
- Multi-sport event management
- User authentication and authorization
- Participant registration system
- Race results tracking
- Admin dashboard

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- MySQL
- Symfony CLI

### Setup Steps

1. Clone the repository:
```bash
git clone https://github.com/yourusername/racehub.git
cd racehub
```

2. Install dependencies:
```bash
composer install
 ```

3. Configure environment:
```bash
cp .env.example .env
 ```

4. Set up database:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
 ```

5. Generate JWT keys:
```bash
php bin/console lexik:jwt:generate-keypair
 ```
6. Start server:
```bash
symfony server:start
 ```

API Documentation
Base URL
plaintext
```	plaintext
https://api.racehub.com
```


Authentication
Login
POST /api/auth/login
Description: Authenticate user and get JWT token
Request Body:
```json
{  
    "username": "string",
    "password": "string"
}
```
Response:
```json

{  
    "token": "eyJhbGciOiJIUzI1NiIs...",
    "user": 
        {    
            "id": 1,    
            "username": "user",    
            "roles": ["ROLE_USER"]  
        }
}
```
Logout
POST /api/auth/logout
Description: End user session
Authentication: Required
Response: Success message
User Management
Get All Users
GET /api/user
Auth Required: Yes
Parameters:
page (optional): Page number
limit (optional): Items per page
Response:
json

{  "data": [    {      "id": 1,      "username": "john_doe",      "email": "john@example.com",      "roles": ["ROLE_USER"]    }  ],  "total": 100,  "page": 1,  "limit": 10}
Race Events
Cycling Events
Create Event
POST /api/cycling
Auth Required: Yes (Admin)
Request Body:
json

{  "name": "Tour de Example",  "date": "2024-06-15T09:00:00Z",  "location": "Paris",  "description": "Annual cycling event",  "distance": 150,  "elevationGain": 1200,  "maxParticipants": 500}
Get Event
GET /api/cycling/{id}
Auth Required: Yes
Response: Event details
Running Events
Similar endpoints with running-specific attributes.

Trail Running Events
Additional parameters for terrain difficulty and safety requirements.

Participants
Register for Event
POST /api/{event-type}-participant
Auth Required: Yes
Request Body:
json

{  "eventId": 1,  "userId": 1,  "category": "amateur",  "emergencyContact": {    "name": "Jane Doe",    "phone": "+1234567890"  }}
Development
Database Migrations
Create migration:

bash
Run
php bin/console make:migration
Apply migrations:

bash
Run
php bin/console doctrine:migrations:migrate
Testing
Run tests:

bash
Run
php bin/phpunit
API Response Codes
200: Success
201: Created
400: Bad Request
401: Unauthorized
403: Forbidden
404: Not Found
422: Validation Error
500: Server Error
Error Response Format
json

{  "status": "error",  "code": 400,  "message": "Error description",  "errors": [    {      "field": "username",      "message": "Username is required"    }  ]}
Contributing
Fork the repository
Create feature branch (git checkout -b feature/amazing-feature)
Commit changes (git commit -m 'Add amazing feature')
Push to branch (git push origin feature/amazing-feature)
Open Pull Request
Coding Standards
Follow PSR-12
Write unit tests
Document new features
License
[Your License]

Support
Issues: GitHub Issues
Email: support@racehub.com
Documentation: [Link to detailed documentation]
Last Updated: 2024 Version: 1.0.0

plaintext

The documentation is now complete in your README.md file. Would you like me to add or modify any specific section?