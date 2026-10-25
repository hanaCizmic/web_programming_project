let trips = [];
let currentTrip = null;
let activities = [];
let editingTrip = null;
let expenses = [];
let packingItems = [];

// Load a page into the app without refreshing the browser
async function loadView(viewName) {
    const app = document.getElementById("app");

    try {
        const response = await fetch(`views/${viewName}.html`);

        if (!response.ok) {
            throw new Error("Page could not be loaded");
        }

        const html = await response.text();
        app.innerHTML = html;

        // Set up the functionality for the page we loaded
        
        if (viewName === "login") {
            initAuth();
        } else if (viewName === "dashboard") {
            initDashboard();
        } else if (viewName === "trip-details") {
            initTripDetails();
        } else if (viewName === "expenses") {
            initExpenses();
        } else if (viewName === "packing-list") {
            initPackingList();
        }


        initLogout();
    } catch (error) {
        app.innerHTML = "<p>Sorry, something went wrong loading TripMate.</p>";
        console.error(error);
    }
}

// Navigate to another page
function navigateTo(viewName) {
    if (location.hash === "#" + viewName) {
        loadView(viewName);
    } else {
        location.hash = viewName;
    }
}

// Load the page whenever the URL hash changes
window.addEventListener("hashchange", function () {
    const viewName = location.hash.substring(1);

    if (viewName) {
        loadView(viewName);
    }
});

// LOGIN AND REGISTRATION

function initAuth() {
    const authForm = document.getElementById("auth-form");

    if (!authForm) {
        return;
    }

    const loginTab = document.getElementById("login-tab");
    const registerTab = document.getElementById("register-tab");
    const nameField = document.getElementById("name-field");
    const nameInput = document.getElementById("name");
    const passwordInput = document.getElementById("password");
    const submitButton = authForm.querySelector('button[type="submit"]');
    const switchAuthButton = document.getElementById("switch-auth");
    const authMessage = document.getElementById("auth-message");

    let isRegistering = false;

    function setAuthMode(registering) {
        isRegistering = registering;

        loginTab.classList.toggle("active", !registering);
        registerTab.classList.toggle("active", registering);

        nameField.hidden = !registering;
        nameInput.required = registering;

        passwordInput.autocomplete = registering
            ? "new-password"
            : "current-password";

        submitButton.textContent = registering
            ? "Create account"
            : "Log in";

        switchAuthButton.textContent = registering
            ? "Log in instead"
            : "Create an account";

        authMessage.textContent = "";
    }

    loginTab.addEventListener("click", function () {
        setAuthMode(false);
    });

    registerTab.addEventListener("click", function () {
        setAuthMode(true);
    });

    switchAuthButton.addEventListener("click", function () {
        setAuthMode(!isRegistering);
    });

    authForm.addEventListener("submit", function (event) {
        event.preventDefault();

        // This is a demo until we connect the PHP backend
        authMessage.textContent = "Welcome to TripMate!";

        navigateTo("dashboard");
    });

    setAuthMode(false);
}

// DASHBOARD AND TRIPS

function initDashboard() {
    const createTripButton = document.getElementById("create-trip-btn");
    const emptyCreateButton = document.getElementById("empty-create-trip-btn");
    const tripDialog = document.getElementById("trip-dialog");
    const cancelTripButton = document.getElementById("cancel-trip-btn");
    const tripForm = document.getElementById("trip-form");

    // Show the create-trip form
    if (createTripButton && tripDialog) {
        createTripButton.addEventListener("click", function () {
            tripDialog.showModal();
        });
    }

    if (emptyCreateButton && tripDialog) {
        emptyCreateButton.addEventListener("click", function () {
            tripDialog.showModal();
        });
    }

    // Close the form
    if (cancelTripButton && tripDialog) {
        cancelTripButton.addEventListener("click", function () {
            tripDialog.close();
        });
    }

    // Save a new trip in the trips array
    if (tripForm) {
        tripForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const tripName = document.getElementById("trip-name").value.trim();
            const destination = document.getElementById("destination").value.trim();
            const startDate = document.getElementById("start-date").value;
            const endDate = document.getElementById("end-date").value;

            if (endDate < startDate) {
                alert("The end date cannot be before the start date.");
                return;
            }

            const trip = {
                id: trips.length + 1,
                name: tripName,
                destination: destination,
                startDate: startDate,
                endDate: endDate
            };

            trips.push(trip);

            tripForm.reset();
            tripDialog.close();

            displayTrips();
        });
    }

    displayTrips();
}

// Display the trips on the dashboard

