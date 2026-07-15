<div class="opa-admin-wrapper">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
        <div>
            <div style="font-size: 12px; color: var(--opa-text-muted); margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">Opa Booking / Data</div>
            <h1 class="opa-page-title">Master Data</h1>
            <p class="opa-page-description">Manage your core business entities: Cities, Waste Types, and Containers.</p>
        </div>
    </div>

    <!-- Tabs Container -->
    <div class="opa-tabs">
        <div class="opa-tabs-nav">
            <button class="opa-tab-btn is-active" data-target="#tab-cities">Cities</button>
            <button class="opa-tab-btn" data-target="#tab-waste">Waste Types</button>
            <button class="opa-tab-btn" data-target="#tab-containers">Containers</button>
        </div>

        <div class="opa-tab-content">
            <!-- Cities Tab -->
            <div id="tab-cities" class="opa-tab-pane is-active">
                <div class="opa-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 style="margin: 0; font-size: 16px;">Service Cities</h2>
                        <button class="opa-btn opa-btn-primary" onclick="OpaSlideOver.open('slideover-city')">
                            + Add City
                        </button>
                    </div>
                    <div class="opa-table-wrapper">
                        <table class="opa-table" id="opa-table-cities">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>City Name</th>
                                    <th>Slug</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="6" style="text-align: center;">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Waste Types Tab -->
            <div id="tab-waste" class="opa-tab-pane">
                <div class="opa-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 style="margin: 0; font-size: 16px;">Waste Types</h2>
                        <button class="opa-btn opa-btn-primary" onclick="OpaSlideOver.open('slideover-waste')">
                            + Add Waste Type
                        </button>
                    </div>
                    <div class="opa-table-wrapper">
                        <table class="opa-table" id="opa-table-waste">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="5" style="text-align: center;">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Containers Tab -->
            <div id="tab-containers" class="opa-tab-pane">
                <div class="opa-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 style="margin: 0; font-size: 16px;">Containers & Bins</h2>
                        <button class="opa-btn opa-btn-primary" onclick="OpaSlideOver.open('slideover-container')">
                            + Add Container
                        </button>
                    </div>
                    <div class="opa-table-wrapper">
                        <table class="opa-table" id="opa-table-containers">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Volume</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="5" style="text-align: center;">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overlay for Slide-overs -->
<div id="opa-slide-over-overlay" class="opa-slide-over-overlay"></div>

<!-- Slide-over: City -->
<div id="slideover-city" class="opa-slide-over">
    <div class="opa-slide-over-header">
        <h2 class="opa-slide-over-title">Add / Edit City</h2>
        <button class="opa-slide-over-close" onclick="OpaSlideOver.close('slideover-city')">✕</button>
    </div>
    <div class="opa-slide-over-content">
        <form id="form-city">
            <input type="hidden" name="city_id" id="field_city_id" value="">
            <div class="opa-form-group">
                <label class="opa-label">City Name *</label>
                <input type="text" name="name" id="field_city_name" class="opa-input" required>
            </div>
            <div class="opa-form-group">
                <label class="opa-label">Slug (Auto-generated if empty)</label>
                <input type="text" name="slug" id="field_city_slug" class="opa-input">
            </div>
            <div class="opa-form-group">
                <label class="opa-label">Country</label>
                <input type="text" name="country" id="field_city_country" class="opa-input">
            </div>
            <div class="opa-form-group">
                <label class="opa-label">Postcode Regex</label>
                <input type="text" name="postcode_regex" id="field_city_postcode" class="opa-input">
            </div>
            <div class="opa-form-group">
                <label class="opa-label">Priority (Higher number = top of list)</label>
                <input type="number" name="priority" id="field_city_priority" class="opa-input" value="0">
            </div>
            <div class="opa-form-group">
                <label class="opa-label">Status</label>
                <select name="status" id="field_city_status" class="opa-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="opa-form-group">
                <label class="opa-label">Internal Notes</label>
                <textarea name="internal_notes" id="field_city_notes" class="opa-textarea" rows="3"></textarea>
            </div>
        </form>
    </div>
    <div class="opa-slide-over-footer">
        <button class="opa-btn opa-btn-secondary" onclick="OpaSlideOver.close('slideover-city')">Cancel</button>
        <button class="opa-btn opa-btn-primary" onclick="saveCity()">Save City</button>
    </div>
