<?php
if (!defined('ABSPATH')) exit;
global $wpdb;
$cities = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}opa_cities WHERE status = 'active' ORDER BY name ASC");
?>
<div class="opa-app-container">
    <div class="opa-app-main">
        <form id="opa_booking_form" class="opa-modern-form">
            
            <!-- SECTION 1: LOCATION -->
            <div class="opa-section is-active" id="sec-location">
                <div class="opa-section-header">
                    <div class="opa-sec-num">1</div>
                    <h2 class="opa-sec-title"><?php esc_html_e('Paslaugos vieta', 'opa-booking'); ?></h2>
                </div>
                <div class="opa-section-content">
                    <div class="opa-address-wrapper">
                        <label class="opa-label" style="font-size: 1rem; margin-bottom: 0.75rem;"><?php esc_html_e('Ieškokite savo vietos, kad patikrintumėte prieinamumą', 'opa-booking'); ?></label>
                        <div class="opa-inline-input-group">
                            <div style="flex-grow: 1; position: relative; display: flex; align-items: center;">
                                <svg width="20" height="20" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" style="position: absolute; left: 1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                <input type="text" id="opa_address_search" class="opa-input-pro" placeholder="Įveskite miestą ar vietovę..." autocomplete="off" style="padding-left: 2.75rem;">
                                <div id="opa_autocomplete_results" class="opa-autocomplete-results"></div>
                            </div>
                            <select id="opa_city_select" class="opa-input-pro opa-city-dropdown">
                                <option value=""><?php esc_html_e('Pasirinkite miestą...', 'opa-booking'); ?></option>
                                <?php foreach ( $cities as $city ) : ?>
                                    <option value="<?php echo esc_attr( $city->id ); ?>"><?php echo esc_html( $city->name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="opa_detected_city_wrapper" style="display:none; margin-top: 0.75rem;">
                            <span class="opa-city-tag">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:0.25rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span id="opa_detected_city_name">Vilnius</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: WASTE TYPE -->
            <div class="opa-section" id="sec-waste">
                <div class="opa-section-header">
                    <div class="opa-sec-num">2</div>
                    <h2 class="opa-sec-title"><?php esc_html_e('Ką išmetate?', 'opa-booking'); ?></h2>
                </div>
                <div class="opa-section-content">
                    <div id="opa_waste_grid" class="opa-pill-grid">
                        <div style="grid-column:1/-1; color:var(--opa-text-muted);"><?php esc_html_e('Pirmiausia pasirinkite miestą.', 'opa-booking'); ?></div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: CONTAINER -->
            <div class="opa-section" id="sec-container">
                <div class="opa-section-header">
                    <div class="opa-sec-num">3</div>
                    <h2 class="opa-sec-title"><?php esc_html_e('Pasirinkite konteinerio dydį', 'opa-booking'); ?></h2>
                </div>
                <div class="opa-section-content">
                    <div id="opa_container_grid" class="opa-pill-grid">
                        <div style="grid-column:1/-1; color:var(--opa-text-muted);"><?php esc_html_e('Pirmiausia pasirinkite atliekų tipą.', 'opa-booking'); ?></div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: CALENDAR -->
            <div class="opa-section" id="sec-calendar">
                <div class="opa-section-header">
                    <div class="opa-sec-num">4</div>
                    <h2 class="opa-sec-title"><?php esc_html_e('Pristatymo data', 'opa-booking'); ?></h2>
                </div>
                <div class="opa-section-content">
                    <div class="opa-calendar">
                        <div class="opa-calendar-header">
                            <button type="button" class="opa-calendar-btn" id="opa_cal_prev">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <h3 id="opa_cal_month" style="margin:0; font-size:1.125rem; font-weight:600;">Month Year</h3>
                            <button type="button" class="opa-calendar-btn" id="opa_cal_next">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                        <div class="opa-calendar-grid">
                            <div class="opa-calendar-day-header">PIR</div>
                            <div class="opa-calendar-day-header">ANT</div>
                            <div class="opa-calendar-day-header">TRE</div>
                            <div class="opa-calendar-day-header">KET</div>
                            <div class="opa-calendar-day-header">PEN</div>
                            <div class="opa-calendar-day-header">ŠEŠ</div>
                            <div class="opa-calendar-day-header">SEK</div>
                        </div>
                        <div class="opa-calendar-grid" id="opa_cal_days">
                            <!-- Days injected via JS -->
                        </div>
                        <div class="opa-calendar-footer" id="opa_cal_footer" style="display: none; align-items: center; justify-content: flex-start; margin-top: 1.5rem; flex-wrap: wrap; gap: 2rem; width: 100%; box-sizing: border-box;">
                            <div class="opa-cal-price-box" style="background: #22c55e; color: #fff; padding: 0.75rem 1.25rem; border-radius: 8px; text-align: center; min-width: 140px; box-shadow: 0 2px 4px rgba(34, 197, 94, 0.2);">
                                <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.95; margin-bottom: 0.25rem;" id="opa_cal_price_label">Kaina be PVM:</div>
                                <div style="font-size: 1.25rem; font-weight: 700; line-height: 1;" id="opa_cal_price_val">--</div>
                            </div>
                            <div class="opa-cal-legend" style="display: flex; gap: 1.5rem; font-size: 0.85rem; color: #64748b; flex-wrap: wrap; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 0.4rem;">
                                    <div style="width: 16px; height: 16px; border-radius: 50%; border: 2px solid #22c55e;"></div>
                                    <span style="white-space: nowrap;">Yra laisvų konteinerių</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.4rem;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2" style="border-radius:50%; background:#f8fafc;"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                    <span style="white-space: nowrap;">Užimta</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="opa_f_date" name="booking_date" required>
                    <p id="opa_date_error" style="color: #ef4444; font-size: 0.875rem; margin-top: 1rem; display: none;">Prašome pasirinkti galimą datą.</p>
                </div>
            </div>

            <!-- SECTION 5: DETAILS -->
            <div class="opa-section" id="sec-details">
                <div class="opa-section-header">
                    <div class="opa-sec-num">5</div>
                    <h2 class="opa-sec-title"><?php esc_html_e('Galutiniai duomenys', 'opa-booking'); ?></h2>
                </div>
                <div class="opa-section-content">
                    <div style="margin-bottom: 1.5rem; display: flex; gap: 1.5rem; font-weight: 500; color: #475569;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="customer_type" value="natural" checked style="width:18px;height:18px;accent-color:#16a34a;">
                            Fizinis asmuo
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="customer_type" value="legal" style="width:18px;height:18px;accent-color:#16a34a;">
                            Juridinis asmuo
                        </label>
                    </div>

                    <div class="opa-input-grid">
                        <div class="opa-input-group" id="grp_customer_name" style="grid-column: 1 / -1;">
                            <label class="opa-label" id="lbl_customer_name">Jūsų Vardas Pavardė</label>
                            <input type="text" name="customer_name" id="inp_customer_name" class="opa-input-pro" required>
                        </div>
                        
                        <div class="opa-input-group" id="grp_company_code" style="display:none;">
                            <label class="opa-label">Įmonės kodas</label>
                            <input type="text" name="company_code" id="inp_company_code" class="opa-input-pro">
                        </div>
                        <div class="opa-input-group" id="grp_person_in_charge" style="display:none;">
                            <label class="opa-label">Atsakingo asmens Vardas Pavardė</label>
                            <input type="text" name="person_in_charge" id="inp_person_in_charge" class="opa-input-pro">
                        </div>

                        <div class="opa-input-group">
                            <label class="opa-label">El. paštas</label>
                            <input type="email" name="customer_email" class="opa-input-pro" required>
                        </div>
                        <div class="opa-input-group">
                            <label class="opa-label">Jūsų telefono Nr.</label>
                            <input type="text" name="customer_phone" class="opa-input-pro" required>
                        </div>
                        <div class="opa-input-group" style="grid-column: 1 / -1;">
                            <label class="opa-label">Visas paslaugos adresas</label>
                            <input type="text" name="address_line" class="opa-input-pro" placeholder="pvz. Pagrindinė g. 123, 4B butas, Miestas" required>
                        </div>
                        <div class="opa-input-group" style="grid-column: 1 / -1;">
                            <label class="opa-label">Jūsų individuali žinutė (Neprivaloma)</label>
                            <textarea name="delivery_notes" class="opa-input-pro" rows="3" placeholder="Specialios instrukcijos..."></textarea>
                        </div>
                    </div>
                    
                    <div id="opa-error-msg" style="display:none; padding: 1rem; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 8px; margin-top: 1.5rem;"></div>

                    <div style="margin-top: 2rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                        <input type="checkbox" id="opa_terms_cb" required style="margin-top: 0.25rem; width: 16px; height: 16px; accent-color: #16a34a; cursor: pointer;">
                        <label for="opa_terms_cb" style="font-size: 0.9rem; color: #475569; line-height: 1.5; cursor: pointer;" id="opa_terms_label">
                            <!-- Injected via JS -->
                        </label>
                    </div>

                    <div style="margin-top: 2rem;">
                        <button type="submit" class="opa-btn-submit" id="opa_btn_submit">
                            <?php esc_html_e('Patvirtinti užsakymą', 'opa-booking'); ?>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>

                    <!-- Honeypot & Idempotency -->
                    <input type="text" name="opa_website" style="display:none;" tabindex="-1" autocomplete="off">
                    <input type="hidden" name="idempotency_key" id="opa_idemp_key">
                </div>
            </div>
            
            <!-- SUCCESS STATE -->
            <div class="opa-section" id="sec-success" style="display: none; text-align:center; padding: 3rem 1.5rem; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto;">
                <div style="width: 72px; height: 72px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                    <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--opa-text);"><?php esc_html_e('Užsakymas Patvirtintas!', 'opa-booking'); ?></h2>
                <p style="color: var(--opa-text-muted); font-size: 1.125rem; margin-bottom: 2rem;"><?php esc_html_e('Užsakymo Nr.', 'opa-booking'); ?> <strong id="opa_final_booking_id" style="color: var(--opa-text);"></strong></p>
                
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; text-align: left; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--opa-text); margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;"><?php esc_html_e('Užsakymo Informacija', 'opa-booking'); ?></h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.95rem;">
                        <div>
                            <span style="color: var(--opa-text-muted); display: block; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;"><?php esc_html_e('Data', 'opa-booking'); ?></span>
                            <strong id="opa_final_date" style="color: var(--opa-text);"></strong>
                        </div>
                        <div>
                            <span style="color: var(--opa-text-muted); display: block; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;"><?php esc_html_e('Kaina', 'opa-booking'); ?></span>
                            <strong id="opa_final_price" style="color: #16a34a; font-size: 1.1rem;"></strong>
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <span style="color: var(--opa-text-muted); display: block; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;"><?php esc_html_e('Paslauga', 'opa-booking'); ?></span>
                            <strong id="opa_final_service" style="color: var(--opa-text);"></strong>
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <span style="color: var(--opa-text-muted); display: block; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;"><?php esc_html_e('Adresas', 'opa-booking'); ?></span>
                            <strong id="opa_final_address" style="color: var(--opa-text);"></strong>
                        </div>
                    </div>
                </div>

                <p style="color: var(--opa-text-muted); font-size: 0.95rem; margin-bottom: 2rem;">
                    <?php esc_html_e('Išsiuntėme užsakymo patvirtinimą į jūsų el. paštą. Jei turite klausimų, susisiekite su mumis.', 'opa-booking'); ?>
                </p>

                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="#" id="opa_btn_download_invoice" target="_blank" class="opa-btn-submit" style="display:inline-flex; align-items: center; justify-content: center; width:auto; padding: 0.75rem 1.5rem; background: var(--opa-primary); color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; gap: 0.5rem; border: none;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <?php esc_html_e('Atsisiųsti Sąskaitą PDF', 'opa-booking'); ?>
                    </a>
                    <a href="<?php echo esc_url( home_url() ); ?>" class="opa-btn-submit" style="display:inline-flex; align-items: center; justify-content: center; width:auto; padding: 0.75rem 1.5rem; background: #f1f5f9; color: #475569; text-decoration: none; border-radius: 8px; font-weight: 600; border: none;">
                        <?php esc_html_e('Grįžti į Pradžią', 'opa-booking'); ?>
                    </a>
                </div>
            </div>

        </form>
    </div>

    <!-- Right Sidebar Preview -->
    <div class="opa-app-sidebar">
        <div class="opa-preview-panel">
            <div class="opa-preview-img-wrapper">
                <img id="opa_preview_img" src="" style="display:none;">
                <button type="button" id="opa_preview_info_btn" title="More Info" style="display:none;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><?php esc_html_e('Išsami informacija', 'opa-booking'); ?></span>
                </button>
                <div id="opa_preview_placeholder">
                    <svg width="48" height="48" fill="none" stroke="#cbd5e1" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span style="display:block; margin-top:0.5rem; color:#94a3b8; font-size:0.875rem;">Pasirinkite konteinerį</span>
                </div>
            </div>
            <div class="opa-preview-details">
                <h3 id="opa_preview_title"><?php esc_html_e('Užsakymo suvestinė', 'opa-booking'); ?></h3>
                <div id="opa_preview_price" class="opa-preview-price">€0.00</div>
                
                <div class="opa-preview-list">
                    <div class="opa-ps-row"><span>Miestas:</span><strong id="sum_city">—</strong></div>
                    <div class="opa-ps-row"><span>Atliekos:</span><strong id="sum_waste">—</strong></div>
                    <div class="opa-ps-row"><span>Dydis:</span><strong id="sum_container">—</strong></div>
                    <div class="opa-ps-row"><span>Data:</span><strong id="sum_date">—</strong></div>
                </div>
                
                <div class="opa-preview-list" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #cbd5e1;">
                    <div class="opa-ps-row" style="color: #64748b; font-size: 0.85rem;"><span>Kaina be PVM:</span><strong id="sum_base_price">—</strong></div>
                    <div class="opa-ps-row" style="color: #64748b; font-size: 0.85rem;"><span>PVM (<span id="sum_tax_rate">0</span>%):</span><strong id="sum_tax_amount">—</strong></div>
                    <div class="opa-ps-row" style="margin-top: 0.5rem; color: #0f172a; font-size: 1.125rem;"><span>Bendra suma:</span><strong id="sum_grand_total">—</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Slide-over Modal -->
<div class="opa-modal-overlay" id="opa_slide_overlay"></div>
<div class="opa-slide-over" id="opa_slide_panel">
    <div class="opa-slide-over-header">
        <h3 class="opa-slide-over-title" id="opa_slide_title"><?php esc_html_e('Išsami informacija', 'opa-booking'); ?></h3>
        <button class="opa-slide-over-close" id="opa_slide_close">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <div class="opa-slide-over-content">
        <img src="" id="opa_slide_img" class="opa-slide-over-image opa-hidden">
        <div id="opa_slide_desc" style="line-height: 1.6; color: var(--opa-text); font-size:1rem;"></div>
    </div>
</div>
