<div class="wrap">
    <h1>Master Data Management</h1>
    <p>Manage Cities, Waste Types, and Containers here.</p>
    
    <div style="display: flex; gap: 20px; margin-top: 20px; flex-wrap: wrap;">
        <!-- Cities Section -->
        <div style="flex: 1; min-width: 300px; background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
            <h2>Cities</h2>
            <div style="margin-bottom: 15px; display: flex; gap: 10px;">
                <input type="text" id="opa_city_name" placeholder="Enter city name" style="flex: 1;">
                <button type="button" class="button button-primary" id="opa_btn_add_city">Add</button>
            </div>
            <table class="wp-list-table widefat fixed striped" id="opa_cities_table">
                <thead><tr><th>ID</th><th>Name</th><th>Status</th></tr></thead>
                <tbody><tr><td colspan="3">Loading...</td></tr></tbody>
            </table>
        </div>

        <!-- Waste Types Section -->
        <div style="flex: 1; min-width: 300px; background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
            <h2>Waste Types</h2>
            <div style="margin-bottom: 15px; display: flex; gap: 10px;">
                <input type="text" id="opa_waste_title" placeholder="Waste Type (e.g. Green Waste)" style="flex: 1;">
                <button type="button" class="button button-primary" id="opa_btn_add_waste">Add</button>
            </div>
            <table class="wp-list-table widefat fixed striped" id="opa_waste_table">
                <thead><tr><th>ID</th><th>Title</th><th>Status</th></tr></thead>
                <tbody><tr><td colspan="3">Loading...</td></tr></tbody>
            </table>
        </div>

        <!-- Containers Section -->
        <div style="flex: 1; min-width: 300px; background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
            <h2>Containers</h2>
            <div style="margin-bottom: 15px; display: flex; gap: 10px;">
                <input type="text" id="opa_container_title" placeholder="Title (e.g. Small Bin)" style="flex: 1;">
                <input type="text" id="opa_container_size" placeholder="Size (e.g. 5m3)" style="width: 80px;">
                <button type="button" class="button button-primary" id="opa_btn_add_container">Add</button>
            </div>
            <table class="wp-list-table widefat fixed striped" id="opa_container_table">
                <thead><tr><th>ID</th><th>Title</th><th>Size</th><th>Status</th></tr></thead>
                <tbody><tr><td colspan="4">Loading...</td></tr></tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    const nonce = '<?php echo wp_create_nonce('opa_admin_nonce'); ?>';
    
    function loadData(action, tableId, renderRow) {
        fetch(ajaxurl + '?action=' + action + '&_wpnonce=' + nonce)
            .then(res => res.json())
            .then(res => {
                const tbody = document.querySelector('#' + tableId + ' tbody');
                tbody.innerHTML = '';
                if(res.success && res.data.length > 0) {
                    res.data.forEach(item => {
                        tbody.innerHTML += renderRow(item);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="5">No records found.</td></tr>';
                }
            });
    }

    function addData(action, btnId, getFormData, onSuccess) {
        document.getElementById(btnId).addEventListener('click', function() {
            const formData = getFormData();
            if(!formData) return;
            formData.append('action', action);
            formData.append('_wpnonce', nonce);

            fetch(ajaxurl, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if(res.success) { onSuccess(); } else { alert(res.data); }
            });
        });
    }

    // Cities
    const renderCity = (city) => `<tr><td>${city.id}</td><td>${city.name}</td><td>${city.status}</td></tr>`;
    loadData('opa_get_cities', 'opa_cities_table', renderCity);
    addData('opa_add_city', 'opa_btn_add_city', () => {
        const input = document.getElementById('opa_city_name');
        if(!input.value.trim()) return null;
        const fd = new FormData(); fd.append('city_name', input.value.trim());
        input.value = ''; return fd;
    }, () => loadData('opa_get_cities', 'opa_cities_table', renderCity));

    // Waste Types
    const renderWaste = (waste) => `<tr><td>${waste.id}</td><td>${waste.title}</td><td>${waste.status}</td></tr>`;
    loadData('opa_get_waste_types', 'opa_waste_table', renderWaste);
    addData('opa_add_waste_type', 'opa_btn_add_waste', () => {
        const input = document.getElementById('opa_waste_title');
        if(!input.value.trim()) return null;
        const fd = new FormData(); fd.append('title', input.value.trim());
        input.value = ''; return fd;
    }, () => loadData('opa_get_waste_types', 'opa_waste_table', renderWaste));

    // Containers
    const renderContainer = (cont) => `<tr><td>${cont.id}</td><td>${cont.title}</td><td>${cont.size}</td><td>${cont.status}</td></tr>`;
    loadData('opa_get_containers', 'opa_container_table', renderContainer);
    addData('opa_add_container', 'opa_btn_add_container', () => {
        const title = document.getElementById('opa_container_title');
        const size = document.getElementById('opa_container_size');
        if(!title.value.trim() || !size.value.trim()) return null;
        const fd = new FormData(); fd.append('title', title.value.trim()); fd.append('size', size.value.trim());
        title.value = ''; size.value = ''; return fd;
    }, () => loadData('opa_get_containers', 'opa_container_table', renderContainer));
});
</script>
