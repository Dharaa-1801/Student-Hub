document.addEventListener('DOMContentLoaded', () => {
    const eventsContainer = document.getElementById('events-container');
    const searchInput = document.getElementById('search-input');
    const categoryFilter = document.getElementById('category-filter');
    const sortSelect = document.getElementById('sort-select');
    const loadingSpinner = document.getElementById('loading-spinner');
    const errorMessage = document.getElementById('error-message');
    const paginationControls = document.getElementById('pagination-controls');

    let allEvents = [];
    let currentPage = 1;
    const recordsPerPage = 6;

    // 1. Fetch JSON Data using Fetch API with loading & error handling
    function fetchEvents() {
        loadingSpinner.style.display = 'block';
        errorMessage.style.display = 'none';

        fetch('events.json')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load JSON data.');
                }
                return response.json();
            })
            .then(data => {
                allEvents = data;
                loadingSpinner.style.display = 'none';
                renderAndPaginate();
            })
            .catch(error => {
                loadingSpinner.style.display = 'none';
                errorMessage.textContent = 'Error: ' + error.message;
                errorMessage.style.display = 'block';
            });
    }

    // 2. Filter, Search, and Sort Logic
    function getProcessedEvents() {
        let filtered = [...allEvents];

        // Search query filter
        const query = searchInput.value.toLowerCase().trim();
        if (query) {
            filtered = filtered.filter(event => event.title.toLowerCase().includes(query));
        }

        // Category filter
        const category = categoryFilter.value;
        if (category !== 'All') {
            filtered = filtered.filter(event => event.category === category);
        }

        // Sorting logic using array sort
        const sortBy = sortSelect.value;
        if (sortBy === 'title-asc') {
            filtered.sort((a, b) => a.title.localeCompare(b.title));
        } else if (sortBy === 'date-asc') {
            filtered.sort((a, b) => new Date(a.date) - new Date(b.date));
        }

        return filtered;
    }

    // 3. Render Events with Pagination
    function renderAndPaginate() {
        const processedEvents = getProcessedEvents();
        
        // Pagination calculation
        const totalPages = Math.ceil(processedEvents.length / recordsPerPage) || 1;
        if (currentPage > totalPages) currentPage = totalPages;

        const startIndex = (currentPage - 1) * recordsPerPage;
        const paginatedEvents = processedEvents.slice(startIndex, startIndex + recordsPerPage);

        // Render Cards using map method
        eventsContainer.innerHTML = '';
        if (paginatedEvents.length === 0) {
            eventsContainer.innerHTML = '<p style="grid-column: 1/-1; text-align: center;">No events found.</p>';
        } else {
            paginatedEvents.forEach(event => {
                const card = document.createElement('div');
                card.style.cssText = "background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);";
                card.innerHTML = `
                    <span style="background: #e0e7ff; color: #3730a3; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">${event.category}</span>
                    <h3 style="margin: 8px 0; color: #1e1b4b; font-size: 1.1rem;">${event.title}</h3>
                    <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 8px;"><strong>Date:</strong> ${event.date}</p>
                    <p style="color: #334155; font-size: 0.9rem;">${event.description}</p>
                `;
                eventsContainer.appendChild(card);
            });
        }

        renderPaginationButtons(totalPages);
    }

    // 4. Pagination Buttons Generator
    function renderPaginationButtons(totalPages) {
        paginationControls.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.style.cssText = `padding: 6px 12px; border: 1px solid #ccc; background: ${i === currentPage ? '#4f46e5' : '#fff'}; color: ${i === currentPage ? '#fff' : '#000'}; border-radius: 4px; cursor: pointer;`;
            btn.addEventListener('click', () => {
                currentPage = i;
                renderAndPaginate();
            });
            paginationControls.appendChild(btn);
        }
    }

    // Event Listeners for interactive search, filter, and sorting
    searchInput.addEventListener('input', () => { currentPage = 1; renderAndPaginate(); });
    categoryFilter.addEventListener('change', () => { currentPage = 1; renderAndPaginate(); });
    sortSelect.addEventListener('change', () => { currentPage = 1; renderAndPaginate(); });

    // Initial Fetch call
    fetchEvents();
});