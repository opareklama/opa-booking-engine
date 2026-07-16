<style>
.opa-premium-wrap { padding: 20px 20px 20px 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; }
.opa-premium-wrap h1 { font-weight: 600; font-size: 24px; margin-bottom: 5px; color: #1d2327; }
.opa-premium-wrap p { color: #50575e; margin-top: 0; margin-bottom: 25px; }

/* KPI Cards */
.opa-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
.opa-kpi-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; display: flex; flex-direction: column; }
.opa-kpi-title { font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
.opa-kpi-value { font-size: 28px; font-weight: 700; color: #0f172a; }

/* Toolbar */
.opa-toolbar { display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; background: #fff; padding: 15px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; }
.opa-toolbar input, .opa-toolbar select { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; color: #334155; min-width: 150px; outline: none; }
.opa-toolbar input:focus, .opa-toolbar select:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }
.opa-toolbar .opa-search-wrapper { flex-grow: 1; min-width: 200px; }
.opa-toolbar .opa-search-wrapper input { width: 100%; }
.opa-toolbar button { padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s; }
.opa-btn-primary { background: #3b82f6; color: #fff; }
.opa-btn-primary:hover { background: #2563eb; }
.opa-btn-outline { background: #f8fafc; color: #475569; border: 1px solid #cbd5e1 !important; }
.opa-btn-outline:hover { background: #f1f5f9; color: #0f172a; }

/* Highlights & Checkboxes */
.opa-highlight { background-color: #fef08a; color: #854d0e; font-weight: 600; padding: 0 2px; border-radius: 2px; }
.opa-checkbox { width: 16px; height: 16px; cursor: pointer; accent-color: #3b82f6; }
.opa-bulk-wrap { display: flex; gap: 8px; align-items: center; border-right: 1px solid #e2e8f0; padding-right: 15px; margin-right: 10px; }

/* Table */
.opa-table-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; overflow: hidden; }
.opa-premium-table { width: 100%; border-collapse: collapse; text-align: left; }
.opa-premium-table th { background: #f8fafc; padding: 16px 20px; font-size: 13px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; }
.opa-premium-table td { padding: 20px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: #1e293b; font-size: 14px; }
.opa-premium-table tr:last-child td { border-bottom: none; }
.opa-premium-table tr:hover { background: #fdfdfd; }

.opa-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.opa-badge.pending { background: #fef3c7; color: #b45309; }
.opa-badge.confirmed { background: #e0e7ff; color: #4338ca; }
.opa-badge.completed { background: #dcfce3; color: #166534; }
.opa-badge.cancelled { background: #fee2e2; color: #b91c1c; }

.opa-action-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 8px 14px; border-radius: 6px; font-size: 13px; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 0.2s; border: none; }
.opa-btn-icon { padding: 8px; border-radius: 50%; background: #f1f5f9; color: #64748b; }
.opa-btn-icon:hover { background: #e2e8f0; color: #0f172a; }
.opa-actions-flex { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

.opa-cust-details strong { display: block; color: #0f172a; margin-bottom: 4px; }
.opa-cust-details span { color: #64748b; font-size: 13px; display: block; line-height: 1.5; }

/* Modal */
.opa-modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 99999; backdrop-filter: blur(4px); }
.opa-modal-content { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; width: 600px; max-width: 90%; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; }
.opa-modal-header { padding: 20px 30px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; }
.opa-modal-header h2 { margin: 0; font-size: 18px; color: #0f172a; font-weight: 600; }
.opa-modal-close { background: none; border: none; font-size: 24px; color: #94a3b8; cursor: pointer; padding: 0; line-height: 1; transition: color 0.2s; }
.opa-modal-close:hover { color: #0f172a; }
.opa-modal-body { padding: 30px; overflow-y: auto; }
.opa-modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
.opa-info-group { background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #f1f5f9; }
.opa-info-label { font-size: 12px; text-transform: uppercase; color: #64748b; font-weight: 600; margin-bottom: 8px; display: block; letter-spacing: 0.5px; }
.opa-info-value { font-size: 14px; color: #1e293b; line-height: 1.6; }
.opa-info-value strong { color: #0f172a; }
.opa-modal-footer { padding: 20px 30px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; }
.opa-status-updater { display: flex; align-items: center; gap: 10px; }
.opa-status-updater select { padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1; outline: none; }
</style>

<div class="wrap opa-premium-wrap">
    <h1>Bookings Dashboard</h1>
    <p>Manage all customer container bookings here.</p>
    
    <!-- KPI Cards -->
    <div class="opa-kpi-grid">
        <div class="opa-kpi-card">
            <div class="opa-kpi-title">Today's Bookings</div>
            <div class="opa-kpi-value" id="kpi_today">-</div>
        </div>
        <div class="opa-kpi-card">
            <div class="opa-kpi-title">Pending Orders</div>
            <div class="opa-kpi-value" id="kpi_pending">-</div>
        </div>
        <div class="opa-kpi-card">
            <div class="opa-kpi-title">Total Revenue</div>
            <div class="opa-kpi-value" id="kpi_revenue">-</div>
        </div>
        <div class="opa-kpi-card">
            <div class="opa-kpi-title">Invoices Generated</div>
            <div class="opa-kpi-value" id="kpi_invoices">-</div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="opa-toolbar">
        <div class="opa-bulk-wrap">
            <select id="bulk_action_select">
                <option value="">Bulk Actions</option>
                <option value="status_pending">Mark Pending</option>
                <option value="status_confirmed">Mark Confirmed</option>
                <option value="status_completed">Mark Completed</option>
                <option value="status_cancelled">Mark Cancelled</option>
                <option value="delete">Delete</option>
            </select>
            <button type="button" class="opa-btn-outline" id="btn_apply_bulk" style="padding: 8px 12px;">Apply</button>
        </div>
        <div class="opa-search-wrapper">
            <input type="text" id="filter_search" placeholder="Search by Booking ID, Email, Phone...">
        </div>
        <input type="date" id="filter_date" placeholder="Date">
        <select id="filter_status">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <button type="button" class="opa-btn-outline" id="btn_refresh">
            Refresh Data
        </button>
    </div>
    
    <div class="opa-table-card">
        <table class="opa-premium-table" id="table_bookings">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;"><input type="checkbox" class="opa-checkbox" id="check_all"></th>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Service Info</th>
                    <th>Date</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">Loading bookings...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Booking Details -->
<div id="opa-booking-modal" class="opa-modal-overlay">
    <div class="opa-modal-content">
        <div class="opa-modal-header">
            <h2 id="modal_b_title">Booking Details</h2>
            <button class="opa-modal-close" onclick="document.getElementById('opa-booking-modal').style.display='none'">&times;</button>
        </div>
        
        <div class="opa-modal-body">
            <div class="opa-modal-grid">
                <div class="opa-info-group">
                    <span class="opa-info-label">Customer Information</span>
                    <div class="opa-info-value" id="modal_v_cust"></div>
                </div>
                <div class="opa-info-group">
                    <span class="opa-info-label">Service Address</span>
                    <div class="opa-info-value" id="modal_v_address"></div>
                </div>
            </div>
            
            <div class="opa-info-group" style="margin-bottom: 24px;">
                <span class="opa-info-label">Service Details</span>
                <div class="opa-info-value" id="modal_v_service"></div>
            </div>
            
            <div class="opa-modal-grid" style="margin-bottom: 0;">
                <div class="opa-info-group">
                    <span class="opa-info-label">Booking Date</span>
                    <div class="opa-info-value"><strong id="modal_v_date"></strong></div>
                </div>
                <div class="opa-info-group">
                    <span class="opa-info-label">Total Amount</span>
                    <div class="opa-info-value"><strong id="modal_v_price" style="color: #10b981; font-size: 16px;"></strong></div>
                </div>
            </div>
        </div>
        
        <div class="opa-modal-footer">
            <div class="opa-status-updater">
                <select id="modal_b_status">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button type="button" class="opa-action-btn opa-btn-primary" id="modal_btn_save">Update Status</button>
            </div>
            <div id="modal_invoice_wrapper"></div>
        </div>
        <input type="hidden" id="modal_hidden_id">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    const nonce = '<?php echo wp_create_nonce('opa_admin_nonce'); ?>';
    const siteUrl = '<?php echo site_url(); ?>';
    let windowAllBookings = [];
    
    function loadKPIs() {
        fetch(ajaxurl + '?action=opa_get_dashboard_kpi&_wpnonce=' + nonce)
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    document.getElementById('kpi_today').innerText = res.data.today;
                    document.getElementById('kpi_pending').innerText = res.data.pending;
                    const currency = '<?php echo get_option('opa_currency_symbol', '€'); ?>';
                    document.getElementById('kpi_revenue').innerText = currency + res.data.revenue;
                    document.getElementById('kpi_invoices').innerText = res.data.invoices;
                }
            });
    }

    function loadBookings() {
        const tbody = document.querySelector('#table_bookings tbody');
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 40px; color: #64748b;">Loading bookings...</td></tr>';

        fetch(ajaxurl + '?action=opa_get_bookings&_wpnonce=' + nonce)
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    windowAllBookings = res.data;
                    filterAndRender();
                } else {
                    tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 40px; color: #64748b;">Error loading bookings.</td></tr>';
                }
            });
    }

    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function highlightText(text, search) {
        if (!search || !text) return text;
        const regex = new RegExp(`(${escapeRegExp(search)})`, 'gi');
        return String(text).replace(regex, '<mark class="opa-highlight">$1</mark>');
    }

    function filterAndRender() {
        const search = document.getElementById('filter_search').value.toLowerCase().trim();
        const status = document.getElementById('filter_status').value;
        const date = document.getElementById('filter_date').value;
        
        let filtered = windowAllBookings.filter(b => {
            if (status && b.status !== status) return false;
            if (date && b.booking_date !== date) return false;
            return true;
        });
        
        if (search) {
            filtered = filtered.map(b => {
                let maxScore = 0;
                let searchable = [
                    b.booking_number, b.customer_email, b.customer_phone, 
                    b.city, b.container, b.waste_type, b.address_line, b.invoice_number
                ].map(s => (s || '').toLowerCase());
                
                searchable.forEach(s => {
                    if (s === search) maxScore = Math.max(maxScore, 100);
                    else if (s.startsWith(search)) maxScore = Math.max(maxScore, 50);
                    else if (s.includes(search)) maxScore = Math.max(maxScore, 10);
                });
                b._score = maxScore;
                return b;
            }).filter(b => b._score > 0);
            
            filtered.sort((a, b) => b._score - a._score);
        }

        renderTable(filtered, search);
    }

    function renderTable(data, search = '') {
        const tbody = document.querySelector('#table_bookings tbody');
        document.getElementById('check_all').checked = false;
        tbody.innerHTML = '';
        
        if(data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 40px; color: #64748b;">No bookings found matching your criteria.</td></tr>';
            return;
        }

        const currency = '<?php echo get_option('opa_currency_symbol', '€'); ?>';
        
        data.forEach((b) => {
            const index = windowAllBookings.indexOf(b);
            const hl = (text) => highlightText(text, search);
            
            tbody.innerHTML += `<tr>
                <td style="text-align: center;">
                    <input type="checkbox" class="opa-checkbox row-checkbox" value="${b.id}">
                </td>
                <td>
                    <strong style="color:#0f172a; font-size:14px;">${hl(b.booking_number)}</strong><br>
                    ${b.invoice_number ? `<span style="font-size:12px; color:#64748b;">Inv: ${hl(b.invoice_number)}</span>` : ''}
                </td>
                <td class="opa-cust-details">
                    <strong>${hl(b.customer_email)}</strong>
                    <span>${hl(b.customer_phone)}</span>
                </td>
                <td style="color:#475569;">
                    <strong style="color:#334155;">${hl(b.waste_type)}</strong><br>
                    <span style="font-size:13px;">${hl(b.city)} &bull; ${hl(b.container)}</span>
                </td>
                <td><strong style="color:#334155;">${b.booking_date}</strong></td>
                <td><strong style="color:#10b981;">${currency}${parseFloat(b.total_price).toFixed(2)}</strong></td>
                <td><span class="opa-badge ${b.status}">${b.status}</span></td>
                <td style="text-align: right;">
                    <div class="opa-actions-flex" style="justify-content: flex-end;">
                        <button type="button" class="opa-action-btn opa-btn-icon opa-view-btn" data-idx="${index}" title="View Details">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </td>
            </tr>`;
        });
        
        bindEvents();
    }
    
    function bindEvents() {
        document.querySelectorAll('.opa-view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const b = windowAllBookings[this.dataset.idx];
                const currency = '<?php echo get_option('opa_currency_symbol', '€'); ?>';
                
                let modalTitle = 'Booking: ' + b.booking_number;
                if (b.invoice_number) {
                    modalTitle += ' <span style="font-size:14px; font-weight:normal; color:#64748b; margin-left:10px;">(Inv: ' + b.invoice_number + ')</span>';
                }
                document.getElementById('modal_b_title').innerHTML = modalTitle;
                
                document.getElementById('modal_v_cust').innerHTML = `<strong>${b.customer_email}</strong><br>${b.customer_phone}`;
                document.getElementById('modal_v_address').innerText = b.address_line;
                document.getElementById('modal_v_service').innerHTML = `<strong>City:</strong> ${b.city}<br><strong>Waste Type:</strong> ${b.waste_type}<br><strong>Container:</strong> ${b.container}`;
                document.getElementById('modal_v_date').innerText = b.booking_date;
                document.getElementById('modal_v_price').innerText = currency + parseFloat(b.total_price).toFixed(2);
                
                document.getElementById('modal_b_status').value = b.status;
                document.getElementById('modal_hidden_id').value = b.id;
                
                const invWrapper = document.getElementById('modal_invoice_wrapper');
                if (b.invoice_token) {
                    invWrapper.innerHTML = `<a href="${siteUrl}/?opa_invoice=${b.invoice_token}" target="_blank" class="opa-action-btn" style="background:#4f46e5; color:#fff;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        View Invoice
                    </a>`;
                } else {
                    invWrapper.innerHTML = '';
                }
                
                document.getElementById('opa-booking-modal').style.display = 'block';
            });
        });
        
        const checkAll = document.getElementById('check_all');
        const rowChecks = document.querySelectorAll('.row-checkbox');
        
        rowChecks.forEach(chk => {
            chk.addEventListener('change', function() {
                const allChecked = Array.from(rowChecks).every(c => c.checked);
                checkAll.checked = allChecked;
            });
        });
    }

    document.getElementById('check_all').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.row-checkbox').forEach(chk => {
            chk.checked = isChecked;
        });
    });

    document.getElementById('btn_apply_bulk').addEventListener('click', function() {
        const action = document.getElementById('bulk_action_select').value;
        if (!action) {
            alert('Please select an action.');
            return;
        }
        
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Please select at least one booking.');
            return;
        }
        
        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete the selected bookings?')) return;
        }
        
        const btn = this;
        const originalText = btn.innerText;
        btn.innerText = 'Applying...';
        btn.disabled = true;
        
        const fd = new FormData();
        fd.append('action', 'opa_bulk_action_bookings');
        fd.append('_wpnonce', nonce);
        fd.append('bulk_action', action);
        
        checkedBoxes.forEach(chk => {
            fd.append('booking_ids[]', chk.value);
        });
        
        fetch(ajaxurl, { method: 'POST', body: fd })
            .then(res => res.json())
            .then(res => {
                btn.innerText = originalText;
                btn.disabled = false;
                if(res.success) {
                    alert(res.data);
                    document.getElementById('bulk_action_select').value = '';
                    loadKPIs();
                    loadBookings();
                } else {
                    alert('Error: ' + res.data);
                }
            });
    });

    ['filter_status', 'filter_date'].forEach(id => {
        document.getElementById(id).addEventListener('change', filterAndRender);
    });
    
    document.getElementById('filter_search').addEventListener('input', function() {
        filterAndRender();
    });
    
    document.getElementById('btn_refresh').addEventListener('click', function() {
        loadKPIs();
        loadBookings();
    });

    // Modal Single Status Update
    document.getElementById('modal_btn_save').addEventListener('click', function() {
        const id = document.getElementById('modal_hidden_id').value;
        const status = document.getElementById('modal_b_status').value;
        const btn = this;
        const originalText = btn.innerText;
        btn.innerText = 'Updating...';
        btn.disabled = true;
        
        const fd = new FormData();
        fd.append('action', 'opa_update_booking_status');
        fd.append('_wpnonce', nonce);
        fd.append('booking_id', id);
        fd.append('status', status);
        
        fetch(ajaxurl, { method: 'POST', body: fd })
            .then(res => res.json())
            .then(res => {
                btn.innerText = originalText;
                btn.disabled = false;
                if(res.success) {
                    document.getElementById('opa-booking-modal').style.display = 'none';
                    loadKPIs();
                    loadBookings();
                } else {
                    alert('Error: ' + res.data);
                }
            });
    });

    // Initial Load
    loadKPIs();
    loadBookings();
});
</script>
