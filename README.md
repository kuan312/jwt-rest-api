# Firebase JWT REST API

This is a demonstration of a REST API built using Firebase JWT, featuring endpoints for user registration, login, token refresh, and access to protected resources.

## Installation

```bash
composer install
```

-----

🌐 **Register User**
**POST** `/api/register`

**Request Body:**

```json
{ "username": "test", "password": "12345" }
```

**Response:**
* ✅️ **Success:**

```json
{ "type": "success", "data": { "message": "User registered successfully" } }
```
* ❌ **Error:**

```json
{ "type": "error", "message": "User already exists" }
```

-----
🌐 **Login**
**POST** `/api/login`

**Request Body:**

```json
{ "username": "test", "password": "12345" }
```
**Response:**
* ✅️ **Success:**

```json
{ "type": "success", "data": { "access_token": "<access_token>", "refresh_token": "<refresh_token>" } }
```
* ❌ **Error:**

```json
{ "type": "error", "message": "Invalid credentials" }
```
**Note:** After a successful login, the client should **securely store** the received `access_token` and `refresh_token`. Typically, tokens are stored in `localStorage`/`sessionStorage` etc.

-----
🌐 **Refresh Token**
**POST** `/api/refresh`

**Request Body:**

```json
{ "refresh_token": "<refresh_token>" }
```
**Response:**
* ✅️ **Success:**

```json
{ "access_token": "<new_access_token>" }
```
* ❌ **Error:**

```json
{ "type": "error", "message": "Invalid or expired refresh token" }
```

-----
🌐 **Protected Route**
**GET** `/api/protected`

**Headers:**
* `Authorization`: Bearer `<access_token>`

**Response:**
* ✅️ **Success:**

```json
{ "type": "success", "data": { "iss": "localhost", "aud": "localhost", "iat": 1735840502, "exp": 1735844102, "username": "test1" } }
```
* ❌ **Error:**

```json
{ "type": "error", "message": "Invalid token" }
```
**Note:** In a production environment, the server **checks the validity** of the `access_token` **every time** a protected resource is accessed. This ensures that only authenticated users can access sensitive data.

Workflow
1. **Registration:**
   * **Action:** User sends `username` and `password` to `/api/register`.
   * **Process:** Server checks if the user exists. If not, it hashes the password and saves the credentials.
   * **Result:** Returns a success message or an error if the user already exists.
2. **Login:**
   * **Action:** User sends `username` and `password` to `/api/login`.
   * **Process:** Server verifies the credentials. Upon successful verification, it generates `access_token` and `refresh_token`.
   * **Result:** Tokens are returned to the client, which should store them securely.
3. **Accessing Protected Resources:**
   * **Action:** Client requests `/api/protected` with the `access_token` in the `Authorization` header.
   * **Process:** Server validates the `access_token`.
   * **Result:** If valid, access to the protected resource is granted; otherwise, an error is returned.
4. **Refreshing Access Token:**
   * **Action:** When the `access_token` expires, the client sends the `refresh_token` to `/api/refresh`.
   * **Process:** Server verifies the `refresh_token` and, if valid, issues a new `access_token`.
   * **Result:** A new `access_token` is returned to the client for continued access.