# AGENTS.md — TripMate

## Project
TripMate is a travel-planning web application that helps users organize trips, destinations, daily itineraries, expenses, budgets, and packing lists.

## Technology Stack
- Frontend: HTML, CSS, JavaScript
- Backend: PHP with FlightPHP REST API
- Database: MySQL
- Database access: PDO
- API communication: JSON and AJAX
- Authentication: password hashing and JWT
- API documentation: OpenAPI and Swagger

## Entities
- Users
- Trips
- Destinations
- Activities
- Expenses
- Packing Items

## Roles
- User: manages their own trips, destinations, activities, expenses, and packing lists.
- Admin: has access to authorized administrative functionality.

## Folder Structure
- `frontend/`: frontend application
- `frontend/css/`: stylesheets
- `frontend/js/`: JavaScript files
- `frontend/views/`: HTML views and templates
- `frontend/static/`: static assets
- `backend/routes/`: API routes
- `backend/services/`: application logic
- `backend/dao/`: database access objects
- `backend/middleware/`: authentication, authorization, logging, and error handling
- `backend/config/`: configuration files
- `backend/logs/`: application logs
- `docs/`: project documentation, including `AI_LOG.md`

## Frontend Rules
- Keep HTML, CSS, and JavaScript separate.
- Build a responsive interface that works on desktop and mobile.
- Use AJAX to communicate with the backend API.
- Validate user input and provide understandable error messages.

## Backend Rules
- Use FlightPHP for REST API routes.
- Keep responsibilities separated between routes, services, and DAOs.
- Use PDO prepared statements for database queries.
- Return JSON responses with appropriate HTTP status codes.
- Validate input on the backend.
- Handle and log errors consistently.

## Security Rules
- Hash passwords securely; never store plain-text passwords.
- Use JWT authentication and role-based authorization as required.
- Keep private credentials and secrets out of Git.
- Keep `backend/config/config.php` uncommitted and provide a safe `config.example.php`.
- Do not commit real secrets or sensitive personal data.

## Conventions
- Use clear, descriptive names for files, variables, functions, and classes.
- Keep code readable and explainable.
- Prefer small, incremental changes.
- Avoid unnecessary dependencies and complexity.

## Rules for AI Assistants
- Explain suggested code and the reasons for changes.
- Do not replace entire files unnecessarily.
- Check existing code before suggesting changes.
- Do not claim that code was tested unless it was actually tested.
- Ask before introducing major dependencies or changing agreed project design.
-Never put passwords, keys or secrets in code, they belong in backend/config/config.php, which is not committed. Update config.example.php instead.


## Current Status
TripMate is in the initial frontend development stage. The login/register interface, dashboard, and create-trip dialog are being prototyped. Backend authentication, database persistence, API integration, and deployment remain to be implemented.