</div>

<!-- Slide-over: Waste Type -->
<div id="slideover-waste" class="opa-slide-over">
    <div class="opa-slide-over-header">
        <h2 class="opa-slide-over-title">Add / Edit Waste Type</h2>
        <button class="opa-slide-over-close" onclick="OpaSlideOver.close('slideover-waste')">✕</button>
    </div>
    <div class="opa-slide-over-content">
        <form id="form-waste">
            <input type="hidden" name="waste_id" id="field_waste_id" value="">
            <div class="opa-form-group">
                <label class="opa-label">Title *</label>
                <input type="text" name="title" id="field_waste_title" class="opa-input" required>
            </div>
            
            <div class="opa-form-group">
                <label class="opa-label">Featured Image</label>
                <div style="display: flex; gap: 10px; align-items: flex-start;">
                    <div id="waste-image-preview" style="width: 100px; height: 100px; background: #f1f5f9; border-radius: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--opa-border);">
                        <span style="color: #94a3b8; font-size: 12px;">No Image</span>
                    </div>
                    <div>
                        <input type="hidden" name="featured_image_id" id="field_waste_image_id">
                        <button type="button" class="opa-btn opa-btn-secondary opa-media-btn" data-input="#field_waste_image_id" data-preview="#waste-image-preview">Choose Image</button>
                    </div>
                </div>
            </div>
            
            <div class="opa-form-group">
                <label class="opa-label">Status</label>
                <select name="status" id="field_waste_status" class="opa-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <div class="opa-form-group">
                <label class="opa-label">Short Description (For Cards)</label>
                <textarea name="short_description" id="field_waste_short_description" class="opa-textarea" rows="2"></textarea>
            </div>

            <div class="opa-form-group">
                <label class="opa-label">Full Description (For Popup)</label>
                <?php wp_editor('', 'field_waste_full_description', ['textarea_name' => 'full_description', 'media_buttons' => false, 'textarea_rows' => 6, 'editor_class' => 'opa-wp-editor']); ?>
            </div>
            
            <p class="opa-page-description">Advanced fields (Allowed items, Pricing) are managed elsewhere.</p>
        </form>
    </div>
    <div class="opa-slide-over-footer">
        <button class="opa-btn opa-btn-secondary" onclick="OpaSlideOver.close('slideover-waste')">Cancel</button>
        <button class="opa-btn opa-btn-primary" onclick="saveWaste()">Save Waste Type</button>
    </div>
</div>

