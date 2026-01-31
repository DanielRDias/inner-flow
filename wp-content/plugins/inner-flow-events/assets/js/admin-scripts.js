/**
 * Admin JavaScript for hiking events
 */

jQuery(document).ready(function($) {
    'use strict';
    
    let stopCounter = $('.ife-stop-point').length;
    
    // Google Maps embed preview
    let previewTimeout = null;
    const $iframeField = $('#ife_maps_iframe');
    
    if ($iframeField.length) {
        console.log('IFE: Map iframe field found');
        
        $iframeField.on('input paste keyup', function() {
            console.log('IFE: Input detected');
            clearTimeout(previewTimeout);
            previewTimeout = setTimeout(updateMapPreview, 500);
        });
        
        // Trigger initial preview if there's content
        if ($iframeField.val().trim()) {
            updateMapPreview();
        }
    } else {
        console.log('IFE: Map iframe field NOT found');
    }
    
    function updateMapPreview() {
        console.log('IFE: updateMapPreview called');
        const embedCode = $('#ife_maps_iframe').val().trim();
        const $container = $('#ife_map_preview_container');
        const $preview = $('#ife_map_preview');
        const $error = $('#ife_map_error');
        
        console.log('IFE: Container found:', $container.length);
        console.log('IFE: Preview found:', $preview.length);
        console.log('IFE: Embed code length:', embedCode.length);
        
        if (!embedCode) {
            $container.hide();
            if ($error.length) $error.hide();
            return;
        }
        
        // Extract src from iframe code
        const srcMatch = embedCode.match(/src="([^"]+)"/);
        
        if (srcMatch && srcMatch[1]) {
            const embedUrl = srcMatch[1];
            console.log('IFE: Embed URL found:', embedUrl.substring(0, 50) + '...');
            
            // Validate it's a Google Maps embed URL
            if (embedUrl.includes('google.com/maps')) {
                $preview.html(`
                    <iframe 
                        src="${embedUrl}"
                        width="100%" 
                        height="400" 
                        style="border:0; display: block;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                `);
                $container.show();
                if ($error.length) $error.hide();
                console.log('IFE: Preview updated and shown');
            } else {
                $preview.empty();
                $container.show();
                if ($error.length) $error.show();
                console.log('IFE: Invalid URL - not Google Maps');
            }
        } else {
            $preview.empty();
            $container.show();
            if ($error.length) $error.show();
            console.log('IFE: Could not extract src from iframe');
        }
    }
    
    // Add stop point
    $('#ife_add_stop').on('click', function(e) {
        e.preventDefault();
        
        const template = `
            <div class="ife-stop-point" data-index="${stopCounter}">
                <h4>Stop Point ${stopCounter + 1} <button type="button" class="button ife-remove-stop">Remove</button></h4>
                
                <p>
                    <label>Stop Name:</label>
                    <input type="text" name="ife_stops[${stopCounter}][name]" value="" style="width: 100%;">
                </p>
                
                <p>
                    <label>Stop Description:</label>
                    <textarea name="ife_stops[${stopCounter}][description]" rows="3" style="width: 100%;"></textarea>
                </p>
                
                <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px;">Stop Time:</label>
                        <input type="time" name="ife_stops[${stopCounter}][time]" value="" style="width: 100%;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px;">Duration (minutes):</label>
                        <input type="number" name="ife_stops[${stopCounter}][duration]" value="" min="0" style="width: 100%;">
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px;">Latitude:</label>
                        <input type="text" name="ife_stops[${stopCounter}][latitude]" value="" class="ife-stop-lat" style="width: 100%;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px;">Longitude:</label>
                        <input type="text" name="ife_stops[${stopCounter}][longitude]" value="" class="ife-stop-lng" style="width: 100%;">
                    </div>
                </div>
                
                <input type="hidden" name="ife_stops[${stopCounter}][order]" value="${stopCounter}" class="ife-stop-order">
                <hr>
            </div>
        `;
        
        $('#ife_stop_points').append(template);
        stopCounter++;
    });
    
    // Remove stop point
    $(document).on('click', '.ife-remove-stop', function(e) {
        e.preventDefault();
        $(this).closest('.ife-stop-point').remove();
        updateStopOrders();
    });
    
    // Make stop points sortable
    $('#ife_stop_points').sortable({
        handle: 'h4',
        update: function() {
            updateStopOrders();
        }
    });
    
    function updateStopOrders() {
        $('.ife-stop-point').each(function(index) {
            $(this).find('.ife-stop-order').val(index);
            $(this).find('h4').first().html(`Stop Point ${index + 1} <button type="button" class="button ife-remove-stop">Remove</button>`);
        });
    }
});