function displayTrips() {
    const tripList = document.getElementById("trips-list");
    const emptyState = document.getElementById("empty-state");

    if (!tripList || !emptyState) {
        return;
    }

    tripList.innerHTML = "";

    if (trips.length === 0) {
        emptyState.hidden = false;
        return;
    }

    emptyState.hidden = true;

    for (let i = 0; i < trips.length; i++) {
        const trip = trips[i];

        const tripCard = document.createElement("div");
        tripCard.className = "trip-card";

        const tripTitle = document.createElement("h3");
        tripTitle.textContent = trip.name;

        const tripDestination = document.createElement("p");
        tripDestination.textContent = trip.destination;

        const tripDates = document.createElement("p");
        tripDates.textContent = trip.startDate + " – " + trip.endDate;

        const detailsButton = document.createElement("button");
        detailsButton.type = "button";
        detailsButton.textContent = "View trip";

        detailsButton.addEventListener("click", function () {
            currentTrip = trip;
            navigateTo("trip-details");
        });

        tripCard.appendChild(tripTitle);
        tripCard.appendChild(tripDestination);
        tripCard.appendChild(tripDates);
        tripCard.appendChild(detailsButton);

        tripList.appendChild(tripCard);
    }
}


// TRIP DETAILS


function initTripDetails() {
    if (!currentTrip) {
        navigateTo("dashboard");
        return;
    }

    document.getElementById("trip-name").textContent = currentTrip.name;
    document.getElementById("trip-destination").textContent = currentTrip.destination;
    document.getElementById("trip-start-date").textContent = currentTrip.startDate;
    document.getElementById("trip-end-date").textContent = currentTrip.endDate;

    
    const editButton = document.getElementById("edit-trip-button");
    const editDialog = document.getElementById("edit-trip-dialog");
    const editForm = document.getElementById("edit-trip-form");
    const cancelEditButton = document.getElementById("cancel-edit-trip");

    editButton.addEventListener("click", function () {
        document.getElementById("edit-trip-name").value = currentTrip.name;
        document.getElementById("edit-destination").value = currentTrip.destination;
        document.getElementById("edit-start-date").value = currentTrip.startDate;
        document.getElementById("edit-end-date").value = currentTrip.endDate;

        editDialog.showModal();
    });

    cancelEditButton.addEventListener("click", function () {
        editDialog.close();
    });

    editForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const startDate = document.getElementById("edit-start-date").value;
        const endDate = document.getElementById("edit-end-date").value;

        if (endDate < startDate) {
            alert("The end date cannot be before the start date.");
            return;
        }

        currentTrip.name = document.getElementById("edit-trip-name").value.trim();
        currentTrip.destination = document.getElementById("edit-destination").value.trim();
        currentTrip.startDate = startDate;
        currentTrip.endDate = endDate;

        editDialog.close();

        document.getElementById("trip-name").textContent = currentTrip.name;
        document.getElementById("trip-destination").textContent = currentTrip.destination;
        document.getElementById("trip-start-date").textContent = currentTrip.startDate;
        document.getElementById("trip-end-date").textContent = currentTrip.endDate;
    });

    
    const deleteButton = document.getElementById("delete-trip-button");

    deleteButton.addEventListener("click", function () {
        const confirmDelete = confirm(
            "Are you sure you want to delete this trip?"
        );

        if (confirmDelete) {
            for (let i = 0; i < trips.length; i++) {
                if (trips[i].id === currentTrip.id) {
                    trips.splice(i, 1);
                    break;
                }
            }

            activities = activities.filter(function (activity) {
                return activity.tripId !== currentTrip.id;
            });

            currentTrip = null;
            navigateTo("dashboard");
        }
    });


    const addButton = document.getElementById("add-activity-button");
    const activityDialog = document.getElementById("activity-dialog");
    const cancelButton = document.getElementById("cancel-activity-button");
    const activityForm = document.getElementById("activity-form");

    addButton.addEventListener("click", function () {
        activityDialog.showModal();
    });

    cancelButton.addEventListener("click", function () {
        activityDialog.close();
    });

    activityForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const activity = {
            id: activities.length + 1,
            tripId: currentTrip.id,
            title: document.getElementById("activity-title").value,
            date: document.getElementById("activity-date").value,
            time: document.getElementById("activity-time").value,
            location: document.getElementById("activity-location").value,
            notes: document.getElementById("activity-notes").value
        };

        activities.push(activity);

        activityForm.reset();
        activityDialog.close();

        displayActivities();
    });

    displayActivities();
}


