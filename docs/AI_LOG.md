AI Log — TripMate
Milestone 1
THis file document how AI tools are used during the development of this project.

Session 1 — Project Setup and Initial Documentation

Tool: GitHub Copilot Chat
Task: Understand the project requirements and configure the initial project setup.
Main prompt: Asked Copilot for help understanding the project requirements and setting up the `.gitignore` file.
How AI was used: Copilot helped clarify technical requirements and explained which files and folders should be excluded from Git, including configuration files containing secrets and generated dependencies.
What was kept, changed, or rejected: Used the explanations to configure `.gitignore` and guide the initial setup. Created the project folders and files manually in VS Code and planned the project features independently.
What was learned: How to organize a web development project and why `.gitignore` is important for keeping unnecessary files and sensitive configuration out of the repository.

Session 2 — Organizing CSS Colors with Custom Properties

Tool: GitHub Copilot Chat
Task: Improve the organization and consistency of the TripMate color palette in CSS.
Main prompt: Asked Copilot for help understanding where to define the project's colors in CSS and how to reuse them throughout the stylesheet.
How AI was used: Copilot explained how the :root selector can be used to define CSS custom properties for the project's color palette, making the colors easier to manage and reuse.
What was kept, changed, or rejected: Used the explanation to organize the TripMate colors into custom properties, including --blue, --rose, --sky, --text, --muted, --border, --white, --sand, and --ivory.
What was learned: How :root and CSS custom properties work, how to reference a color using var(--primary), and why centralizing colors makes it easier to maintain a consistent design and update the palette later.


Session 3 — Implementing the Login and Registration Switch

Tool: GitHub Copilot Chat
Task: Understand how to implement the switch between the login and registration forms using HTML and JavaScript.
Main prompt: Asked Copilot for help implementing the “Create an account” button in the HTML and connecting it to JavaScript so users could switch between login and registration modes.
How AI was used: Copilot explained the purpose of the HTML button with the id="switch-auth" and how JavaScript can use that ID to detect clicks and change the form between login and registration modes.
What was kept, changed, or rejected: Used the explanations to understand how the HTML button and JavaScript event listener work together to switch between the two modes.
What was learned: How HTML elements can be connected to JavaScript using IDs, how click event listeners work, and how a single form can switch between login and registration without requiring separate pages.


Session 4 — CSS Styling and Image Overlay

Tool: GitHub Copilot Chat
Task: Understand how to add a dark overlay over the travel background image on the TripMate login page.
Main prompt: Asked Copilot for help creating a dark overlay over a background image using CSS.
How AI was used: Copilot explained how to combine linear-gradient() with a background image and how rgba() values control the darkness and transparency of the overlay.
What was kept, changed, or rejected: Used the explanation to understand how the overlay works and how to adjust the background styling to achieve the desired appearance.
What was learned: How CSS background layers work, how transparency is controlled with rgba(), and how background-size: cover and background positioning affect the appearance of an image.

 Session 5 — General CSS and JavaScript Assistance

Tool: GitHub Copilot Chat
Task: Complete and understand parts of the CSS and JavaScript files during the development of TripMate.
Main prompt: Asked Copilot for help completing certain lines of CSS and JavaScript, understanding which properties and functions to use, and connecting HTML elements with the appropriate styling and functionality.
How AI was used: Copilot explained CSS properties, selectors, background styling, JavaScript variables, functions, event listeners, and DOM manipulation. It also suggested code for parts of the application and explained where the code should be placed.
What was kept, changed, or rejected: Used suggestions that matched the existing project structure and adapted the code to work with the HTML elements and IDs already present in TripMate. Some suggestions required adjustments to match the intended design and functionality.
What was learned: How CSS styling connects to HTML, how JavaScript interacts with page elements, and how to use AI explanations to understand code rather than copying unfamiliar solutions without reviewing them.

 Session 6 — Implementing Trip Creation