<!-- Slide-over: Container -->
<div id="slideover-container" class="opa-slide-over">
    <div class="opa-slide-over-header">
        <h2 class="opa-slide-over-title">Add / Edit Container</h2>
        <button class="opa-slide-over-close" onclick="OpaSlideOver.close('slideover-container')">✕</button>
    </div>
    <div class="opa-slide-over-content">
        <form id="form-container">
            <input type="hidden" name="container_id" id="field_container_id" value="">
            
            <div class="opa-form-group">
                <label class="opa-label">Internal Name *</label>
                <input type="text" name="name" id="field_container_name" class="opa-input" required>
            </div>
            
            <div class="opa-form-group">
                <label class="opa-label">Display Name * (e.g., Small Skip)</label>
                <input type="text" name="display_name" id="field_container_display_name" class="opa-input" required>
            </div>
            
            <div class="opa-form-group">
                <label class="opa-label">Volume (e.g., 5m3)</label>
                <input type="text" name="volume" id="field_container_volume" class="opa-input">
            </div>

            <div style="display:flex; gap:16px;">
                <div class="opa-form-group" style="flex:1;">
                    <label class="opa-label">Length</label>
                    <input type="text" name="length" id="field_container_length" class="opa-input">
                </div>
                <div class="opa-form-group" style="flex:1;">
                    <label class="opa-label">Width</label>
                    <input type="text" name="width" id="field_container_width" class="opa-input">
                </div>
                <div class="opa-form-group" style="flex:1;">
                    <label class="opa-label">Height</label>
                    <input type="text" name="height" id="field_container_height" class="opa-input">
                </div>
            </div>

            <div class="opa-form-group">
                <label class="opa-label">Featured Image</label>
                <div style="display: flex; gap: 10px; align-items: flex-start;">
                    <div id="container-image-preview" style="width: 100px; height: 100px; background: #f1f5f9; border-radius: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--opa-border);">
                        <span style="color: #94a3b8; font-size: 12px;">No Image</span>
                    </div>
                    <div>
                        <input type="hidden" name="featured_image_id" id="field_container_image_id">
                        <button type="button" class="opa-btn opa-btn-secondary opa-media-btn" data-input="#field_container_image_id" data-preview="#container-image-preview">Choose Image</button>
                    </div>
                </div>
            </div>

            <div class="opa-form-group">
                <label class="opa-label">Status</label>
                <select name="status" id="field_container_status" class="opa-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </form>
    </div>
    <div class="opa-slide-over-footer">
        <button class="opa-btn opa-btn-secondary" onclick="OpaSlideOver.close('slideover-container')">Cancel</button>
        <button class="opa-btn opa-btn-primary" onclick="saveContainer()">Save Container</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    const nonce = '<?php echo wp_create_nonce('opa_admin_nonce'); ?>';
    
    // Initialize Media Uploader from Design System
    if(window.OpaMediaUploader) {
        window.OpaMediaUploader.init('.opa-media-btn');
    }

    
    // Quick load function for basic table
    function loadTable(action, tableId, rowRenderer) {
        fetch(ajaxurl + '?action=' + action + '&_wpnonce=' + nonce)
            .then(res => res.json())
            .then(res => {
                const tbody = document.querySelector('#' + tableId + ' tbody');
                tbody.innerHTML = '';
                if(res.success && res.data.length > 0) {
                    res.data.forEach(item => {
                        tbody.innerHTML += rowRenderer(item);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="10" style="text-align:center; padding: 40px; color: #64748b;">No records found.</td></tr>';
                }
            });
    }

    // Badge helper
    window.getStatusBadge = (status) => {
        const type = status === 'active' ? 'active' : (status === 'inactive' ? 'inactive' : 'archived');
        return `<span class="opa-badge opa-badge-${type}">${status}</span>`;
    };

    window.renderCityRow = (city) => {
        return `<tr>
            <td>${city.id}</td>
            <td style="font-weight: 500;">${city.name}</td>
            <td>${city.slug || ''}</td>
            <td>${city.priority || 0}</td>
            <td>${getStatusBadge(city.status)}</td>
            <td>
                <button class="opa-btn opa-btn-secondary" style="padding: 4px 8px; font-size: 12px;" onclick="editCity(${city.id})">Edit</button>
            </td>
        </tr>`;
    };

    // State
    window.opaCitiesData = [];
    window.opaWasteData = [];
    window.opaContainersData = [];

    // Load Cities
    window.loadCities = () => {
        fetch(ajaxurl + '?action=opa_get_cities&_wpnonce=' + nonce)
            .then(res => res.json())
            .then(res => {
                const tbody = document.querySelector('#opa-table-cities tbody');
                tbody.innerHTML = '';
                if(res.success && res.data.length > 0) {
                    window.opaCitiesData = res.data;
                    res.data.forEach(item => {
                        tbody.innerHTML += window.renderCityRow(item);
                    });
                } else {
                    window.opaCitiesData = [];
                    tbody.innerHTML = '<tr><td colspan="10" style="text-align:center; padding: 40px; color: #64748b;">No records found.</td></tr>';
                }
            });
    };

    window.renderWasteRow = (waste) => {
        return `<tr>
            <td>${waste.id}</td>
            <td style="font-weight: 500;">${waste.title}</td>
            <td>${waste.slug || ''}</td>
            <td>${getStatusBadge(waste.status)}</td>
            <td>
                <button class="opa-btn opa-btn-secondary" style="padding: 4px 8px; font-size: 12px;" onclick="editWaste(${waste.id})">Edit</button>
            </td>
        </tr>`;
    };

    window.loadWasteTypes = () => {
        fetch(ajaxurl + '?action=opa_get_waste_types&_wpnonce=' + nonce)
            .then(res => res.json())
            .then(res => {
                const tbody = document.querySelector('#opa-table-waste tbody');
                tbody.innerHTML = '';
                if(res.success && res.data.length > 0) {
                    window.opaWasteData = res.data;
                    res.data.forEach(item => {
                        tbody.innerHTML += window.renderWasteRow(item);
                    });
                } else {
                    window.opaWasteData = [];
                    tbody.innerHTML = '<tr><td colspan="10" style="text-align:center; padding: 40px; color: #64748b;">No records found.</td></tr>';
                }
            });
    };

    window.renderContainerRow = (container) => {
        return `<tr>
            <td>${container.id}</td>
            <td style="font-weight: 500;">${container.title || container.name}</td>
            <td>${container.volume || container.size || ''}</td>
            <td>${getStatusBadge(container.status)}</td>
            <td>
                <button class="opa-btn opa-btn-secondary" style="padding: 4px 8px; font-size: 12px;" onclick="editContainer(${container.id})">Edit</button>
            </td>
        </tr>`;
    };

    window.loadContainers = () => {
        fetch(ajaxurl + '?action=opa_get_containers&_wpnonce=' + nonce)
            .then(res => res.json())
            .then(res => {
                const tbody = document.querySelector('#opa-table-containers tbody');
                tbody.innerHTML = '';
                if(res.success && res.data.length > 0) {
                    window.opaContainersData = res.data;
                    res.data.forEach(item => {
                        tbody.innerHTML += window.renderContainerRow(item);
                    });
                } else {
                    window.opaContainersData = [];
                    tbody.innerHTML = '<tr><td colspan="10" style="text-align:center; padding: 40px; color: #64748b;">No records found.</td></tr>';
                }
            });
    };

    window.loadCities();
    window.loadWasteTypes();
    window.loadContainers();

    // Edit City
    window.editCity = (id) => {
        const city = window.opaCitiesData.find(c => c.id == id);
        if(!city) return;

        document.getElementById('field_city_id').value = city.id;
        document.getElementById('field_city_name').value = city.name || '';
        document.getElementById('field_city_slug').value = city.slug || '';
        document.getElementById('field_city_country').value = city.country || '';
        document.getElementById('field_city_postcode').value = city.postcode_regex || '';
        document.getElementById('field_city_priority').value = city.priority || 0;
        document.getElementById('field_city_status').value = city.status || 'active';
        document.getElementById('field_city_notes').value = city.internal_notes || '';

        OpaSlideOver.open('slideover-city');
    };

    // Save City function
    window.saveCity = () => {
        const form = document.getElementById('form-city');
        const formData = new FormData(form);
        formData.append('action', 'opa_save_city');
        formData.append('_wpnonce', nonce);

        fetch(ajaxurl, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                OpaToast.show('City saved successfully!', 'success');
                OpaSlideOver.close('slideover-city');
                window.loadCities();
                form.reset();
            } else {
                OpaToast.show(res.data || 'Failed to save', 'error');
            }
        });
    };

    // Override Add Buttons (to reset forms)
    document.querySelector('button[onclick="OpaSlideOver.open(\'slideover-city\')"]').onclick = () => {
        document.getElementById('form-city').reset();
        document.getElementById('field_city_id').value = '';
        OpaSlideOver.open('slideover-city');
    };
    
    document.querySelector('button[onclick="OpaSlideOver.open(\'slideover-waste\')"]').onclick = () => {
        document.getElementById('form-waste').reset();
        document.getElementById('field_waste_id').value = '';
        OpaSlideOver.open('slideover-waste');
    };

    document.querySelector('button[onclick="OpaSlideOver.open(\'slideover-container\')"]').onclick = () => {
        document.getElementById('form-container').reset();
        document.getElementById('field_container_id').value = '';
        OpaSlideOver.open('slideover-container');
    };

    // Edit Waste
    window.editWaste = (id) => {
        const waste = window.opaWasteData.find(w => w.id == id);
        if(!waste) return;
        document.getElementById('field_waste_id').value = waste.id;
        document.getElementById('field_waste_title').value = waste.title || '';
        document.getElementById('field_waste_status').value = waste.status || 'active';
        document.getElementById('field_waste_short_description').value = waste.short_description || '';
        
        // Update TinyMCE editor
        if(typeof tinymce !== 'undefined' && tinymce.get('field_waste_full_description')) {
            tinymce.get('field_waste_full_description').setContent(waste.full_description || '');
        } else {
            document.getElementById('field_waste_full_description').value = waste.full_description || '';
        }
        
        document.getElementById('field_waste_image_id').value = waste.featured_image_id || '';
        const preview = document.getElementById('waste-image-preview');
        if (waste.featured_image_url) {
            preview.innerHTML = `<img src="${waste.featured_image_url}" style="max-width:100%;max-height:100%;object-fit:cover;">`;
        } else {
            preview.innerHTML = `<span style="color: #94a3b8; font-size: 12px;">No Image</span>`;
        }

        OpaSlideOver.open('slideover-waste');
    };

    // Save Waste
    window.saveWaste = () => {
        // Sync tinymce content back to textarea before FormData creation
        if(typeof tinymce !== 'undefined' && tinymce.get('field_waste_full_description')) {
            tinymce.get('field_waste_full_description').save();
        }
        
        const form = document.getElementById('form-waste');
        const formData = new FormData(form);
        formData.append('action', 'opa_save_waste_type'); 
        formData.append('_wpnonce', nonce);

        fetch(ajaxurl, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                OpaToast.show('Waste Type saved successfully!', 'success');
                OpaSlideOver.close('slideover-waste');
                window.loadWasteTypes();
                form.reset();
                if(typeof tinymce !== 'undefined' && tinymce.get('field_waste_full_description')) {
                    tinymce.get('field_waste_full_description').setContent('');
                }
                document.getElementById('waste-image-preview').innerHTML = `<span style="color: #94a3b8; font-size: 12px;">No Image</span>`;
            } else {
                OpaToast.show(res.data || 'Failed to save', 'error');
            }
        });
    };

    // Edit Container
    window.editContainer = (id) => {
        const cont = window.opaContainersData.find(c => c.id == id);
        if(!cont) return;
        document.getElementById('field_container_id').value = cont.id;
        document.getElementById('field_container_name').value = cont.name || cont.title || '';
        document.getElementById('field_container_display_name').value = cont.display_name || cont.title || '';
        document.getElementById('field_container_volume').value = cont.volume || cont.size || '';
        document.getElementById('field_container_length').value = cont.length || '';
        document.getElementById('field_container_width').value = cont.width || '';
        document.getElementById('field_container_height').value = cont.height || '';
        document.getElementById('field_container_status').value = cont.status || 'active';
        
        document.getElementById('field_container_image_id').value = cont.featured_image_id || '';
        const preview = document.getElementById('container-image-preview');
        if (cont.featured_image_url) {
            preview.innerHTML = `<img src="${cont.featured_image_url}" style="max-width:100%;max-height:100%;object-fit:cover;">`;
        } else {
            preview.innerHTML = `<span style="color: #94a3b8; font-size: 12px;">No Image</span>`;
        }

        OpaSlideOver.open('slideover-container');
    };

    // Save Container
    window.saveContainer = () => {
        const form = document.getElementById('form-container');
        const formData = new FormData(form);
        // Map old fields to new
        formData.append('title', document.getElementById('field_container_name').value);
        formData.append('display_name', document.getElementById('field_container_display_name').value);
        formData.append('volume', document.getElementById('field_container_volume').value);
        formData.append('action', 'opa_save_container'); 
        formData.append('_wpnonce', nonce);

        fetch(ajaxurl, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                OpaToast.show('Container saved successfully!', 'success');
                OpaSlideOver.close('slideover-container');
                window.loadContainers();
                form.reset();
                document.getElementById('container-image-preview').innerHTML = `<span style="color: #94a3b8; font-size: 12px;">No Image</span>`;
            } else {
                OpaToast.show(res.data || 'Failed to save', 'error');
            }
        });
    };
});
</script>
