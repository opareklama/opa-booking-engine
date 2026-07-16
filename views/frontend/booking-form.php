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
                    <div class="opa-input-grid">
                        <div class="opa-input-group">
                            <label class="opa-label">Vardas ir pavardė</label>
                            <input type="text" name="customer_name" class="opa-input-pro" required>
                        </div>
                        <div class="opa-input-group">
                            <label class="opa-label">El. pašto adresas</label>
                            <input type="email" name="customer_email" class="opa-input-pro" required>
                        </div>
                        <div class="opa-input-group" style="grid-column: 1 / -1;">
                            <label class="opa-label">Visas paslaugos adresas</label>
                            <input type="text" name="address_line" class="opa-input-pro" placeholder="pvz. Pagrindinė g. 123, 4B butas, Miestas" required>
                        </div>
                        <div class="opa-input-group" style="grid-column: 1 / -1;">
                            <label class="opa-label">Telefono numeris</label>
                            <input type="text" name="customer_phone" class="opa-input-pro" required>
                        </div>
                        <div class="opa-input-group" style="grid-column: 1 / -1;">
                            <label class="opa-label">Pristatymo pastabos (Neprivaloma)</label>
                            <textarea name="delivery_notes" class="opa-input-pro" rows="3" placeholder="Specialios instrukcijos..."></textarea>
                        </div>
                    </div>
                    
                    <div id="opa-error-msg" style="display:none; padding: 1rem; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 8px; margin-top: 1.5rem;"></div>

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
            <div class="opa-section" id="sec-success" style="display: none; text-align:center; padding: 4rem 2rem;">
                <div style="width: 80px; height: 80px; background: #16a34a; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 style="font-size: 2rem; margin-bottom: 0.5rem; color: var(--opa-text);"><?php esc_html_e('Užsakymas patvirtintas!', 'opa-booking'); ?></h2>
                <p style="color: var(--opa-text-muted); font-size: 1.125rem;"><?php esc_html_e('Užsakymo Nr. ', 'opa-booking'); ?><strong id="opa_final_booking_id" style="color: var(--opa-text);"></strong></p>
                <div style="margin-top: 2rem;">
                    <a href="#" id="opa_btn_download_invoice" target="_blank" class="opa-btn-submit" style="display:inline-flex; width:auto; padding: 1rem 2rem;"><?php esc_html_e('Atsisiųsti sąskaitos faktūros PDF', 'opa-booking'); ?></a>
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
