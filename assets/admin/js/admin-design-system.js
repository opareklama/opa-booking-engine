/**
 * Opa Booking Engine - Enterprise Design System JS
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // --- Slide-over Manager ---
    window.OpaSlideOver = {
        open(id) {
            const slideOver = document.getElementById(id);
            const overlay = document.getElementById('opa-slide-over-overlay');
            if (slideOver && overlay) {
                overlay.classList.add('is-active');
                slideOver.classList.add('is-active');
                
                // Focus trap logic could be added here
            }
        },
        close(id) {
            const slideOver = document.getElementById(id);
            const overlay = document.getElementById('opa-slide-over-overlay');
            if (slideOver && overlay) {
                slideOver.classList.remove('is-active');
                overlay.classList.remove('is-active');
            }
        },
        closeAll() {
            document.querySelectorAll('.opa-slide-over.is-active').forEach(el => {
                el.classList.remove('is-active');
            });
            const overlay = document.getElementById('opa-slide-over-overlay');
            if(overlay) overlay.classList.remove('is-active');
        }
    };

    // Global overlay click to close
    const globalOverlay = document.getElementById('opa-slide-over-overlay');
    if (globalOverlay) {
        globalOverlay.addEventListener('click', () => {
            window.OpaSlideOver.closeAll();
        });
    }

    // Escape key to close slide-overs
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            window.OpaSlideOver.closeAll();
        }
    });

    // --- Toast Manager ---
    window.OpaToast = {
        show(message, type = 'info') {
            let container = document.getElementById('opa-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'opa-toast-container';
                container.className = 'opa-toast-container';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            toast.className = `opa-toast ${type}`;
            
            // Icon based on type
            let icon = 'ℹ️';
            if(type === 'success') icon = '✅';
            if(type === 'error') icon = '❌';
            if(type === 'warning') icon = '⚠️';

            toast.innerHTML = `<span>${icon}</span><span>${message}</span>`;
            
            container.appendChild(toast);

            // Trigger animation
            setTimeout(() => {
                toast.classList.add('is-active');
            }, 10);

            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.remove('is-active');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }
    };

    // --- Tab Manager ---
    const tabWrappers = document.querySelectorAll('.opa-tabs');
    tabWrappers.forEach(wrapper => {
        const btns = wrapper.querySelectorAll('.opa-tab-btn');
        const panes = wrapper.querySelectorAll('.opa-tab-pane');

        btns.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-target');
                
                btns.forEach(b => b.classList.remove('is-active'));
                panes.forEach(p => p.classList.remove('is-active'));

                btn.classList.add('is-active');
                const targetPane = wrapper.querySelector(targetId);
                if(targetPane) targetPane.classList.add('is-active');
            });
        });
    });
});

// --- Native WP Media Uploader Integration ---
window.OpaMediaUploader = {
    init(buttonSelector, inputSelector, previewSelector) {
        const buttons = document.querySelectorAll(buttonSelector);
            
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const btn = this;
                    const input = document.querySelector(btn.getAttribute('data-input'));
                    const preview = document.querySelector(btn.getAttribute('data-preview'));

                    // If the media frame already exists, reopen it.
                    if (btn.mediaFrame) {
                        btn.mediaFrame.open();
                        return;
                    }
                    
                    // Create the media frame.
                    btn.mediaFrame = wp.media({
                        title: 'Select or Upload Media',
                        button: {
                            text: 'Use this media'
                        },
                        multiple: false  // Set to true to allow multiple files to be selected
                    });

                    // When an image is selected, run a callback.
                    btn.mediaFrame.on('select', function() {
                        const attachment = btn.mediaFrame.state().get('selection').first().toJSON();
                        
                        if(input) input.value = attachment.id;
                        if(preview) {
                            preview.innerHTML = `<img src="${attachment.url}" alt="" style="max-width: 100px; max-height: 100px; border-radius: 4px;" />`;
                        }
                    });

                btn.mediaFrame.open();
            });
        });
    }
};
