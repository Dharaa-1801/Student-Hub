document.addEventListener('DOMContentLoaded', () => {
    const section = document.getElementById('events');
    const form = document.getElementById('event-api-form');
    const tableBody = document.getElementById('event-api-table-body');
    const status = document.getElementById('event-api-status');
    if (!section || !form || !tableBody || !status) return;

    const setStatus = (message, isError = false) => {
        status.textContent = message;
        status.className = `admin-alert ${isError ? 'error' : 'success'}`;
        status.hidden = message === '';
    };

    const request = async (url, options = {}) => {
        const response = await fetch(url, {
            ...options,
            headers: { 'Content-Type': 'application/json', ...(options.headers || {}) }
        });
        const result = await response.json();
        if (!response.ok || !result.success) {
            throw new Error(result.message || 'The request failed.');
        }
        return result;
    };

    const input = (name, value, type = 'text') => {
        const element = document.createElement('input');
        element.name = name;
        element.value = value || '';
        element.type = type;
        element.required = name !== 'description';
        return element;
    };

    const renderEvents = (events) => {
        tableBody.replaceChildren();
        if (events.length === 0) {
            const row = document.createElement('tr');
            const cell = document.createElement('td');
            cell.colSpan = 5;
            cell.textContent = 'No events added yet.';
            row.appendChild(cell);
            tableBody.appendChild(row);
            return;
        }

        events.forEach((event) => {
            const row = document.createElement('tr');
            const values = [event.title, event.category, event.event_date];
            if (event.poster_path) {
                const posterCell = document.createElement('td');
                const image = document.createElement('img');
                image.src = event.poster_path;
                image.alt = `${event.title} poster`;
                image.className = 'event-poster-thumb';
                posterCell.appendChild(image);
                row.appendChild(posterCell);
            } else {
                const posterCell = document.createElement('td');
                posterCell.textContent = 'No poster';
                row.appendChild(posterCell);
            }
            values.forEach((value) => {
                const cell = document.createElement('td');
                cell.textContent = value || '';
                row.appendChild(cell);
            });

            const actions = document.createElement('td');
            const edit = document.createElement('button');
            edit.className = 'btn btn-light';
            edit.type = 'button';
            edit.textContent = 'Edit';
            edit.addEventListener('click', () => showEditForm(row, event));
            const remove = document.createElement('button');
            remove.className = 'btn btn-danger';
            remove.type = 'button';
            remove.textContent = 'Delete';
            remove.addEventListener('click', async () => {
                if (!window.confirm('Delete this event?')) return;
                try {
                    await request(`api.php?id=${event.id}`, { method: 'DELETE' });
                    setStatus('Event deleted successfully.');
                    await loadEvents();
                } catch (error) {
                    setStatus(error.message, true);
                }
            });
            actions.append(edit, remove);
            row.appendChild(actions);
            tableBody.appendChild(row);
        });
    };

    const showEditForm = (row, event) => {
        row.replaceChildren();
        const cell = document.createElement('td');
        cell.colSpan = 5;
        const editForm = document.createElement('form');
        editForm.className = 'admin-form';
        editForm.append(input('title', event.title), input('category', event.category), input('event_date', event.event_date, 'date'), input('description', event.description));
        const save = document.createElement('button');
        save.className = 'btn btn-primary';
        save.type = 'submit';
        save.textContent = 'Save changes';
        editForm.appendChild(save);
        editForm.addEventListener('submit', async (submitEvent) => {
            submitEvent.preventDefault();
            const data = Object.fromEntries(new FormData(editForm));
            try {
                await request(`api.php?id=${event.id}`, { method: 'PUT', body: JSON.stringify(data) });
                setStatus('Event updated successfully.');
                await loadEvents();
            } catch (error) {
                setStatus(error.message, true);
            }
        });
        cell.appendChild(editForm);
        row.appendChild(cell);
    };

    const loadEvents = async () => {
        setStatus('Loading events...');
        try {
            const result = await request('api.php');
            renderEvents(result.data);
            setStatus('');
        } catch (error) {
            setStatus(error.message, true);
        }
    };

    form.addEventListener('submit', async (event) => {
        if (form.querySelector('input[type="file"]').files.length > 0) {
            return;
        }
        event.preventDefault();
        const data = Object.fromEntries(new FormData(form));
        delete data.action;
        try {
            await request('api.php', { method: 'POST', body: JSON.stringify(data) });
            form.reset();
            setStatus('Event created successfully.');
            await loadEvents();
        } catch (error) {
            setStatus(error.message, true);
        }
    });

    loadEvents();
});
