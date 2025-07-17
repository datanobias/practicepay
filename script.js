/**
 * Dental Practice Pay Survey - Frontend JavaScript
 * 
 * Handles form submission, validation, and chart rendering
 */

// State/Province data for dropdown population
const stateData = {
    USA: [
        'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA',
        'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD',
        'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ',
        'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC',
        'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
    ],
    Canada: [
        'AB', 'BC', 'MB', 'NB', 'NL', 'NS', 'ON', 'PE', 'QC', 'SK', 'NT', 'NU', 'YT'
    ]
};

// Global variables
let payChart = null;
let surveyData = [];

// DOM elements
const form = document.getElementById('surveyForm');
const countrySelect = document.getElementById('country');
const stateSelect = document.getElementById('state');
const formMessage = document.getElementById('form-message');
const loadingMessage = document.getElementById('loading-message');
const chartContainer = document.getElementById('chart-container');
const summaryStats = document.getElementById('summary-stats');

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    loadAveragesData();
});

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Form submission
    form.addEventListener('submit', handleFormSubmit);
    
    // Country change updates state dropdown
    countrySelect.addEventListener('change', populateStateDropdown);
    
    // Real-time validation
    form.querySelectorAll('input, select').forEach(element => {
        element.addEventListener('blur', validateField);
        element.addEventListener('input', clearFieldError);
    });
}

/**
 * Handle form submission
 */
