<div class="opa-booking-container" style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-family: sans-serif;">
    <h2 style="text-align: center; margin-bottom: 20px;">Book a Container</h2>
    
    <form id="opa-booking-form">
        <!-- Step 1: Location -->
        <div class="opa-step" id="step-1">
            <h3>1. Where do you need the service?</h3>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px;">Select City</label>
                <select id="opa_f_city" name="city_id" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">Select a city...</option>
                </select>
            </div>
            <button type="button" class="opa-btn opa-next" data-next="step-2" style="padding: 10px 20px; background: #0073aa; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Next</button>
        </div>

        <!-- Step 2: Waste Type -->
        <div class="opa-step" id="step-2" style="display: none;">
            <h3>2. What type of waste?</h3>
            <div style="margin-bottom: 15px;">
                <select id="opa_f_waste" name="waste_type_id" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">Select waste type...</option>
                </select>
            </div>
            <button type="button" class="opa-btn opa-prev" data-prev="step-1" style="padding: 10px 20px; background: #ccc; border: none; border-radius: 4px; cursor: pointer;">Back</button>
            <button type="button" class="opa-btn opa-next" data-next="step-3" style="padding: 10px 20px; background: #0073aa; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Next</button>
        </div>

        <!-- Step 3: Container -->
        <div class="opa-step" id="step-3" style="display: none;">
            <h3>3. Choose Container Size</h3>
            <div style="margin-bottom: 15px;">
                <select id="opa_f_container" name="container_id" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">Select container...</option>
                </select>
            </div>
            <div id="opa_price_display" style="margin-bottom: 15px; font-weight: bold; color: #d63638; display:none;">
                Total Price: $<span id="opa_total_price">0.00</span>
            </div>
            <button type="button" class="opa-btn opa-prev" data-prev="step-2" style="padding: 10px 20px; background: #ccc; border: none; border-radius: 4px; cursor: pointer;">Back</button>
            <button type="button" class="opa-btn opa-next" data-next="step-4" style="padding: 10px 20px; background: #0073aa; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Next</button>
        </div>

        <!-- Step 4: Details & Submit -->
        <div class="opa-step" id="step-4" style="display: none;">
            <h3>4. Your Details</h3>
            <div style="margin-bottom: 15px;">
                <label>Date</label>
                <input type="date" name="booking_date" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px;">
                
                <label>Email</label>
                <input type="email" name="customer_email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px;">
                
                <label>Phone</label>
                <input type="text" name="customer_phone" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px;">
                
                <label>Full Address</label>
                <input type="text" name="address_line" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px;">
                
                <!-- Honeypot -->
                <input type="text" name="opa_website" style="display:none;" tabindex="-1" autocomplete="off">
                <input type="hidden" name="idempotency_key" id="opa_idemp_key">
            </div>
            <button type="button" class="opa-btn opa-prev" data-prev="step-3" style="padding: 10px 20px; background: #ccc; border: none; border-radius: 4px; cursor: pointer;">Back</button>
            <button type="submit" style="padding: 10px 20px; background: #46b450; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Confirm Booking</button>
        </div>
        
        <div id="opa-success-msg" style="display:none; padding: 20px; background: #d4edda; color: #155724; border-radius: 4px; margin-top:20px; text-align:center;">
            Booking successful! Your ID is <span id="opa_final_booking_id"></span>.
        </div>
        <div id="opa-error-msg" style="display:none; padding: 20px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-top:20px; text-align:center;">
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    const nonce = '<?php echo wp_create_nonce('opa_frontend_nonce'); ?>';
    
    // Generate idempotency key
    document.getElementById('opa_idemp_key').value = 'idemp_' + Math.random().toString(36).substr(2, 9);

    // Initial load: Cities
    fetch(ajaxurl + '?action=opa_front_get_cities')
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                res.data.forEach(c => {
                    document.getElementById('opa_f_city').innerHTML += `<option value="${c.id}">${c.name}</option>`;
                });
            }
        });

    // Step navigation
    document.querySelectorAll('.opa-next').forEach(btn => {
        btn.addEventListener('click', function() {
            const currentStep = this.closest('.opa-step');
            
            // Validation before proceeding
            const selects = currentStep.querySelectorAll('select');
            let valid = true;
            selects.forEach(s => { if(!s.value) valid = false; });
            if(!valid) { alert('Please make a selection.'); return; }

            // Dynamic logic based on step
            if(currentStep.id === 'step-1') {
                const cityId = document.getElementById('opa_f_city').value;
                fetch(ajaxurl + '?action=opa_front_get_waste&city_id=' + cityId)
                    .then(res => res.json())
                    .then(res => {
                        const sel = document.getElementById('opa_f_waste');
                        sel.innerHTML = '<option value="">Select waste type...</option>';
                        if(res.success) {
                            res.data.forEach(w => sel.innerHTML += `<option value="${w.id}">${w.title}</option>`);
                        }
                    });
            }
            if(currentStep.id === 'step-2') {
                const cityId = document.getElementById('opa_f_city').value;
                const wasteId = document.getElementById('opa_f_waste').value;
                fetch(ajaxurl + '?action=opa_front_get_containers&city_id=' + cityId + '&waste_id=' + wasteId)
                    .then(res => res.json())
                    .then(res => {
                        const sel = document.getElementById('opa_f_container');
                        sel.innerHTML = '<option value="">Select container...</option>';
                        if(res.success) {
                            res.data.forEach(c => sel.innerHTML += `<option value="${c.id}">${c.title} (${c.size})</option>`);
                        }
                    });
            }

            currentStep.style.display = 'none';
            document.getElementById(this.dataset.next).style.display = 'block';
        });
    });

    document.querySelectorAll('.opa-prev').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.opa-step').style.display = 'none';
            document.getElementById(this.dataset.prev).style.display = 'block';
        });
    });

    // Price calculation when container is selected
    document.getElementById('opa_f_container').addEventListener('change', function() {
        const cityId = document.getElementById('opa_f_city').value;
        const wasteId = document.getElementById('opa_f_waste').value;
        const containerId = this.value;
        if(!containerId) return;

        fetch(ajaxurl + '?action=opa_front_get_price&city_id=' + cityId + '&waste_id=' + wasteId + '&container_id=' + containerId)
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    document.getElementById('opa_total_price').innerText = res.data.price;
                    document.getElementById('opa_price_display').style.display = 'block';
                }
            });
    });

    // Form Submission
    document.getElementById('opa-booking-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const fd = new FormData(this);
        fd.append('action', 'opa_front_submit_booking');
        fd.append('_wpnonce', nonce);

        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerText = 'Processing...';

        fetch(ajaxurl, { method: 'POST', body: fd })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                document.querySelectorAll('.opa-step').forEach(el => el.style.display = 'none');
                document.getElementById('opa_final_booking_id').innerText = res.data.booking_number;
                
                // Add Invoice Download Link dynamically
                const invoiceUrl = window.location.href.split('?')[0] + '?opa_invoice=' + res.data.invoice_token;
                const dlBtn = document.createElement('a');
                dlBtn.href = invoiceUrl;
                dlBtn.target = '_blank';
                dlBtn.innerText = 'Download PDF Invoice';
                dlBtn.style.display = 'inline-block';
                dlBtn.style.marginTop = '15px';
                dlBtn.style.padding = '8px 15px';
                dlBtn.style.background = '#0073aa';
                dlBtn.style.color = '#fff';
                dlBtn.style.textDecoration = 'none';
                dlBtn.style.borderRadius = '4px';
                
                const successMsg = document.getElementById('opa-success-msg');
                successMsg.appendChild(document.createElement('br'));
                successMsg.appendChild(dlBtn);
                
                successMsg.style.display = 'block';
                document.getElementById('opa-error-msg').style.display = 'none';
            } else {
                document.getElementById('opa-error-msg').innerText = res.data;
                document.getElementById('opa-error-msg').style.display = 'block';
                btn.disabled = false;
                btn.innerText = 'Confirm Booking';
            }
        }).catch(err => {
            document.getElementById('opa-error-msg').innerText = "Network error occurred.";
            document.getElementById('opa-error-msg').style.display = 'block';
            btn.disabled = false;
            btn.innerText = 'Confirm Booking';
        });
    });
});
</script>
