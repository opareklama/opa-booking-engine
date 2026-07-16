<div class="opa-admin-wrapper">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
        <div>
            <div style="font-size: 12px; color: var(--opa-text-muted); margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">Opa Booking / Pricing</div>
            <h1 class="opa-page-title">Pricing Rules Engine</h1>
            <p class="opa-page-description">Map Cities, Waste Types, and Containers to establish a base price.</p>
        </div>
        <button class="opa-btn opa-btn-primary" onclick="window.OpaSlideOver.open('slideover-rule'); document.getElementById('form-rule').reset(); document.getElementById('field_rule_id').value = '';">
            + Add New Rule
        </button>
    </div>

    <!-- Filters & Search -->
    <div class="opa-card" style="margin-bottom: 24px; padding: 16px;">
        <div style="display: flex; gap: 16px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" id="filter_search" class="opa-input" placeholder="Search rules..." onkeyup="if(event.key === 'Enter') window.loadRules()">
            </div>
            <div style="width: 150px;">
                <select id="filter_city" class="opa-select" onchange="window.loadRules()">
                    <option value="">All Cities</option>
                </select>
            </div>
            <div style="width: 150px;">
                <select id="filter_waste" class="opa-select" onchange="window.loadRules()">
                    <option value="">All Waste Types</option>
                </select>
            </div>
            <div style="width: 150px;">
                <select id="filter_container" class="opa-select" onchange="window.loadRules()">
                    <option value="">All Containers</option>
                </select>
            </div>
            <div style="width: 150px;">
                <select id="filter_status" class="opa-select" onchange="window.loadRules()">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            <button class="opa-btn opa-btn-secondary" onclick="window.resetFilters()">Clear</button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="opa-card">
        <div class="opa-table-wrapper">
            <table class="opa-table" id="opa-table-rules">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>City</th>
                        <th>Waste Type</th>
                        <th>Container</th>
                        <th>Base Price</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="8" style="text-align: center;">Loading...</td></tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid var(--opa-border); margin-top: 16px;">
            <div style="font-size: 13px; color: var(--opa-text-muted);" id="pagination-info">
                Showing 0 results
            </div>
            <div style="display: flex; gap: 8px;" id="pagination-controls">
                <button class="opa-btn opa-btn-secondary" disabled>Previous</button>
                <button class="opa-btn opa-btn-secondary" disabled>Next</button>
            </div>
        </div>
    </div>
</div>

<!-- Overlay for Slide-overs -->
<div id="opa-slide-over-overlay" class="opa-slide-over-overlay"></div>