async function handleFormSubmit(e) {
    e.preventDefault();
    
    // Validate form
    if (!validateForm()) {
        return;
    }
    
    // Disable submit button
    const submitBtn = form.querySelector('.submit-btn');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';
    
    try {
        // Prepare form data
        const formData = new FormData(form);
        
        // Submit to server
        const response = await fetch('submit.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showFormMessage('success', result.message);
            form.reset();
            clearAllErrors();
            
            // Reload averages data to include new submission
            setTimeout(loadAveragesData, 1000);
        } else {
            showFormMessage('error', result.error || 'An error occurred while submitting the survey.');
            
            // Show field-specific errors if available
            if (result.details) {
                showFieldErrors(result.details);
            }
        }
    } catch (error) {
        console.error('Submit error:', error);
        showFormMessage('error', 'Network error. Please check your connection and try again.');
    } finally {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
}

/**
 * Validate entire form
 */
function validateForm() {
    let isValid = true;
    
    // Clear previous errors
    clearAllErrors();
    
    // Validate each field
    const fields = ['name', 'email', 'role', 'country', 'state', 'hourly_rate'];
    fields.forEach(fieldName => {
        if (!validateField({ target: document.getElementById(fieldName) })) {
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Validate individual field
 */
function validateField(e) {
    const field = e.target;
    const value = field.value.trim();
    const fieldName = field.name;
    let isValid = true;
    let errorMessage = '';
    
    switch (fieldName) {
        case 'name':
            if (!value) {
                errorMessage = 'Name is required';
                isValid = false;
            } else if (value.length > 100) {
                errorMessage = 'Name must be less than 100 characters';
                isValid = false;
            }
            break;
            
        case 'email':
            if (!value) {
                errorMessage = 'Email is required';
                isValid = false;
            } else if (!isValidEmail(value)) {
                errorMessage = 'Please enter a valid email address';
                isValid = false;
            } else if (value.length > 150) {
                errorMessage = 'Email must be less than 150 characters';
                isValid = false;
            }
            break;
            
        case 'role':
            if (!value) {
                errorMessage = 'Role is required';
                isValid = false;
            }
            break;
            
        case 'country':
            if (!value) {
                errorMessage = 'Country is required';
                isValid = false;
            }
            break;
            
        case 'state':
            if (!value) {
                errorMessage = 'State/Province is required';
                isValid = false;
            }
            break;
            
        case 'hourly_rate':
            if (!value) {
                errorMessage = 'Hourly rate is required';
                isValid = false;
            } else if (isNaN(value) || parseFloat(value) < 0) {
                errorMessage = 'Hourly rate must be a positive number';
                isValid = false;
            } else if (parseFloat(value) > 999.99) {
                errorMessage = 'Hourly rate must be less than $1000';
                isValid = false;
            }
            break;
    }
    
    if (!isValid) {
        showFieldError(fieldName, errorMessage);
    } else {
        clearFieldError({ target: field });
    }
    
    return isValid;
}

/**
 * Validate email format
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Show field error
 */
function showFieldError(fieldName, message) {
    const field = document.getElementById(fieldName);
    const errorElement = document.getElementById(fieldName + '-error');
    
    field.parentElement.classList.add('error');
    errorElement.textContent = message;
    errorElement.classList.add('show');
}

/**
 * Show multiple field errors
 */
function showFieldErrors(errors) {
    errors.forEach(error => {
        // Try to map error to specific field
        const lowerError = error.toLowerCase();
        if (lowerError.includes('name')) showFieldError('name', error);
        else if (lowerError.includes('email')) showFieldError('email', error);
        else if (lowerError.includes('role')) showFieldError('role', error);
        else if (lowerError.includes('state')) showFieldError('state', error);
        else if (lowerError.includes('country')) showFieldError('country', error);
        else if (lowerError.includes('rate')) showFieldError('hourly_rate', error);
    });
}

/**
 * Clear field error
 */
function clearFieldError(e) {
    const field = e.target;
    const fieldName = field.name;
    const errorElement = document.getElementById(fieldName + '-error');
    
    field.parentElement.classList.remove('error');
    errorElement.textContent = '';
    errorElement.classList.remove('show');
}

/**
 * Clear all field errors
 */
function clearAllErrors() {
    document.querySelectorAll('.form-group.error').forEach(group => {
        group.classList.remove('error');
    });
    
    document.querySelectorAll('.error-message.show').forEach(error => {
        error.textContent = '';
        error.classList.remove('show');
    });
}

/**
 * Show form message
 */
function showFormMessage(type, message) {
    formMessage.className = `form-message ${type}`;
    formMessage.textContent = message;
    
    // Auto-hide success messages after 5 seconds
    if (type === 'success') {
        setTimeout(() => {
            formMessage.style.display = 'none';
        }, 5000);
    }
}

/**
 * Populate state dropdown based on country selection
 */
function populateStateDropdown() {
    const country = countrySelect.value;
    const states = stateData[country] || [];
    
    // Clear existing options
    stateSelect.innerHTML = '<option value="">Select state/province</option>';
    
    // Add states for selected country
    states.forEach(state => {
        const option = document.createElement('option');
        option.value = state;
        option.textContent = state;
        stateSelect.appendChild(option);
    });
    
    // Clear state field error if country is selected
    if (country) {
        clearFieldError({ target: stateSelect });
    }
}

/**
 * Load averages data from server
 */
async function loadAveragesData() {
    try {
        const response = await fetch('averages.php');
        const result = await response.json();
        
        if (result.data && result.data.length > 0) {
            surveyData = result.data;
            renderChart();
            renderSummaryStats(result.summary);
        } else {
            showNoDataMessage();
        }
    } catch (error) {
        console.error('Error loading averages:', error);
        showErrorMessage('Unable to load survey data. Please try again later.');
    }
}

/**
 * Render the pay chart
 */
function renderChart() {
    loadingMessage.style.display = 'none';
    chartContainer.style.display = 'block';
    
    const ctx = document.getElementById('payChart').getContext('2d');
    
    // Prepare chart data
    const labels = surveyData.map(item => `${item.state}, ${item.country}`);
    const data = surveyData.map(item => item.avg_rate);
    const colors = surveyData.map(item => 
        item.country === 'USA' ? '#667eea' : '#764ba2'
    );
    
    // Destroy existing chart if it exists
    if (payChart) {
        payChart.destroy();
    }
    
    // Create new chart
    payChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Average Hourly Rate ($)',
                data: data,
                backgroundColor: colors,
                borderColor: colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Average Hourly Rates by Location'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const dataItem = surveyData[context.dataIndex];
                            return [
                                `Average Rate: $${dataItem.avg_rate}`,
                                `Based on ${dataItem.survey_count} survey(s)`
                            ];
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Hourly Rate ($)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Location'
                    }
                }
            }
        }
    });
}

/**
 * Render summary statistics
 */
function renderSummaryStats(summary) {
    if (!summary) return;
    
    summaryStats.innerHTML = `
        <div class="stat-card">
            <span class="stat-value">${summary.total_surveys}</span>
            <span class="stat-label">Total Surveys</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">$${summary.overall_average}</span>
            <span class="stat-label">Overall Average</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">${summary.locations_count}</span>
            <span class="stat-label">Locations</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">$${summary.min_rate} - $${summary.max_rate}</span>
            <span class="stat-label">Range</span>
        </div>
    `;
}

/**
 * Show no data message
 */
function showNoDataMessage() {
    loadingMessage.textContent = 'No survey data available yet. Be the first to submit!';
    loadingMessage.className = 'loading';
}

/**
 * Show error message
 */
function showErrorMessage(message) {
    loadingMessage.textContent = message;
    loadingMessage.className = 'loading error';
}
