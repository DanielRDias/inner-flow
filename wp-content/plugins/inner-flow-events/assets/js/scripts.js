/**
 * Frontend JavaScript for hiking events
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Event registration handling
    $('.ife-register-btn').on('click', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const eventId = $btn.data('event-id');
        const status = 'joining';
        const joinStopId = $('#ife_join_stop').val();
        
        if (!confirm(ifeData.strings.confirmJoin || 'Are you sure you want to join this event?')) {
            return;
        }
        
        $.ajax({
            url: ifeData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'ife_register_event',
                nonce: ifeData.nonce,
                event_id: eventId,
                status: status,
                join_stop_id: joinStopId
            },
            beforeSend: function() {
                $btn.prop('disabled', true).text('Processing...');
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message || 'Registration successful!');
                    window.location.reload();
                } else {
                    alert(response.data.message || 'Registration failed.');
                    $btn.prop('disabled', false).text('Register for Event');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $btn.prop('disabled', false).text('Register for Event');
            }
        });
    });
    
    // Unregister from event
    $('.ife-unregister-btn').on('click', function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const eventId = $btn.data('event-id');
        
        if (!confirm(ifeData.strings.confirmLeave || 'Are you sure you want to leave this event?')) {
            return;
        }
        
        $.ajax({
            url: ifeData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'ife_unregister_event',
                nonce: ifeData.nonce,
                event_id: eventId
            },
            beforeSend: function() {
                $btn.prop('disabled', true).text('Processing...');
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message || 'Unregistered successfully.');
                    window.location.reload();
                } else {
                    alert(response.data.message || 'Failed to unregister.');
                    $btn.prop('disabled', false).text('Unregister');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $btn.prop('disabled', false).text('Unregister');
            }
        });
    });
    
    // Load participants
    function loadParticipants(eventId) {
        $.ajax({
            url: ifeData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'ife_get_participants',
                nonce: ifeData.nonce,
                event_id: eventId
            },
            success: function(response) {
                if (response.success) {
                    displayParticipants(response.data.participants, response.data.stops);
                }
            }
        });
    }
    
    function displayParticipants(participants, stops) {
        const $container = $('#ife-participants-list');
        if (!$container.length) return;
        
        $container.empty();
        
        // Filter only joining participants
        const joiningParticipants = participants.filter(p => p.status === 'joining');
        const totalCount = joiningParticipants.length;
        
        if (totalCount === 0) {
            $container.html('<p>' + (ifeData.strings.noParticipants || 'No participants yet.') + '</p>');
            return;
        }
        
        // Group participants by starting point
        const groups = {};
        
        // Initialize "Start (Beginning)" group
        groups['start'] = {
            name: ifeData.strings.startBeginning || 'Start (Beginning)',
            participants: [],
            order: 0
        };
        
        // Initialize stop point groups
        if (stops && stops.length > 0) {
            stops.forEach(function(stop) {
                groups['stop_' + stop.id] = {
                    name: stop.name,
                    participants: [],
                    order: stop.order
                };
            });
        }
        
        // Assign participants to groups
        joiningParticipants.forEach(function(participant) {
            if (participant.join_stop_id && groups['stop_' + participant.join_stop_id]) {
                groups['stop_' + participant.join_stop_id].participants.push(participant);
            } else {
                groups['start'].participants.push(participant);
            }
        });
        
        // Build total summary header
        const totalLabel = ifeData.strings.totalParticipants || 'Total Participants';
        let html = `<div class="ife-participants-summary">
            <strong>👥 ${totalLabel}: ${totalCount}</strong>
        </div>`;
        
        // Sort groups by order and display
        const sortedGroups = Object.values(groups).sort((a, b) => a.order - b.order);
        
        sortedGroups.forEach(function(group) {
            if (group.participants.length === 0) return;
            
            const joinAtLabel = ifeData.strings.joinAt || 'Join at';
            html += `
                <div class="ife-participant-group">
                    <div class="ife-group-header">
                        <span class="ife-group-name">📍 ${joinAtLabel}: ${group.name}</span>
                        <span class="ife-group-count">(${group.participants.length})</span>
                    </div>
                    <div class="ife-group-participants">
            `;
            
            group.participants.forEach(function(participant) {
                html += `
                    <div class="ife-participant">
                        <img src="${participant.avatar}" alt="${participant.name}" class="ife-participant-avatar">
                        <div class="ife-participant-info">
                            <strong>${participant.name}</strong>
                        </div>
                    </div>
                `;
            });
            
            html += `
                    </div>
                </div>
            `;
        });
        
        $container.html(html);
    }
    
    // Initialize Google Maps for event display
    if (typeof google !== 'undefined' && google.maps && $('#ife-event-map').length) {
        initializeEventMap();
    }
    
    function initializeEventMap() {
        const mapElement = document.getElementById('ife-event-map');
        const lat = parseFloat(mapElement.dataset.lat);
        const lng = parseFloat(mapElement.dataset.lng);
        
        const map = new google.maps.Map(mapElement, {
            center: { lat: lat, lng: lng },
            zoom: 13
        });
        
        // Main marker
        new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: 'Event Location'
        });
        
        // Route and stop points
        const stops = JSON.parse(mapElement.dataset.stops || '[]');
        if (stops.length > 0) {
            const path = stops.map(stop => ({
                lat: parseFloat(stop.latitude),
                lng: parseFloat(stop.longitude)
            }));
            
            // Draw polyline
            new google.maps.Polyline({
                path: path,
                geodesic: true,
                strokeColor: '#2C5F8D',
                strokeOpacity: 0.8,
                strokeWeight: 3,
                map: map
            });
            
            // Add markers for stops
            stops.forEach((stop, index) => {
                new google.maps.Marker({
                    position: {
                        lat: parseFloat(stop.latitude),
                        lng: parseFloat(stop.longitude)
                    },
                    map: map,
                    label: String(index + 1),
                    title: stop.stop_name
                });
            });
        }
    }
    
    // Auto-load participants if on event page
    const eventId = $('.ife-event-single').data('event-id');
    if (eventId) {
        loadParticipants(eventId);
    }
});
