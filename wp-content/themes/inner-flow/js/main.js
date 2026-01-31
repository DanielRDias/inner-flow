/**
 * Inner Flow Main JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';

    // Mobile menu toggle
    $('.mobile-menu-toggle').on('click', function() {
        $(this).toggleClass('active');
        $('.main-navigation').toggleClass('active');
        
        // Animate hamburger icon
        if ($(this).hasClass('active')) {
            $(this).find('span:nth-child(1)').css('transform', 'rotate(45deg) translate(5px, 5px)');
            $(this).find('span:nth-child(2)').css('opacity', '0');
            $(this).find('span:nth-child(3)').css('transform', 'rotate(-45deg) translate(7px, -6px)');
        } else {
            $(this).find('span').css({'transform': 'none', 'opacity': '1'});
        }
    });

    // Close mobile menu when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('header').length) {
            $('.mobile-menu-toggle').removeClass('active');
            $('.main-navigation').removeClass('active');
            $('.mobile-menu-toggle span').css({'transform': 'none', 'opacity': '1'});
        }
    });

    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(e) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 800, 'swing');
            
            // Close mobile menu after clicking
            $('.mobile-menu-toggle').removeClass('active');
            $('.main-navigation').removeClass('active');
            $('.mobile-menu-toggle span').css({'transform': 'none', 'opacity': '1'});
        }
    });

    // Add scroll effect to header
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 50) {
            $('header').addClass('scrolled');
        } else {
            $('header').removeClass('scrolled');
        }
    });

    // Animate elements on scroll
    function animateOnScroll() {
        $('.event-card, .ife-stop-item').each(function() {
            var elementTop = $(this).offset().top;
            var windowBottom = $(window).scrollTop() + $(window).height();
            
            if (elementTop < windowBottom - 50) {
                $(this).addClass('animated');
            }
        });
    }
    
    $(window).on('scroll', animateOnScroll);
    animateOnScroll(); // Run on page load
});
