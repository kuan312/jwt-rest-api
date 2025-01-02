# Firebase JWT REST API

This is a procedural REST API built using Firebase JWT, featuring routes for user registration, login, token refresh, and access to protected resources.

## Endpoints

### **Register User**
**POST** `/api/register`

**Request Body:**
```json
{
    "username": "test",
    "password": "12345"
}
```

**Response:**
```json
{
    "message": "User registered successfully"
}
```

### **Login**
**POST** `/api/login`

**Request Body:**
```json
{
    "username": "test",
    "password": "12345"
}
```

**Response:**
```json
{
    "access_token": "<access_token>",
    "refresh_token": "<refresh_token>"
}
```

### **Refresh Token**
**POST** `/api/refresh`

**Request Body:**
```json
{
    "refresh_token": "<refresh_token>"
}
```

**Response:**
```json
{
    "access_token": "<new_access_token>"
}
```

### **Protected Route**
**GET** `/api/protected`

**Headers:**
- `Authorization`: Bearer `<access_token>`

**Response:**
```json
{
    "type": "success",
    "data": {
        "iss": "localhost",
        "aud": "localhost",
        "iat": 1735840502,
        "exp": 1735844102,
        "username": "test1"
    }
}