Tool: GitHub Copilot Chat
Task: Implement the functionality for creating and displaying trips on the dashboard.
Main prompt: Asked Copilot to generate JavaScript for handling the Create Trip buttons, reading form values, validating trip dates, storing trip information, and displaying created trips on the dashboard.
How AI was used: Copilot generated an implementation using arrays, objects, functions, event listeners, and DOM methods. The initial version needed adjustments to match the existing dashboard HTML.
What was kept, changed, or rejected: Reviewed the suggested code and adapted it to the existing HTML structure. The Create Trip buttons were connected to the same form, and the implementation was kept simple using an array to hold trip information during the current session.
What was learned: How to collect form values, create an object representing a trip, add it to an array, validate dates, and display information dynamically.

 Session 7 — Loading Views Without Refreshing the Page

Tool: GitHub Copilot Chat
Task: Implement navigation between TripMate views without refreshing the entire browser page.
Main prompt: Asked Copilot for help loading different HTML view fragments into the main application container and initializing the JavaScript functionality required by each view.
How AI was used: Copilot suggested using `fetch()` to load HTML fragments and `async/await` to handle the response. It also explained how URL hashes could be used for navigation between views.
What was kept, changed, or rejected: Retained the approach of loading HTML into the existing application container. Reviewed the implementation to ensure that functions associated with a particular page were called only when that view was loaded.
What was learned: How asynchronous JavaScript works with `fetch()`, how HTML fragments can be inserted into an existing page, and why page-specific JavaScript must account for elements that are not present in every view.

 Session 8 — Simplifying the JavaScript Implementation

Tool: GitHub Copilot Chat
Task: Make the JavaScript easier to understand.
Main prompt: Asked Copilot to simplify a previously suggested JavaScript implementation because it included additional helper functions and storage logic that were more complicated than necessary for the current development stage.
How AI was used: Copilot suggested a simpler approach using concepts covered in class, including variables, arrays, objects, functions, conditional statements, event listeners, and `fetch()`.
What was kept, changed, or rejected: Removed unnecessary storage helpers and browser storage from the beginner-level implementation. Kept trip information in a simple JavaScript array for the current prototype, leaving persistent storage for the planned PHP and MySQL backend.
What was learned: How to choose an implementation that matches the concepts learned in class, why simpler code can be easier to maintain and explain, and why an in-memory array cannot replace persistent database storage.

Where the AI Was Wrong or Needed Correction

Issue 1 — JavaScript Error Between Pages

Tool: GitHUb Copilot Chat
Task: Understand and debug shared JavaScript used by the login page and dashboard.
What was wrong: Authentication-related JavaScript attempted to access login form elements on the dashboard, where those elements did not exist.
How it was noticed: A JavaScript error occurred while working with the dashboard.
How it was fixed: The authentication-related code was guarded so it only runs when the login form exists.
What was learned: When JavaScript is shared between multiple pages, page-specific elements must be checked before accessing them or attaching event listeners.

Issue 2 — JavaScript Code Did Not Match the Existing HTML

Tool: GitHub Copilot Chat
Task: Implement the functionality for creating and displaying trips on the TripMate dashboard.
What was wrong: The suggested JavaScript relied on HTML element IDs and page elements that did not necessarily match the existing dashboard HTML. As a result, some functionality could fail if the expected elements were missing or had different IDs.
How it was noticed: While reviewing the generated code, it became clear that the JavaScript selectors needed to match the IDs used in the actual HTML files.
How it was fixed: The code was checked against the existing HTML, and the element IDs and event listeners were adjusted to match the project structure.
What was learned: AI-generated code must be checked against the existing project instead of being copied directly. HTML and JavaScript must use matching element IDs for event listeners and DOM manipulation to work correctly.

Issue 3 — JavaScript Implementation Was More Complicated Than Necessary

Tool: GitHub Copilot Chat
Task: Simplify the JavaScript implementation for TripMate while keeping the required functionality understandable.
What was wrong: Copilot initially suggested a more complicated implementation that included browser storage, additional helper functions, and extra code that was not necessary for the current prototype.
How it was noticed: The implementation included concepts that had not yet been covered in class and made the code harder to understand and explain.
How it was fixed: The implementation was simplified to use variables, arrays, objects, functions, conditional statements, event listeners, and fetch(). Browser storage and unnecessary helper functions were removed from the current version.
What was learned: Code should be appropriate for the project's current stage and the developer's understanding. A simpler implementation can be easier to explain and maintain, while persistent storage can be implemented later using the planned PHP and MySQL backend.

Future entries:
Add entries as AI assistanceis used.