<!-- Slide-over: Rule -->
<div id="slideover-rule" class="opa-slide-over">
    <div class="opa-slide-over-header">
        <h2 class="opa-slide-over-title">Pricing Rule</h2>
        <button class="opa-slide-over-close" onclick="OpaSlideOver.close('slideover-rule')">✕</button>
    </div>
    <div class="opa-slide-over-content">
        <form id="form-rule">
            <input type="hidden" name="rule_id" id="field_rule_id" value="">
            
            <div class="opa-form-group">
                <label class="opa-label">City *</label>
                <select name="city_id" id="field_rule_city" class="opa-select" required>
                    <option value="">Select City...</option>
                </select>
            </div>
            
            <div class="opa-form-group">
                <label class="opa-label">Waste Type *</label>
                <select name="waste_type_id" id="field_rule_waste" class="opa-select" required>
                    <option value="">Select Waste Type...</option>
                </select>
            </div>
            
            <div class="opa-form-group">
                <label class="opa-label">Container *</label>
                <select name="container_id" id="field_rule_container" class="opa-select" required>
                    <option value="">Select Container...</option>
                </select>
            </div>

            <div class="opa-form-group">
                <label class="opa-label">Base Price ($) *</label>
                <input type="number" step="0.01" name="base_price" id="field_rule_price" class="opa-input" required>
            </div>
            
            <div class="opa-form-group">
                <label class="opa-label">Status</label>
                <select name="status" id="field_rule_status" class="opa-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </form>
    </div>
    <div class="opa-slide-over-footer">
        <button class="opa-btn opa-btn-secondary" onclick="OpaSlideOver.close('slideover-rule')">Cancel</button>
        <button class="opa-btn opa-btn-primary" onclick="window.saveRule()">Save Rule</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    const nonce = '<?php echo wp_create_nonce('opa_admin_nonce'); ?>';
    
    // State
    window.opaRulesData = [];
    window.opaCurrentPage = 1;
    
    // Load Dropdowns
    function loadDropdown(action, selectors, textKey) {
        fetch(ajaxurl + '?action=' + action + '&_wpnonce=' + nonce)
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    selectors.forEach(selId => {
                        const sel = document.getElementById(selId);
                        res.data.forEach(item => {
                            sel.innerHTML += `<option value="${item.id}">${item[textKey]}</option>`;
                        });
                    });
                }
            });
    }

    loadDropdown('opa_get_cities', ['filter_city', 'field_rule_city'], 'name');
    loadDropdown('opa_get_waste_types', ['filter_waste', 'field_rule_waste'], 'title');
    loadDropdown('opa_get_containers', ['filter_container', 'field_rule_container'], 'name');

    window.resetFilters = () => {
        document.getElementById('filter_search').value = '';
        document.getElementById('filter_city').value = '';
        document.getElementById('filter_waste').value = '';
        document.getElementById('filter_container').value = '';
        document.getElementById('filter_status').value = '';
        window.opaCurrentPage = 1;
        window.loadRules();
    };

    window.getStatusBadge = (status) => {
        const type = status === 'active' ? 'active' : (status === 'inactive' ? 'inactive' : 'archived');
        return `<span class="opa-badge opa-badge-${type}">${status}</span>`;
    };

    window.formatDate = (dateString) => {
        if (!dateString) return '-';
        const d = new Date(dateString);
        return d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    };

    window.renderRuleRow = (rule) => {
        let actions = `
            <div style="display:flex; gap: 8px; justify-content: flex-end;">
                <button class="opa-btn opa-btn-secondary" style="padding: 4px 8px; font-size: 12px;" onclick="editRule(${rule.id})">Edit</button>
                <button class="opa-btn opa-btn-secondary" style="padding: 4px 8px; font-size: 12px;" onclick="duplicateRule(${rule.id})">Duplicate</button>
                ${rule.status !== 'archived' ? `<button class="opa-btn opa-btn-secondary" style="padding: 4px 8px; font-size: 12px; color: #ef4444; border-color: #fca5a5;" onclick="archiveRule(${rule.id})">Archive</button>` : ''}
                ${rule.status === 'archived' ? `<button class="opa-btn opa-btn-secondary" style="padding: 4px 8px; font-size: 12px; color: #ef4444; border-color: #fca5a5;" onclick="deleteRule(${rule.id})">Delete</button>` : ''}
            </div>
        `;
        return `<tr>
            <td>${rule.id}</td>
            <td style="font-weight: 500;">${rule.city_name}</td>
            <td>${rule.waste_name}</td>
            <td>${rule.container_name}</td>
            <td style="font-weight: 600;">$${parseFloat(rule.base_price).toFixed(2)}</td>
            <td>${getStatusBadge(rule.status)}</td>
            <td style="color: #64748b; font-size: 13px;">${formatDate(rule.created_at)}</td>
            <td>${actions}</td>
        </tr>`;
    };

    window.loadRules = (page = 1) => {
        window.opaCurrentPage = page;
        
        const search = document.getElementById('filter_search').value;
        const city_id = document.getElementById('filter_city').value;
        const waste_id = document.getElementById('filter_waste').value;
        const container_id = document.getElementById('filter_container').value;
        const status = document.getElementById('filter_status').value;

        const params = new URLSearchParams({
            action: 'opa_get_pricing_rules',
            _wpnonce: nonce,
            page: page,
            limit: 15,
            search: search,
            city_id: city_id,
            waste_id: waste_id,
            container_id: container_id,
            status: status
        });

        fetch(ajaxurl + '?' + params.toString())
            .then(res => res.json())
            .then(res => {
                const tbody = document.querySelector('#opa-table-rules tbody');
                tbody.innerHTML = '';
                
                if(res.success && res.data.data.length > 0) {
                    window.opaRulesData = res.data.data;
                    res.data.data.forEach(item => {
                        tbody.innerHTML += window.renderRuleRow(item);
                    });
                    
                    // Update Pagination
                    const pag = res.data.pagination;
                    document.getElementById('pagination-info').innerText = `Showing ${res.data.data.length} of ${pag.total_records} results`;
                    
                    let controls = '';
                    if (pag.current_page > 1) {
                        controls += `<button class="opa-btn opa-btn-secondary" onclick="window.loadRules(${pag.current_page - 1})">Previous</button>`;
                    } else {
                        controls += `<button class="opa-btn opa-btn-secondary" disabled>Previous</button>`;
                    }
                    
                    if (pag.current_page < pag.total_pages) {
                        controls += `<button class="opa-btn opa-btn-secondary" onclick="window.loadRules(${pag.current_page + 1})">Next</button>`;
                    } else {
                        controls += `<button class="opa-btn opa-btn-secondary" disabled>Next</button>`;
                    }
                    document.getElementById('pagination-controls').innerHTML = controls;

                } else {
                    window.opaRulesData = [];
                    tbody.innerHTML = '<tr><td colspan="8" style="text-align:center; padding: 40px; color: #64748b;">No pricing rules found.</td></tr>';
                    document.getElementById('pagination-info').innerText = `Showing 0 results`;
                    document.getElementById('pagination-controls').innerHTML = `
                        <button class="opa-btn opa-btn-secondary" disabled>Previous</button>
                        <button class="opa-btn opa-btn-secondary" disabled>Next</button>
                    `;
                }
            });
    };

    window.loadRules();

    window.editRule = (id) => {
        const rule = window.opaRulesData.find(r => r.id == id);
        if(!rule) return;

        document.getElementById('field_rule_id').value = rule.id;
        document.getElementById('field_rule_city').value = rule.city_id;
        document.getElementById('field_rule_waste').value = rule.waste_type_id;
        document.getElementById('field_rule_container').value = rule.container_id;
        document.getElementById('field_rule_price').value = rule.base_price;
        document.getElementById('field_rule_status').value = rule.status;

        OpaSlideOver.open('slideover-rule');
    };

    window.duplicateRule = (id) => {
        const rule = window.opaRulesData.find(r => r.id == id);
        if(!rule) return;

        // Pre-fill the form but empty the ID, then open it
        document.getElementById('field_rule_id').value = '';
        document.getElementById('field_rule_city').value = rule.city_id;
        document.getElementById('field_rule_waste').value = rule.waste_type_id;
        document.getElementById('field_rule_container').value = rule.container_id;
        document.getElementById('field_rule_price').value = rule.base_price;
        document.getElementById('field_rule_status').value = 'inactive'; // Set as inactive copy

        OpaSlideOver.open('slideover-rule');
        OpaToast.show('Rule duplicated! Please change the mapping and save.', 'success');
    };

    window.saveRule = () => {
        const form = document.getElementById('form-rule');
        const formData = new FormData(form);
        formData.append('action', 'opa_save_pricing_rule');
        formData.append('_wpnonce', nonce);

        fetch(ajaxurl, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                OpaToast.show('Pricing Rule saved successfully!', 'success');
                OpaSlideOver.close('slideover-rule');
                window.loadRules(window.opaCurrentPage);
                form.reset();
            } else {
                OpaToast.show(res.data || 'Failed to save', 'error');
            }
        });
    };

    window.archiveRule = (id) => {
        if(confirm('Are you sure you want to archive this pricing rule? It will no longer be available for new bookings.')) {
            const fd = new FormData();
            fd.append('action', 'opa_archive_pricing_rule');
            fd.append('_wpnonce', nonce);
            fd.append('id', id);

            fetch(ajaxurl, { method: 'POST', body: fd })
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    OpaToast.show('Rule archived.', 'success');
                    window.loadRules(window.opaCurrentPage);
                } else {
                    OpaToast.show(res.data, 'error');
                }
            });
        }
    };

    window.deleteRule = (id) => {
        if(confirm('Are you sure you want to permanently DELETE this pricing rule? This action cannot be undone.')) {
            const fd = new FormData();
            fd.append('action', 'opa_delete_pricing_rule');
            fd.append('_wpnonce', nonce);
            fd.append('id', id);

            fetch(ajaxurl, { method: 'POST', body: fd })
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    OpaToast.show('Rule deleted.', 'success');
                    window.loadRules(window.opaCurrentPage);
                } else {
                    OpaToast.show(res.data, 'error');
                }
            });
        }
    };
});
</script>
