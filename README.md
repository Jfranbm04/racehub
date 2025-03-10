# RaceHub API Documentation

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

## API Documentation

### Base URL
```
https://api.racehub.com
```

## Authentication

### Login
**Endpoint:** `POST /api2/auth/login_check`

**Description:** Authenticate user and get JWT token.

**Request Body:**
```json
{ 
  "email": "string", 
  "password": "string" 
}
```

**Response:**
```json
{ 
  "user": 
        { 
          "id": 1, 
          "email": "user@example.com", 
          "roles": ["ROLE_USER"] 
        }, 
        "message": "Login successful" 
}

```

### Logout
**Endpoint:** `POST /api/auth/logout`

**Description:** Ends user session.

**Response:**
```json
{ 
  "message": "Logout successful" 
}
```

---
## User Management

### Get All Users
**Endpoint:** `GET /api/user`

**Response:**
```json
[ 
  { 
    "id": 1, 
    "email": "john@example.com", 
    "roles": ["ROLE_USER"], 
    "name": "John Doe", 
    "banned": false 
  } 
]
```

### Get User by ID
**Endpoint:** `GET /api/user/:id`

**Response:**
```json
{ 
  "id": 1, 
  "email": "john@example.com", 
  "roles": ["ROLE_USER"], 
  "name": "John Doe", 
  "banned": false 
}
```

### Edit User
**Endpoint:** `PUT /api/user/:id/edit`

**Request Body:**
```json
{ 
  "email": "new@example.com", 
  "roles": ["ROLE_ADMIN"], 
  "name": "John Updated", 
  "oldpassword": "oldpass", 
  "newpassword": "newpass", 
  "banned": false 
}
```

**Response:**
```json
{ 
  "success": true 
}
```

---
## Race Events

### Get All Cycling Events
**Endpoint:** `GET /api/cycling`

**Response:**
```json
[ 
  { 
    "id": 1, "name": "Tour de Example", 
    "description": "Annual cycling event", 
    "date": "2024-06-15T09:00:00Z", 
    "distance_km": 150, 
    "location": "Paris", 
    "image": "url-to-image" 
  } 
]
```

### Get Cycling Event by ID
**Endpoint:** `GET /api/cycling/:id`

**Response:**
```json
{ 
  "id": 1, 
  "name": "Tour de Example", 
  "description": "Annual cycling event", 
  "date": "2024-06-15T09:00:00Z", 
  "distance_km": 150, 
  "location": "Paris", 
  "image": "url-to-image" 
}
```

---
## Participants

### Get All Participants
**Endpoint:** `GET /api/cycling_participant/`

**Response:**
```json
[ 
  { 
    "id": 1, 
    "user": 1, 
    "cycling": 1, 
    "time": "02:30:00", 
    "dorsal": 101, 
    "banned": false 
  } 
]
```

### Register for Cycling Event
**Endpoint:** `POST /api/cycling_participant/new`

**Request Body:**
```json
{ 
  "user": 1, 
  "cycling": 1, 
  "time": "02:30:00", 
  "dorsal": 101, 
  "banned": false 
}
```

**Response:**
```json
{ 
  "id": 1, 
  "user": 1, 
  "cycling": 1, 
  "time": "02:30:00", 
  "dorsal": 101, 
  "banned": false 
}
```

### Delete Participant
**Endpoint:** `DELETE /api/cycling_participant/:id`

**Response:**
```json
{ 
  "success": true 
}
```

---
## API Response Codes

| Code | Meaning          |
|------|------------------|
| 200  | Success          |
| 201  | Created          |
| 400  | Bad Request      |
| 401  | Unauthorized     |
| 403  | Forbidden        |
| 404  | Not Found        |
| 422  | Validation Error |
| 500  | Server Error     |

**Error Response Format:**
```json
{ 
  "status": "error", 
  "code": 400, 
  "message": "Error description", 
  "errors": 
    [ 
      { 
        "field": "username", 
        "message": "Username is required" 
      } 
    ] 
}
```

---
## Contributing

1. Fork the repository.
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open Pull Request.

### Coding Standards
- Follow PSR-12
- Write unit tests
- Document new features

## Support
- Issues: GitHub Issues
- Email: support@racehub.com
- Documentation: [Link to detailed documentation]

**Last Updated:** 2025  
**Version:** 1.0.0
