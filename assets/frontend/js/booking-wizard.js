/**
 * Opa Booking Engine - Progressive Dashboard Logic
 */

document.addEventListener('DOMContentLoaded', () => {
    if (typeof opaBookingObj === 'undefined') return;

    const ajaxurl = opaBookingObj.ajax_url;
    const nonce = opaBookingObj.nonce;

    const state = {
        city: null,
        waste: null,
        container: null,
        date: null,
        price: 0,
        blockedDates: [],
        cache: {
            waste: [],
            containers: []
        }
    };

    const els = {
        // Sections
        secLocation: document.getElementById('sec-location'),
        secWaste: document.getElementById('sec-waste'),
        secContainer: document.getElementById('sec-container'),
        secCalendar: document.getElementById('sec-calendar'),
        secDetails: document.getElementById('sec-details'),
        secSuccess: document.getElementById('sec-success'),
        form: document.getElementById('opa_booking_form'),

        // Inputs
        citySelect: document.getElementById('opa_city_select'),
        wasteGrid: document.getElementById('opa_waste_grid'),
        containerGrid: document.getElementById('opa_container_grid'),
        dateInput: document.getElementById('opa_f_date'),

        // Calendar
        calMonth: document.getElementById('opa_cal_month'),
        calDays: document.getElementById('opa_cal_days'),
        btnPrevMonth: document.getElementById('opa_cal_prev'),
        btnNextMonth: document.getElementById('opa_cal_next'),
        dateError: document.getElementById('opa_date_error'),

        // Summary
        sumCity: document.getElementById('sum_city'),
        sumWaste: document.getElementById('sum_waste'),
        sumContainer: document.getElementById('sum_container'),
        sumDate: document.getElementById('sum_date'),

        // Preview Panel
        prevTitle: document.getElementById('opa_preview_title'),
        prevPrice: document.getElementById('opa_preview_price'),
        prevImg: document.getElementById('opa_preview_img'),
        prevPlaceholder: document.getElementById('opa_preview_placeholder'),
        prevInfoBtn: document.getElementById('opa_preview_info_btn'),

        // Slideover
        slideOverlay: document.getElementById('opa_slide_overlay'),
        slidePanel: document.getElementById('opa_slide_panel'),
        slideClose: document.getElementById('opa_slide_close'),
        slideTitle: document.getElementById('opa_slide_title'),
        slideImg: document.getElementById('opa_slide_img'),
        slideDesc: document.getElementById('opa_slide_desc'),

        slideDesc: document.getElementById('opa_slide_desc'),

        btnSubmit: document.getElementById('opa_btn_submit'),
        
        // Tax & Totals UI
        sumBasePrice: document.getElementById('sum_base_price'),
        sumTaxRate: document.getElementById('sum_tax_rate'),
        sumTaxAmount: document.getElementById('sum_tax_amount'),
        sumGrandTotal: document.getElementById('sum_grand_total'),
        
        calFooter: document.getElementById('opa_cal_footer'),
        calPriceLabel: document.getElementById('opa_cal_price_label'),
        calPriceVal: document.getElementById('opa_cal_price_val')
    };

    let calDate = new Date();

    // Utility: Smooth scroll to section
    function scrollToSection(element) {
        if(window.innerWidth > 992) {
            const y = element.getBoundingClientRect().top + window.scrollY - 100;
            window.scrollTo({top: y, behavior: 'smooth'});
        }
    }

    // Address Search (OpenStreetMap Nominatim)
    const addressInput = document.getElementById('opa_address_search');
    const autocompleteResults = document.getElementById('opa_autocomplete_results');
    let searchTimeout;

    if (addressInput) {
        addressInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            
            if (query.length < 3) {
                autocompleteResults.classList.remove('is-active');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&countrycodes=LT`)
                    .then(res => res.json())
                    .then(data => {
                        autocompleteResults.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(place => {
                                const div = document.createElement('div');
                                div.className = 'opa-autocomplete-item';
                                div.innerHTML = `
                                    <div class="opa-autocomplete-item-title">${place.display_name.split(',')[0]}</div>
                                    <div class="opa-autocomplete-item-desc">${place.display_name}</div>
                                `;
                                div.addEventListener('click', () => {
                                    addressInput.value = place.display_name;
                                    autocompleteResults.classList.remove('is-active');
                                    
                                    // Extract City and match with our cities
                                    const city = place.address.city || place.address.town || place.address.village || place.address.municipality;
                                    if (city) {
                                        matchCity(city);
                                    } else {
                                        alert(opaBookingObj.i18n.err_no_city);
                                    }
                                });
                                autocompleteResults.appendChild(div);
                            });
                            autocompleteResults.classList.add('is-active');
                        } else {
                            autocompleteResults.classList.remove('is-active');
                        }
                    });
            }, 500);
        });

        // Hide autocomplete on click outside
        document.addEventListener('click', (e) => {
            if (!addressInput.contains(e.target) && !autocompleteResults.contains(e.target)) {
                autocompleteResults.classList.remove('is-active');
            }
        });
    }

    function matchCity(cityName) {
        let matched = false;
        for (let i = 0; i < els.citySelect.options.length; i++) {
            const opt = els.citySelect.options[i];
            // Simple case-insensitive include match
            if (opt.value && (opt.text.toLowerCase().includes(cityName.toLowerCase()) || cityName.toLowerCase().includes(opt.text.toLowerCase()))) {
                els.citySelect.selectedIndex = i;
                els.citySelect.dispatchEvent(new Event('change'));
                matched = true;
                break;
            }
        }
        if (!matched) {
            alert(`${opaBookingObj.i18n.err_no_service} ${cityName}.`);
            // Reset fields
            els.citySelect.selectedIndex = 0;
            state.city = null;
            els.wasteGrid.innerHTML = `<div style="grid-column:1/-1; color:var(--opa-text-muted);">${opaBookingObj.i18n.select_city_first}</div>`;
            els.containerGrid.innerHTML = `<div style="grid-column:1/-1; color:var(--opa-text-muted);">${opaBookingObj.i18n.select_waste_first}</div>`;
            updatePreview();
            document.getElementById('opa_detected_city_wrapper').style.display = 'none';
        } else {
            document.getElementById('opa_detected_city_name').innerText = els.citySelect.options[els.citySelect.selectedIndex].text;
            document.getElementById('opa_detected_city_wrapper').style.display = 'block';
        }
    }

    // Update the right sidebar preview panel
    function updatePreview() {
        // Text
        els.sumCity.innerText = state.city ? els.citySelect.options[els.citySelect.selectedIndex].text : '—';
        els.sumWaste.innerText = state.waste ? state.waste.title : '—';
        els.sumContainer.innerText = state.container ? state.container.title : '—';
        els.sumDate.innerText = state.date ? new Date(state.date).toLocaleDateString() : '—';

        // Price
        let basePrice = state.price > 0 && state.date ? parseFloat(state.price) : 0;
        let taxRate = opaBookingObj.tax_rate ? parseFloat(opaBookingObj.tax_rate) : 0;
        let taxAmount = (basePrice * taxRate) / 100;
        let grandTotal = basePrice + taxAmount;
        
        if (basePrice > 0) {
            els.prevPrice.style.display = 'none'; // We'll show detailed breakdown below instead of single big price
            
            els.sumBasePrice.innerText = `€${basePrice.toFixed(2)}`;
            els.sumTaxRate.innerText = taxRate;
            els.sumTaxAmount.innerText = `€${taxAmount.toFixed(2)}`;
            els.sumGrandTotal.innerText = `€${grandTotal.toFixed(2)}`;
            
            els.calFooter.style.display = 'flex';
            els.calPriceLabel.innerText = `Kaina be ${taxRate}% PVM:`;
            els.calPriceVal.innerText = `€${basePrice.toFixed(2)}`;
        } else {
            els.prevPrice.style.display = 'block';
            els.prevPrice.innerText = '---';
            
            els.sumBasePrice.innerText = '—';
            els.sumTaxRate.innerText = taxRate;
            els.sumTaxAmount.innerText = '—';
            els.sumGrandTotal.innerText = '—';
            
            els.calFooter.style.display = 'none';
        }

        // Image logic (prioritize container, fallback to waste)
        const targetObj = state.container || state.waste;
        if (targetObj) {
            els.prevTitle.innerText = targetObj.title;
            els.prevPlaceholder.style.display = 'none';
            els.prevImg.style.display = 'block';
            els.prevImg.src = targetObj.featured_image_url || '';
            
            // Only show details button in overview if container is selected
            if(state.container && targetObj.full_description) {
                els.prevInfoBtn.style.display = 'flex';
                els.prevInfoBtn.onclick = () => {
                    openSlideOver(targetObj.title, targetObj.featured_image_url, targetObj.full_description);
                };
            } else if (state.container && state.waste) {
                els.prevInfoBtn.style.display = 'flex';
                els.prevInfoBtn.onclick = () => {
                    let combinedHtml = `
                        <div style="margin-bottom: 2rem;">
                            <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--opa-text);">${opaBookingObj.i18n.container_details}</h4>
                            <p>${opaBookingObj.i18n.volume} ${state.container.size}</p>
                            ${state.container.full_description ? state.container.full_description : ''}
                        </div>
                        <div style="padding: 1.25rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px;">
                            <h4 style="font-size: 1rem; font-weight: 600; color: #991b1b; margin-bottom: 0.5rem;">${opaBookingObj.i18n.waste_rules_for} ${state.waste.title}</h4>
                            ${state.waste.full_description ? state.waste.full_description : `<p style="color: #991b1b; margin:0;">${opaBookingObj.i18n.standard_rules}</p>`}
                        </div>
                    `;
                    openSlideOver(opaBookingObj.i18n.order_details, targetObj.featured_image_url, combinedHtml);
                };
            } else {
                els.prevInfoBtn.style.display = 'none';
            }
        }
    }

    // Address / City Selection
    els.citySelect.addEventListener('change', (e) => {
        const cityId = e.target.value;
        if (!cityId) {
            document.getElementById('opa_detected_city_wrapper').style.display = 'none';
            return;
        }

        // If manually selected, update tag
        document.getElementById('opa_detected_city_name').innerText = els.citySelect.options[els.citySelect.selectedIndex].text;
        document.getElementById('opa_detected_city_wrapper').style.display = 'block';

        state.city = cityId;
        updatePreview();

        els.wasteGrid.innerHTML = `<div style="grid-column:1/-1; padding:2rem; text-align:center;"><div class="opa-spinner"></div> ${opaBookingObj.i18n.loading_options}</div>`;
        scrollToSection(els.secWaste);

        fetch(`${ajaxurl}?action=opa_front_get_waste&city_id=${cityId}`)
            .then(r => r.json())
            .then(res => {
                els.wasteGrid.innerHTML = '';
                if (res.success && res.data.length > 0) {
                    state.cache.waste = res.data;
                    res.data.forEach(w => {
                        const div = document.createElement('div');
                        div.className = `opa-pill-btn ${state.waste && state.waste.id == w.id ? 'is-selected' : ''}`;
                        const infoBtnHtml = w.full_description ? `<button type="button" class="opa-pill-info-btn" title="${opaBookingObj.i18n.details}"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>${opaBookingObj.i18n.details}</span></button>` : '';
                        const checkHtml = `<div class="opa-pill-check"><svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"></path></svg></div>`;
                        const imgHtml = w.featured_image_url ? `<div class="opa-pill-img-wrap">${checkHtml}${infoBtnHtml}<img src="${w.featured_image_url}" alt="${w.title}"></div>` : `<div style="position:relative; height:40px;">${checkHtml}${infoBtnHtml}</div>`;
                        
                        div.innerHTML = `
                            ${imgHtml}
                            <div class="opa-pill-content">
                                <div class="opa-pill-title">${w.title}</div>
                                <div class="opa-pill-desc">${w.short_description ? w.short_description.substring(0,50) + '...' : ''}</div>
                            </div>
                        `;

                        const infoBtn = div.querySelector('.opa-pill-info-btn');
                        if(infoBtn) {
                            infoBtn.addEventListener('click', (e) => {
                                e.stopPropagation();
                                openSlideOver(w.title, w.featured_image_url, w.full_description);
                            });
                        }

                        div.addEventListener('click', () => {
                            document.querySelectorAll('#opa_waste_grid .opa-pill-btn').forEach(c => c.classList.remove('is-selected'));
                            div.classList.add('is-selected');
                            state.waste = w;
                            state.container = null; // reset container
                            state.price = 0;
                            updatePreview();
                            loadContainers();
                        });
                        els.wasteGrid.appendChild(div);
                    });
                } else {
                    els.wasteGrid.innerHTML = `<div style="color:var(--opa-text-muted);">${opaBookingObj.i18n.no_waste}</div>`;
                }
            });
    });

    // Load Containers
    function loadContainers() {
        els.containerGrid.innerHTML = `<div style="grid-column:1/-1; padding:2rem; text-align:center;"><div class="opa-spinner"></div> ${opaBookingObj.i18n.loading_containers}</div>`;
        scrollToSection(els.secContainer);

        fetch(`${ajaxurl}?action=opa_front_get_containers&city_id=${state.city}&waste_id=${state.waste.id}`)
            .then(r => r.json())
            .then(res => {
                els.containerGrid.innerHTML = '';
                if (res.success && res.data.length > 0) {
                    state.cache.containers = res.data;
                    res.data.forEach(c => {
                        // Fetch price early just to display it
                        fetch(`${ajaxurl}?action=opa_front_get_price&city_id=${state.city}&waste_id=${state.waste.id}&container_id=${c.id}`)
                            .then(pr => pr.json())
                            .then(priceRes => {
                                const rawPrice = priceRes.success ? parseFloat(priceRes.data.price) : 0;
                                const priceStr = rawPrice > 0 ? `€${rawPrice.toFixed(2)}` : '';
                                
                                const div = document.createElement('div');
                                div.className = `opa-pill-btn ${state.container && state.container.id == c.id ? 'is-selected' : ''}`;
                                const infoBtnHtml = c.full_description ? `<button type="button" class="opa-pill-info-btn" title="${opaBookingObj.i18n.details}"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>${opaBookingObj.i18n.details}</span></button>` : '';
                                const checkHtml = `<div class="opa-pill-check"><svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"></path></svg></div>`;
                                const imgHtml = c.featured_image_url ? `<div class="opa-pill-img-wrap">${checkHtml}${infoBtnHtml}<img src="${c.featured_image_url}" alt="${c.title}"></div>` : `<div style="position:relative; height:40px;">${checkHtml}${infoBtnHtml}</div>`;
                                
                                div.innerHTML = `
                                    ${imgHtml}
                                    <div class="opa-pill-content">
                                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.25rem;">
                                            <div class="opa-pill-title">${c.title}</div>
                                            <div style="font-weight:700; color:var(--opa-text); font-size:0.875rem;">${priceStr}</div>
                                        </div>
                                        <div class="opa-pill-desc">${opaBookingObj.i18n.volume} ${c.size}</div>
                                    </div>
                                `;

                                const infoBtn = div.querySelector('.opa-pill-info-btn');
                                if(infoBtn) {
                                    infoBtn.addEventListener('click', (e) => {
                                        e.stopPropagation();
                                        openSlideOver(c.title, c.featured_image_url, c.full_description);
                                    });
                                }

                                div.addEventListener('click', () => {
                                    document.querySelectorAll('#opa_container_grid .opa-pill-btn').forEach(cc => cc.classList.remove('is-selected'));
                                    div.classList.add('is-selected');
                                    state.container = c;
                                    state.price = rawPrice;
                                    updatePreview();
                                    
                                    // Fetch blocked dates
                                    fetch(`${ajaxurl}?action=opa_front_get_blocked_dates&container_id=${c.id}`)
                                        .then(br => br.json())
                                        .then(blockRes => {
                                            state.blockedDates = blockRes.success ? blockRes.data : [];
                                            initCalendar();
                                            scrollToSection(els.secCalendar);
                                        });
                                });
                                els.containerGrid.appendChild(div);
                            });
                    });
                } else {
                    els.containerGrid.innerHTML = `<div style="color:var(--opa-text-muted);">${opaBookingObj.i18n.no_containers}</div>`;
                }
            });
    }

    // Calendar
    function initCalendar() {
        els.btnPrevMonth.onclick = () => { calDate.setMonth(calDate.getMonth() - 1); renderCalendar(); };
        els.btnNextMonth.onclick = () => { calDate.setMonth(calDate.getMonth() + 1); renderCalendar(); };
        renderCalendar();
    }

    function renderCalendar() {
        const year = calDate.getFullYear();
        const month = calDate.getMonth();
        
        const monthNames = opaBookingObj.i18n.months;
        els.calMonth.innerText = `${monthNames[month]} ${year}`;
        
        const firstDayIndex = new Date(year, month, 1).getDay() - 1;
        const adjFirst = firstDayIndex === -1 ? 6 : firstDayIndex;
        const lastDay = new Date(year, month + 1, 0).getDate();
        
        els.calDays.innerHTML = '';
        
        for (let i = 0; i < adjFirst; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'opa-calendar-empty';
            els.calDays.appendChild(emptyDiv);
        }

        const priceStr = state.price > 0 ? `€${parseFloat(state.price).toFixed(2)}` : '';
        const today = new Date();
        today.setHours(0,0,0,0);

        for (let i = 1; i <= lastDay; i++) {
            const d = new Date(year, month, i);
            const ymd = `${year}-${String(month+1).padStart(2,'0')}-${String(i).padStart(2,'0')}`;

            const div = document.createElement('div');
            div.className = 'opa-calendar-day';
            
            div.innerHTML = `
                <div class="opa-calendar-date-num">${i}</div>
                <div class="opa-calendar-day-price">${priceStr}</div>
            `;
            
            if (d < today || state.blockedDates.includes(ymd)) {
                div.classList.add('is-disabled');
                if(state.blockedDates.includes(ymd)) div.title = opaBookingObj.i18n.fully_booked;
            } else {
                if (state.date === ymd) div.classList.add('is-selected');
                
                div.addEventListener('click', () => {
                    document.querySelectorAll('.opa-calendar-day').forEach(el => el.classList.remove('is-selected'));
                    div.classList.add('is-selected');
                    state.date = ymd;
                    els.dateInput.value = ymd;
                    els.dateError.style.display = 'none';
                    updatePreview();
                    scrollToSection(els.secDetails);
                });
            }
            els.calDays.appendChild(div);
        }
    }

    // Submit Logic
    els.form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        if (!state.city || !state.waste || !state.container || !state.date) {
            alert(opaBookingObj.i18n.complete_all);
            return;
        }
        
        const btn = els.btnSubmit;
        btn.disabled = true;
        btn.innerHTML = `<div class="opa-spinner" style="width:20px;height:20px;border-color:#fff;border-bottom-color:transparent;"></div> ${opaBookingObj.i18n.processing}`;
        
        // Generate Idempotency key if not exists
        if(!document.getElementById('opa_idemp_key').value) {
            document.getElementById('opa_idemp_key').value = 'front_' + Math.random().toString(36).substr(2, 9);
        }

        const formData = new FormData(els.form);
        formData.append('action', 'opa_front_submit_booking');
        formData.append('_wpnonce', nonce);
        formData.append('city_id', state.city);
        formData.append('waste_type_id', state.waste.id);
        formData.append('container_id', state.container.id);
        
        fetch(ajaxurl, { method: 'POST', body: formData })
            .then(r => r.json())
            .then(res => {
                if(res.success) {
                    document.getElementById('opa_final_booking_id').innerText = res.data.booking_number;
                    
                    // Populate informative details
                    let finalBase = parseFloat(state.price);
                    let finalTax = (finalBase * parseFloat(opaBookingObj.tax_rate)) / 100;
                    let finalGrand = finalBase + finalTax;
                    
                    document.getElementById('opa_final_date').innerText = new Date(state.date).toLocaleDateString();
                    document.getElementById('opa_final_price').innerText = `€${finalGrand.toFixed(2)} (su PVM)`;
                    document.getElementById('opa_final_service').innerText = `${state.container.title} (${state.waste.title})`;
                    document.getElementById('opa_final_address').innerText = formData.get('address_line');
                    
                    // Hide all sections except success
                    document.querySelectorAll('.opa-section').forEach(s => s.style.display = 'none');
                    document.querySelector('.opa-app-sidebar').style.display = 'none';
                    
                    els.secSuccess.style.display = 'block';
                    
                    // Setup invoice download link
                    if(res.data.invoice_url) {
                        const dl = document.getElementById('opa_btn_download_invoice');
                        dl.href = res.data.invoice_url;
                        dl.download = `Invoice_${res.data.booking_number}.pdf`;
                    }
                } else {
                    btn.disabled = false;
                    btn.innerHTML = `${opaBookingObj.i18n.confirm_booking} <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>`;
                    const errObj = document.getElementById('opa-error-msg');
                    errObj.innerText = res.data || opaBookingObj.i18n.error_occured;
                    errObj.style.display = 'block';
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = `${opaBookingObj.i18n.confirm_booking} <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>`;
                alert(opaBookingObj.i18n.network_error);
            });
    });

    // Slide-over logic
    function openSlideOver(title, imgUrl, descHtml) {
        els.slideTitle.innerText = title;
        if(imgUrl) {
            els.slideImg.src = imgUrl;
            els.slideImg.classList.remove('opa-hidden');
        } else {
            els.slideImg.classList.add('opa-hidden');
        }
        els.slideDesc.innerHTML = descHtml || '';
        
        els.slideOverlay.classList.add('is-active');
        els.slidePanel.classList.add('is-active');
    }
    
    els.slideClose.onclick = closeSlideOver;
    els.slideOverlay.onclick = closeSlideOver;
    
    function closeSlideOver() {
        els.slideOverlay.classList.remove('is-active');
        els.slidePanel.classList.remove('is-active');
    }

});
