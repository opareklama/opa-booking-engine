<div class="wrap">
    <h1>Pricing Rules Engine</h1>
    <p>Map Cities, Waste Types, and Containers to establish a base price.</p>
    
    <div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); margin-top: 20px;">
        <h2>Add New Rule</h2>
        <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
            <select id="pr_city" style="flex: 1;"><option value="">Select City...</option></select>
            <select id="pr_waste" style="flex: 1;"><option value="">Select Waste Type...</option></select>
            <select id="pr_container" style="flex: 1;"><option value="">Select Container...</option></select>
            <input type="number" id="pr_price" placeholder="Base Price ($)" step="0.01" style="width: 120px;">
            <button type="button" class="button button-primary" id="btn_add_rule">Save Rule</button>
        </div>

        <table class="wp-list-table widefat fixed striped" id="table_pricing_rules">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>City</th>
                    <th>Waste Type</th>
                    <th>Container</th>
                    <th>Base Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="6">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    const nonce = '<?php echo wp_create_nonce('opa_admin_nonce'); ?>';
    
    // Load Dropdowns
    function loadDropdown(action, selectId, textKey) {
        fetch(ajaxurl + '?action=' + action + '&_wpnonce=' + nonce)
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    const sel = document.getElementById(selectId);
                    res.data.forEach(item => {
                        sel.innerHTML += `<option value="${item.id}">${item[textKey]}</option>`;
                    });
                }
            });
    }

    loadDropdown('opa_get_cities', 'pr_city', 'name');
    loadDropdown('opa_get_waste_types', 'pr_waste', 'title');
    loadDropdown('opa_get_containers', 'pr_container', 'title');

    // Load Rules Table
    function loadRules() {
        fetch(ajaxurl + '?action=opa_get_pricing_rules&_wpnonce=' + nonce)
            .then(res => res.json())
            .then(res => {
                const tbody = document.querySelector('#table_pricing_rules tbody');
                tbody.innerHTML = '';
                if(res.success && res.data.length > 0) {
                    res.data.forEach(rule => {
                        tbody.innerHTML += `<tr>
                            <td>${rule.id}</td>
                            <td>${rule.city}</td>
                            <td>${rule.waste_type}</td>
                            <td>${rule.container}</td>
                            <td>$${rule.base_price}</td>
                            <td>${rule.status}</td>
                        </tr>`;
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="6">No pricing rules configured yet.</td></tr>';
                }
            });
    }

    loadRules();

    // Add Rule
    document.getElementById('btn_add_rule').addEventListener('click', function() {
        const city = document.getElementById('pr_city').value;
        const waste = document.getElementById('pr_waste').value;
        const container = document.getElementById('pr_container').value;
        const price = document.getElementById('pr_price').value;

        if(!city || !waste || !container || !price) {
            alert('Please select all options and enter a price.');
            return;
        }

        const fd = new FormData();
        fd.append('action', 'opa_add_pricing_rule');
        fd.append('_wpnonce', nonce);
        fd.append('city_id', city);
        fd.append('waste_type_id', waste);
        fd.append('container_id', container);
        fd.append('base_price', price);

        fetch(ajaxurl, { method: 'POST', body: fd })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                document.getElementById('pr_price').value = '';
                loadRules();
            } else {
                alert('Error: ' + res.data); // Unique constraint error mostly
            }
        });
    });
});
</script>
