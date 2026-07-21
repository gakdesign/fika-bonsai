(function($) {
  $(function() {

		
    // Passive event listeners for CWV
    $.event.special.touchstart = {
      setup: function(_, ns, handle) {
        this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
      }
    };
    $.event.special.touchmove = {
      setup: function(_, ns, handle) {
        this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
      }
    };
    $.event.special.wheel = {
      setup: function(_, ns, handle) {
        this.addEventListener("wheel", handle, { passive: true });
      }
    };
    $.event.special.mousewheel = {
      setup: function(_, ns, handle) {
        this.addEventListener("mousewheel", handle, { passive: true });
      }
    };

    // YouTube iframe privacy enhancement
    $('iframe[src*="https://www.youtube.com/"]').each(function () {
      this.src = this.src.replace("https://www.youtube.com/", "https://www.youtube-nocookie.com/");
    });

    // Adaptive screen-based content removal
    $(window).on('resize', debounce(function() {
      const windowW = $(window).width();
      if (windowW > 992) {
        $('.show-900').remove();
      } else {
        $('.hide-900').remove();
      }
    }, 150)).trigger('resize');

    // AOS animation setup 
    //AOS.init({ easing: 'ease-in-out-sine', startEvent: 'load', disable: 'mobile' });

    // Page fade-in
    // $('body').hide().fadeIn(500);

    // Smooth scrolling for in-page anchor links
    $('a[href*="#"]:not([href="#"])').click(function(e) {
      if (location.pathname === this.pathname && location.hostname === this.hostname) {
        const target = $(this.hash);
        if (target.length) {
          e.preventDefault();
          $('html, body').animate({ scrollTop: target.offset().top }, 1000);
        }
      }
    });

    // Refresh slick slider on accordion interaction
    $('.accordion-button').on('click', function(e) {
      e.preventDefault();
      $(".side-image").slick('refresh');
    });

    // Mobile menu toggle
    $('#trigger, #trigger-close').on('click', function(e) {
      e.preventDefault();
      $('#slide-div, .mobile-overflow, body, .menu-nav, .hamburger-lineshamburger-lineshamburger-lines, .hamburger').toggleClass('active');
    });

    // Room Booking Off Canvas toggle
    $('#bookings-open, #slide-room-bookings-canvas-close').on('click', function(e) {
      e.preventDefault();
      $('#slide-room-bookings-canvas, .mobile-overflow, body, .menu-nav').toggleClass('active');
    });




    // Desktop menu hover effects
    $(".menu-item-has-children").hover(
      function() {
        $("#menu-main-menu li").addClass("fademe-menu");
        $(".menu-links").addClass("fademe-menu-links");
        $(this).addClass("hover");
      },
      function() {
        $("#menu-main-menu li, .menu-links").removeClass("fademe-menu fademe-menu-links");
        $(this).removeClass("hover");
      }
    );

    // Mobile submenu toggle
    $(".mobile-navigation .sub-menu").hide();
    $(".mobile-navigation li.menu-item").on('click', function(e) {
      if ($(this).find('.sub-menu').length) {
        e.preventDefault();
        $(".mobile-navigation li.menu-item").removeClass("redlinked");
        $(this).find(".sub-menu").slideToggle("fast");
        $(this).addClass("redlinked");
        e.stopPropagation();
      }
    });

    // Slick slider setup
    $('.slider-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      fade: true,
      infinite: true,
      speed: 300,
      asNavFor: '.home-news-slider',
      responsive: [
        {
          breakpoint: 600,
          settings: { arrows: false }
        }
      ]
    });

    // Content_introduction_module slider

    $('.featured-gateway').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      arrows: false,
      fade: false,
      infinite: true,
      speed: 300,
      responsive: [
        {
          breakpoint: 1200,
          settings: { arrows: true, slidesToShow: 2 }
        },
        {
          breakpoint: 768,
          settings: { arrows: true, slidesToShow: 1 }
        }
      ]
    });

    // image_slider_module

    $('.image-slider-module').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      arrows: false,
      fade: false,
      infinite: true,
      autoplay: true,
      autoplaySpeed: 4000,
      speed: 300,
      responsive: [
        {
          breakpoint: 992,
          settings: {slidesToShow: 3 }
        },
        {
          breakpoint: 768,
          settings: {slidesToShow: 1 }
        }
      ]
    });

    // whats_one_module
    $('.whats-on-slider').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      arrows: true,
      fade: false,
      infinite: true,
      speed: 300,
      responsive: [
        {
          breakpoint: 1980,
          settings: {slidesToShow: 3 }
        },
        {
          breakpoint: 1280,
          settings: {slidesToShow: 2 }
        },
        {
          breakpoint: 768,
          settings: {slidesToShow: 1 }
        }
      ]
    });

  // Room Details Slider
  
      $('.details-slider').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      arrows: false,
      fade: false,
      infinite: true,
      speed: 300,
      responsive: [
        {
          breakpoint: 1980,
          settings: {slidesToShow: 3 }
        },
        {
          breakpoint: 1280,
          settings: {slidesToShow: 2, arrows: true, }
        },
        {
          breakpoint: 768,
          settings: {slidesToShow: 1 }
        }
      ]
    });

  // Gallery Slider
  $('.galleryslider-for').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    adaptiveHeight: true,
    asNavFor: '.galleryslider-nav'
  });

  // Gallery Slider Thumbs
  $('.galleryslider-nav').slick({
    slidesToShow: 3,         
    slidesToScroll: 1,
    asNavFor: '.galleryslider-for',
    dots: false,
    centerMode: false,
    arrows: true,
    focusOnSelect: true,
    vertical: true,
    verticalSwiping: true,
    infinite: false,
    responsive: [
      {
        breakpoint: 992, 
        settings: {
          vertical: false,        
          verticalSwiping: false,
          slidesToShow: 3,   
          arrows: true,         
        }
      },
      {
        breakpoint: 768,
        settings: {
          vertical: false, 
          verticalSwiping: false,
          slidesToShow: 2,       
          arrows: true,       
        }
      },
      {
        breakpoint: 600, 
        settings: {
          vertical: false,        
          verticalSwiping: false,
          slidesToShow: 3,        
          arrows: true,    
        }
      },
    ]
  });


    // Smooth scroll for links with class .smooth
    $("a.smooth").on('click', function(e) {
      if (this.hash !== "") {
        e.preventDefault();
        const hash = this.hash;
        $('html, body').animate({
          scrollTop: $(hash).offset().top
        }, 800, function() {
          window.location.hash = hash;
        });
      }
    });

    // Footer spacing logic
    function adjustFooter() {
      const footerHeight = $('#site_footer').outerHeight();
      const isShort = footerHeight < window.innerHeight;
      $('body').toggleClass('fixed-footer', isShort);
      $('#overall-wrapper').css('padding-bottom', isShort ? footerHeight : '0');
    }

    $(window).on('resize', debounce(adjustFooter, 150));
    adjustFooter();

    // Dark mode toggle logic
    const html = document.documentElement;
    const switches = document.querySelector(".switches");
    if (switches) {
      const inputs = switches.querySelectorAll("input");

      const setTheme = (theme) => {
        if (theme === "dark") {
          html.setAttribute("data-bs-theme", "dark");
          html.classList.add("dark-mode");
          html.classList.remove("light-mode");
          localStorage.setItem("bs-dark-mode", "true");
        } else {
          html.removeAttribute("data-bs-theme");
          html.classList.add("light-mode");
          html.classList.remove("dark-mode");
          localStorage.removeItem("bs-dark-mode");
        }
      };

      const selectedTheme = localStorage.getItem("bs-selected-radio");
      if (selectedTheme) {
        switches.querySelector(`#${selectedTheme}`).checked = true;
      }

      if (localStorage.getItem("bs-dark-mode")) {
        setTheme("dark");
      }

      const handleInputChange = (e) => {
        const id = e.target.id;
        if (id === "dark" || (id === "auto" && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
          setTheme("dark");
        } else {
          setTheme("light");
        }
        localStorage.setItem("bs-selected-radio", id);
      };

      const handleMediaChange = (e) => {
        if (switches.querySelector('#auto').checked) {
          setTheme(e.matches ? "dark" : "light");
        }
      };

      window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", handleMediaChange);
      inputs.forEach(input => input.addEventListener("input", handleInputChange));
    }

    // Utility: debounce function
    function debounce(func, wait) {
      let timeout;
      return function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, arguments), wait);
      };
    }

    // Simple Accordion Style - Not Bootstrap

    $('.accordion-header').click(function () {
      const $header = $(this);
      const $content = $header.next('.accordion-content');
      // Close all panels & remove active state
      $('.accordion-content').not($content).slideUp();
      $('.accordion-header').not($header).removeClass('active');
      // Toggle clicked one
      $content.stop(true, true).slideToggle();
      // Toggle active class on header
      $header.toggleClass('active');
    });


    // home only navigation shows on scroll

      function toggleHeader() {
        if ($(window).width() < 1200) {
          var scrollPoint = $(window).height(); // 100vh
          if ($(window).scrollTop() >= scrollPoint) {
            $('.home header').addClass('active');
          } else {
            $('.home header').removeClass('active');
          }
        } else {
          $('.home header').removeClass('active');
        }
      }

      // Run on load, scroll and resize
      $(window).on('load scroll resize', toggleHeader);



  });
})(jQuery);
