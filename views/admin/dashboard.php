<div class="wrap">
    <h1>Bookings Dashboard</h1>
    <p>Manage all customer container bookings here.</p>
    
    <div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); margin-top: 20px;">
        <table class="wp-list-table widefat fixed striped" id="table_bookings">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer Details</th>
                    <th>Service Details</th>
                    <th>Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="7">Loading bookings...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Booking Details -->
<div id="opa-booking-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99999;">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:#fff; padding:30px; border-radius:8px; width:500px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
        <h2>Update Booking Status</h2>
        <p><strong>Booking ID:</strong> <span id="modal_b_id"></span></p>
        <p><strong>Customer:</strong> <span id="modal_b_cust"></span></p>
        
        <div style="margin-top:20px;">
            <label style="font-weight:bold; display:block; margin-bottom:5px;">Change Status:</label>
            <select id="modal_b_status" style="width:100%; padding:8px;">
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        
        <input type="hidden" id="modal_hidden_id">
        
        <div style="margin-top:30px; text-align:right;">
            <button type="button" class="button" onclick="document.getElementById('opa-booking-modal').style.display='none'">Cancel</button>
            <button type="button" class="button button-primary" id="modal_btn_save">Update Status</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    const nonce = '<?php echo wp_create_nonce('opa_admin_nonce'); ?>';
    
    function loadBookings() {
        fetch(ajaxurl + '?action=opa_get_bookings&_wpnonce=' + nonce)
            .then(res => res.json())
            .then(res => {
                const tbody = document.querySelector('#table_bookings tbody');
                tbody.innerHTML = '';
                if(res.success && res.data.length > 0) {
                    res.data.forEach(b => {
                        tbody.innerHTML += `<tr>
                            <td><strong>${b.booking_number}</strong></td>
                            <td>${b.customer_email}<br>${b.customer_phone}<br><small>${b.address_line}</small></td>
                            <td>${b.city} > ${b.waste_type} > ${b.container}</td>
                            <td>${b.booking_date}</td>
                            <td>$${b.total_price}</td>
                            <td><span style="padding: 5px 10px; border-radius: 4px; background: #eee; text-transform:uppercase; font-size:11px;">${b.status}</span></td>
                            <td>
                                <button type="button" class="button button-small opa-edit-btn" data-id="${b.id}" data-num="${b.booking_number}" data-cust="${b.customer_email}" data-status="${b.status}">Update</button>
                            </td>
                        </tr>`;
                    });
                    
                    document.querySelectorAll('.opa-edit-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            document.getElementById('modal_b_id').innerText = this.dataset.num;
                            document.getElementById('modal_b_cust').innerText = this.dataset.cust;
                            document.getElementById('modal_b_status').value = this.dataset.status;
                            document.getElementById('modal_hidden_id').value = this.dataset.id;
                            document.getElementById('opa-booking-modal').style.display = 'block';
                        });
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="7">No bookings found.</td></tr>';
                }
            });
    }

    loadBookings();
    
    document.getElementById('modal_btn_save').addEventListener('click', function() {
        const id = document.getElementById('modal_hidden_id').value;
        const status = document.getElementById('modal_b_status').value;
        
        const fd = new FormData();
        fd.append('action', 'opa_update_booking_status');
        fd.append('_wpnonce', nonce);
        fd.append('booking_id', id);
        fd.append('status', status);
        
        fetch(ajaxurl, { method: 'POST', body: fd })
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    document.getElementById('opa-booking-modal').style.display = 'none';
                    loadBookings();
                } else {
                    alert('Error: ' + res.data);
                }
            });
    });
});
</script>