function displayActivities() {
    const itineraryList = document.getElementById("itinerary-list");
    const emptyItinerary = document.getElementById("empty-itinerary");

    if (!itineraryList || !emptyItinerary) {
        return;
    }

    itineraryList.innerHTML = "";

    const tripActivities = [];

    for (let i = 0; i < activities.length; i++) {
        if (activities[i].tripId === currentTrip.id) {
            tripActivities.push(activities[i]);
        }
    }

    if (tripActivities.length === 0) {
        emptyItinerary.hidden = false;
        return;
    }

    emptyItinerary.hidden = true;

    for (let i = 0; i < tripActivities.length; i++) {
        const activity = tripActivities[i];

        const card = document.createElement("div");
        card.className = "activity-card";

        const title = document.createElement("h3");
        title.textContent = activity.title;

        const date = document.createElement("p");
        date.textContent = "Date: " + activity.date;

        card.appendChild(title);
        card.appendChild(date);

        if (activity.time) {
            const time = document.createElement("p");
            time.textContent = "Time: " + activity.time;
            card.appendChild(time);
        }

        if (activity.location) {
            const location = document.createElement("p");
            location.textContent = "Location: " + activity.location;
            card.appendChild(location);
        }

        if (activity.notes) {
            const notes = document.createElement("p");
            notes.textContent = activity.notes;
            card.appendChild(notes);
        }

        itineraryList.appendChild(card);
    }
}


// LOG OUT

function initLogout() {
    const logoutButton = document.getElementById("logout-button");

    if (logoutButton) {
        logoutButton.addEventListener("click", function () {
            navigateTo("login");
        });
    }
}


function initExpenses() {
    const addButton = document.getElementById("add-expense-button");
    const dialog = document.getElementById("expense-dialog");
    const cancelButton = document.getElementById("cancel-expense-button");
    const form = document.getElementById("expense-form");
    const tripSelect = document.getElementById("expense-trip");
    const tripFilter = document.getElementById("expense-trip-filter");

    // Fill both dropdowns with the trips the user created
    tripSelect.innerHTML = '<option value="">Select a trip</option>';
    tripFilter.innerHTML = '<option value="">All trips</option>';

    for (let i = 0; i < trips.length; i++) {
        const tripOption = document.createElement("option");
        tripOption.value = trips[i].id;
        tripOption.textContent = trips[i].name;
        tripSelect.appendChild(tripOption);

        const filterOption = document.createElement("option");
        filterOption.value = trips[i].id;
        filterOption.textContent = trips[i].name;
        tripFilter.appendChild(filterOption);
    }

    addButton.addEventListener("click", function () {
        if (trips.length === 0) {
            alert("Please create a trip before adding an expense.");
            return;
        }

        dialog.showModal();
    });

    cancelButton.addEventListener("click", function () {
        dialog.close();
    });

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const amount = Number(document.getElementById("expense-amount").value);

        if (amount <= 0) {
            alert("Please enter an amount greater than zero.");
            return;
        }

        const expense = {
            id: expenses.length + 1,
            tripId: Number(tripSelect.value),
            title: document.getElementById("expense-title").value.trim(),
            amount: amount,
            currency: document.getElementById("expense-currency").value,
            category: document.getElementById("expense-category").value,
            date: document.getElementById("expense-date").value,
            notes: document.getElementById("expense-notes").value.trim()
        };

        expenses.push(expense);

        form.reset();
        dialog.close();

        displayExpenses();
    });

    tripFilter.addEventListener("change", function () {
        displayExpenses();
    });

    displayExpenses();
}

function displayExpenses() {
    const expenseList = document.getElementById("expenses-list");
    const emptyState = document.getElementById("expenses-empty-state");
    const totalSpent = document.getElementById("total-spent");
    const totalBudget = document.getElementById("total-budget");
    const remainingBudget = document.getElementById("remaining-budget");
    const tripFilter = document.getElementById("expense-trip-filter");

    if (!expenseList || !emptyState) {
        return;
    }

    expenseList.innerHTML = "";

    const selectedTrip = tripFilter.value;
    let total = 0;
    let visibleCount = 0;

    for (let i = 0; i < expenses.length; i++) {
        const expense = expenses[i];

        if (selectedTrip !== "" && expense.tripId !== Number(selectedTrip)) {
            continue;
        }

        visibleCount++;
        total += expense.amount;

        const row = document.createElement("article");
        row.className = "expense-card";

        const title = document.createElement("h3");
        title.textContent = expense.title;

        const details = document.createElement("p");
        details.textContent =
            expense.category.charAt(0).toUpperCase() +
            expense.category.slice(1) +
            " · " + expense.date;

        const amount = document.createElement("p");
        amount.textContent = expense.amount.toFixed(2) + " " + expense.currency;

        const trip = trips.find(function (item) {
            return item.id === expense.tripId;
        });

        const tripName = document.createElement("p");
        tripName.textContent = trip ? trip.name : "Trip unavailable";

        const deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.textContent = "Delete expense";

        deleteButton.addEventListener("click", function () {
            expenses = expenses.filter(function (item) {
                return item.id !== expense.id;
            });

            displayExpenses();
        });

        row.appendChild(title);
        row.appendChild(details);
        row.appendChild(amount);
        row.appendChild(tripName);

        if (expense.notes) {
            const notes = document.createElement("p");
            notes.textContent = expense.notes;
            row.appendChild(notes);
        }

        row.appendChild(deleteButton);
        expenseList.appendChild(row);
    }

    emptyState.hidden = visibleCount > 0;
    totalSpent.textContent = total.toFixed(2);

    // Budget is not implemented yet, so show a clear placeholder
    totalBudget.textContent = "Not set";
    remainingBudget.textContent = "Not calculated";
}


