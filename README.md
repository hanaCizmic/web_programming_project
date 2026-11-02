# TripMate — Web Programming Project

## Project Description

TripMate is a travel planner designed to help students organize their trips in one place. Users can plan destinations and daily itineraries, manage travel budgets and expenses, and create packing checklists.

## Main Features

- Create, view, edit, and delete trips
- Organize destinations and daily itineraries
- Set travel budgets and track expenses
- Create and manage packing checklists
- Check weather information for destinations

## Technologies

- HTML
- CSS
- JavaScript
- PHP (planned backend)
- MySQL (planned database)
- FlightPHP (planned backend framework)

## Project Structure

- `frontend/` — HTML views, CSS styles, JavaScript, and static assets
- `backend/` — backend application structure
- `docs/` — project documentation and the draft ERD

## Current Status

Milestone 1 focuses on the project structure, static single-page application, and draft database schema. Backend integration and database implementation will be completed in later milestones.
Milestone 2 implemented the MySQL database with six entities, sample data, the PDO database connection, a reusable BaseDao class, and individual DAO classes for all six entities. The DAO methods were tested using a dedicated test script.

FlightPHP has been installed using Composer. REST API routes, services, and frontend-backend integration are planned for the following milestones.

Future Development:

Implement service classes and REST API routes using FlightPHP.
Add user authentication and role-based authorization.
Integrate an external weather API.
Connect the frontend to the backend using AJAX.
Add API documentation using OpenAPI and Swagger.
Deploy the completed application.