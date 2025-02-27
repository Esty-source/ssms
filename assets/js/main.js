// Main JavaScript file for Safe Lock Storage Management System

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // AJAX functions for dynamic content loading
    window.loadAvailableContainers = function(size = '') {
        fetch(`${BASE_URL}api/containers.php?size=${size}`)
            .then(response => response.json())
            .then(data => {
                // Update containers display
                const containerGrid = document.getElementById('container-grid');
                if (containerGrid) {
                    containerGrid.innerHTML = data.map(container => `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">${container.size}</h5>
                                    <p class="card-text">Location: ${container.location}</p>
                                    <p class="card-text">Price: £${container.price}/month</p>
                                    <button class="btn btn-primary" 
                                            onclick="bookContainer(${container.id})"
                                            ${container.status !== 'available' ? 'disabled' : ''}>
                                        ${container.status === 'available' ? 'Book Now' : 'Unavailable'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }
            })
            .catch(error => console.error('Error loading containers:', error));
    };

    // Booking function
    window.bookContainer = function(containerId) {
        // Redirect to booking page with container ID
        window.location.href = `${BASE_URL}booking.php?container_id=${containerId}`;
    };

    // Moving service request form handler
    const movingForm = document.getElementById('moving-service-form');
    if (movingForm) {
        movingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(`${BASE_URL}api/moving-request.php`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Moving service request submitted successfully!');
                    movingForm.reset();
                } else {
                    alert('Error submitting request: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting your request.');
            });
        });
    }

    // Initialize date pickers
    const datePickers = document.querySelectorAll('.datepicker');
    datePickers.forEach(picker => {
        // Add your preferred date picker initialization here
        // Example: new Datepicker(picker, {...options});
    });
});