function initPackingList() {
    const addButton = document.getElementById("add-packing-item-button");
    const dialog = document.getElementById("packing-item-dialog");
    const cancelButton = document.getElementById("cancel-packing-button");
    const form = document.getElementById("packing-item-form");
    const tripSelect = document.getElementById("packing-trip");
    const tripFilter = document.getElementById("packing-trip-filter");

    // Fill the trip dropdowns
    tripSelect.innerHTML = '<option value="">Select a trip</option>';
    tripFilter.innerHTML = '<option value="">Select a trip</option>';

    for (let i = 0; i < trips.length; i++) {
        const option = document.createElement("option");
        option.value = trips[i].id;
        option.textContent = trips[i].name;
        tripSelect.appendChild(option);

        const filterOption = document.createElement("option");
        filterOption.value = trips[i].id;
        filterOption.textContent = trips[i].name;
        tripFilter.appendChild(filterOption);
    }

    // Open the form
    addButton.addEventListener("click", function () {
        if (trips.length === 0) {
            alert("Please create a trip before adding packing items.");
            return;
        }

        if (tripFilter.value) {
            tripSelect.value = tripFilter.value;
        }

        dialog.showModal();
    });

    // Close the form
    cancelButton.addEventListener("click", function () {
        dialog.close();
    });

    // Save a new packing item
    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const item = {
            id: packingItems.length + 1,
            tripId: Number(tripSelect.value),
            name: document.getElementById("packing-item-name").value.trim(),
            category: document.getElementById("packing-category").value,
            quantity: Number(document.getElementById("packing-quantity").value),
            notes: document.getElementById("packing-notes").value.trim(),
            packed: false
        };

        packingItems.push(item);

        form.reset();
        document.getElementById("packing-quantity").value = 1;
        dialog.close();

        displayPackingItems();
    });

    // Filter items by trip
    tripFilter.addEventListener("change", function () {
        displayPackingItems();
    });

    displayPackingItems();
}

function displayPackingItems() {
    const list = document.getElementById("packing-items-list");
    const emptyState = document.getElementById("packing-empty-state");
    const progressText = document.getElementById("packing-progress");
    const progressBar = document.getElementById("packing-progress-bar");
    const tripFilter = document.getElementById("packing-trip-filter");

    if (!list || !emptyState) {
        return;
    }

    list.innerHTML = "";

    const selectedTrip = tripFilter.value;
    let total = 0;
    let packedCount = 0;

    for (let i = 0; i < packingItems.length; i++) {
        const item = packingItems[i];

        if (selectedTrip === "" || item.tripId === Number(selectedTrip)) {
            total++;

            if (item.packed) {
                packedCount++;
            }

            const row = document.createElement("article");
            row.className = "packing-item-card";

            const checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.checked = item.packed;
            checkbox.id = "packed-item-" + item.id;

            const label = document.createElement("label");
            label.htmlFor = checkbox.id;
            label.textContent = item.name + " (Quantity: " + item.quantity + ")";

            if (item.packed) {
                label.style.textDecoration = "line-through";
            }

            checkbox.addEventListener("change", function () {
                item.packed = checkbox.checked;
                displayPackingItems();
            });

            const category = document.createElement("p");
            category.textContent = "Category: " + item.category;

            row.appendChild(checkbox);
            row.appendChild(label);
            row.appendChild(category);

            if (item.notes) {
                const notes = document.createElement("p");
                notes.textContent = item.notes;
                row.appendChild(notes);
            }

            const deleteButton = document.createElement("button");
            deleteButton.type = "button";
            deleteButton.textContent = "Delete";

            deleteButton.addEventListener("click", function () {
                packingItems = packingItems.filter(function (packingItem) {
                    return packingItem.id !== item.id;
                });

                displayPackingItems();
            });

            row.appendChild(deleteButton);
            list.appendChild(row);
        }
    }

    emptyState.hidden = total > 0;

    progressText.textContent = packedCount + " of " + total + " items packed";

    if (total > 0) {
        progressBar.value = (packedCount / total) * 100;
    } else {
        progressBar.value = 0;
    }
}



// START THE APPLICATION

const firstPage = location.hash.substring(1);

if (firstPage) {
    loadView(firstPage);
} else {
    loadView("login");
}
