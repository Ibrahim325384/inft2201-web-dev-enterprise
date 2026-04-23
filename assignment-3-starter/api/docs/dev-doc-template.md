# Assignment 3 – Developer Documentation

## 1. Overview

Briefly describe what this API does and the main use case.

- Example: “This API provides authenticated access to mail messages for a corporate mail system, with role-based access control, logging, rate limiting, and centralized error handling.”

- "This API authenticates users and gives them authorization based on their role. 
Furthermore it centralizes all the error handling and implements rate limiting."

---

## 2. Authentication

### 2.1 Auth Method

- Scheme: Bearer token (JWT)
- How to obtain a token:
  - Endpoint: `POST /auth/login`
  - Request body format:
    ```json
    {
      "username": "user1",
      "password": "user123"
    }
    ```
  - Example success response:
    ```json
    {
      "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6..."
    }
    ```

### 2.2 Using the Token

- Required header for authenticated requests:
  - `Authorization: Bearer <token>`

Mention any expiry behavior (e.g., tokens are valid for 1 hour).

- Tokens expire in 1 hour and they are needed in the authorization header 
to get access to any protected routes

---

## 3. Roles & Access Rules

Describe each role and what it can do.

Example:

- `admin`
  - Can view any mail message.
- `user`
  - Can only view their own mail messages.

You can include a simple matrix:

| Endpoint        | Method | admin | user |
|----------------|--------|-------|------|
| `/mail/:id`    | GET    | ✅ all mail | ✅ own mail only |
| `/auth/login`  | POST   | ✅ | ✅ |
| `/status`      | GET    | ✅ | ✅ |

- Admin
  - Can view any mail

- user1
  - Can only see their own mail

- user2
  - Can only see their own mail

- Invalid login
  - Doesn't exist, so gives error message 

---

## 4. Endpoints

### 4.1 `POST /auth/login`

**Description:**  
Authenticate with username/password and receive a JWT.

**Request Body:**

```json
{
  "username": "user1",
  "password": "user123"
}
```

**Success Response (200):**

```json
{
  "token": "..."
}
```

   User tries to access something they are not authorized to they get the below error
   - curl http://localhost:3000/mail/1
   - {"error":"Unathorized","message":"Missing Authorization header","statusCode":401,"requestId":"req-19dbb08c82f-378d25","timestamp":"2026-04-23T15:50:08.202Z"}

**Notes:**
Document any common failure reasons (invalid credentials, missing fields).

---

### 4.2 `GET /mail/:id`

**Description:**
Retrieve a single mail message by ID.

**Authentication:**

* Requires `Authorization: Bearer <token>` header.

**Access Rules:**

* `admin`: may view any mail ID.
* `user`: may view only mail where `mail.userId` matches their own `userId`.

**Example Request:**

```bash
curl http://localhost:3000/mail/2 \
  -H "Authorization: Bearer <token>"
```

**Example Success Response (200):**

```json
{
  "id": 2,
  "userId": 2,
  "subject": "Hello User1",
  "body": "Your report is ready."
}
```

**Example Forbidden Response (when user tries to access someone else’s mail):**

```json
{
  "error": "Forbidden",
  "message": "User does not have permission to access this resource.",
  "statusCode": 403,
  "requestId": "req-12345",
  "timestamp": "2025-11-30T14:22:00Z"
}
```
      User tries to access mail that doesn't exist
      - curl http://localhost:3000/mail/999 -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjEsInJvbGUiOiJhZG1pbiIsImlhdCI6MTc3Njk2MDI3MywiZXhwIjoxNzc2OTYzODczfQ.7OtWZcVGMf5AiFjahHYcvctt1T9mgcriL7jHFHJpYrM"
      - {"error":"Not Found","message":"Mail not found","statusCode":404,"requestId":"req-19dbb18f2c1-22742d","timestamp":"2026-04-23T16:07:47.658Z"}
---

### 4.3 `GET /status`

**Description:**
Simple health check to confirm the API is running.

**Authentication:**

* None required.

**Example Response (200):**

```json
{
  "status": "ok"
}
```

---

## 5. Rate Limiting

Describe how rate limiting works in your implementation.

* Keyed by: (IP address) or (userId from token).
* Limit: e.g. `RATE_LIMIT_MAX` requests per `RATE_LIMIT_WINDOW_SECONDS`.
* What happens when the limit is exceeded:

  * Example response:

    ```json
    {
      "error": "TooManyRequests",
      "message": "Rate limit exceeded. Please try again later.",
      "statusCode": 429,
      "requestId": "req-67890",
      "timestamp": "2025-11-30T14:30:00Z"
    }
    ```

You can also mention if you set a `Retry-After` header or include a field in the JSON.

      User issues too many requests
      - for /l %i in (1,1,10) do curl -s http://localhost:3000/auth/login -H "Content-Type: application/json" -d "{\"username\": \"user1\", \"password\": \"user123\"}"
      - {"error":"Too Many Requests","message":"Too Many Requests","statusCode":429,"requestId":"unknown","timestamp":"2026-04-23T15:59:28.801Z"}
---

## 6. Error Response Format

Briefly describe the standard error JSON returned by your centralized error handler.

Example:

```json
{
  "error": "Forbidden",
  "message": "User does not have permission to access this resource.",
  "statusCode": 403,
  "requestId": "req-abc123",
  "timestamp": "2025-11-30T14:35:00Z"
}
```

List a few common error categories you use (`BadRequest`, `Unauthorized`, `Forbidden`, `NotFound`, `TooManyRequests`, `InternalServerError`, etc.).

---

## 7. Example Flows

Provide at least one complete “happy path” and one “error path”:

### 7.1 Happy Path: Login + Access Own Mail

1. `POST /auth/login` as `user1` → receive token.
2. `GET /mail/2` with that token → receive mail details.

   Admin viewing their and other members mail (Happy Path)
   - Viewing their own mail 
   - curl http://localhost:3000/mail/1 -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjEsInJvbGUiOiJhZG1pbiIsImlhdCI6MTc3NjkwNjkwMiwiZXhwIjoxNzc2OTEwNTAyfQ.EEUoNl2NfcB-BbECmvzdcyG0JaUslzpNTneFKpqw4RY"
   - {"id":1,"userId":1,"subject":"System Notice","body":"Welcome, admin!"}

   - Viewing user1's mail
   - curl http://localhost:3000/mail/2 -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjEsInJvbGUiOiJhZG1pbiIsImlhdCI6MTc3NjkwNjkwMiwiZXhwIjoxNzc2OTEwNTAyfQ.EEUoNl2NfcB-BbECmvzdcyG0JaUslzpNTneFKpqw4RY" 
   - {"id":2,"userId":2,"subject":"Hello User1","body":"Your report is ready."} 

   - Viewing user2's mail
   - curl http://localhost:3000/mail/3 -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjEsInJvbGUiOiJhZG1pbiIsImlhdCI6MTc3NjkwNjkwMiwiZXhwIjoxNzc2OTEwNTAyfQ.EEUoNl2NfcB-BbECmvzdcyG0JaUslzpNTneFKpqw4RY"
   - {"id":3,"userId":3,"subject":"Hello User2","body":"You have a new message."}


Include the exact curl commands and example responses.

### 7.2 Error Path: User Accessing Someone Else’s Mail

1. Login as `user1`.
2. `GET /mail/1` (which belongs to another user).
3. Show the `403` response.

  User1 tries to view Admin's mail (Error Path)
  - curl http://localhost:3000/mail/1 -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjIsInJvbGUiOiJ1c2VyIiwiaWF0IjoxNzc2OTEwOTUwLCJleHAiOjE3NzY5MTQ1NTB9.SUzRAYLWjN1hNp7-aotkKe_hNTaZrUtg7ZBGzc_QcGM"
  - {"error":"Forbidden","message":"Forbidden","statusCode":403,"requestId":"req-19db825c2e1-a1eff5","timestamp":"2026-04-23T02:22:55.716Z"}