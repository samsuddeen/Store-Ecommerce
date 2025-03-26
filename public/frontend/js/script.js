//  scroll text animation
$(function () {
    $(".animated_text").scrollText({
        duration: 2000,
    });
});

// Mobile Nav
jQuery(document).ready(function ($) {
    jQuery('.stellarnav').stellarNav({
        theme: 'light',
        breakpoint: 1024,
        position: 'left'
    });
});
// MObile Nav End

// Side menubar
$(".closebtn, .toggle-btn, #mob-cat").click(function () {
    $("#mySidenav, #mySidenav1, body, .stellarnav").toggleClass("active");
});

$(".closebtn, .toggle-btn").click(function () {
    $(".stellarnav").css("display", "block");
});
//   Sideabr End 

// Fixed Header 
$(window).scroll(function () {
    var scroll = $(window).scrollTop();

    if (scroll >= 400) {
        $(".scroll-header").addClass("sticky");
    } else {
        $(".scroll-header").removeClass("sticky");
    }
});
// Fixed Header End  


$('.selectedProductsCarousel').owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: ["<i class='las la-angle-left'></i>", "<i class='las la-angle-right'></i>"],
    responsive: {
        0: {
            items: 1,
            // margin: 10,
        },
        576: {
            items: 1,
            // margin: 10,
        },
        768: {
            items: 3,
        },
        992: {
            items: 4,
        },
        1025: {
            items: 6,
        },
        1200: {
            items: 6,
        },
    }, 
});

//   Product
$(".product-carousel").owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: ["<i class='las la-angle-left'></i>", "<i class='las la-angle-right'></i>"],
    responsive: {
        0: {
            items: 2,
            margin: 10,
        },
        576: {
            items: 3,
            margin: 10,
        },
        768: {
            items: 6,
        },
        992: {
            items: 6,
        },
        1200: {
            items: 6,
        },
    },
});
// Product End 


//   Product
$(".bulk-carousel").owlCarousel({
    loop: true,
    margin: 10,
    dots: false,
    nav: true,
    navText: ["<i class='las la-angle-left'></i>", "<i class='las la-angle-right'></i>"],
    responsive: {
        0: {
            items: 1,
        },
        575: {
            items: 2,
        },
        767: {
            items: 2,
        },
        1025: {
            items: 3,
        },
        1200: {
            items: 3,
        },
    },
});
// Product End 

//   Product
$(".category-carousel").owlCarousel({
    loop: true,
    margin: 10,
    dots: false,
    nav: true,
    navText: ["<i class='las la-angle-left'></i>", "<i class='las la-angle-right'></i>"],
    responsive: {
        0: {
            items: 1,
        },
        575: {
            items: 2,
        },
        767: {
            items: 2,
        },
        1025: {
            items: 3,
        },
        1200: {
            items: 5,
        },
    },
});
// Product End 

// Recent Product 
$("#recent_product").owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: ["<i class='las la-angle-left'></i>", "<i class='las la-angle-right'></i>"],
    responsive: {
        0: {
            items: 1,
        },
        768: {
            items: 2,
        },
        992: {
            items: 3,
        },
        1025: {
            items: 3,
        },
        1200: {
            items: 4,
        },
    },
});
// Recent Product End 


// Related Product 
$("#related-product").owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: ["<i class='las la-angle-left'></i>", "<i class='las la-angle-right'></i>"],
    responsive: {
        0: {
            items: 1,
            margin: 10,
        },
        576: {
            items: 1,
            margin: 10,
        },
        768: {
            items: 3,
        },
        992: {
            items: 4,
        },
        1025: {
            items: 4,
        },
        1200: {
            items: 5,
        },
    },
});
// Related Product End 




$('#selectedProductsCarousel').owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: ["<i class='las la-angle-left'></i>", "<i class='las la-angle-right'></i>"],
    responsive: {
        0: {
            items: 1,
            // margin: 10,
        },
        576: {
            items: 1,
            // margin: 10,
        },
        768: {
            items: 3,
        },
        992: {
            items: 4,
        },
        1025: {
            items: 6,
        },
        1200: {
            items: 6,
        },
    }, 
});

$('.categoryCarousel').owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    autoplay:true,
    autoplayTimeout:1500,
    autoplayHoverPause:true,
    nav: true,
    navText: ["<i class='las la-angle-left'></i>", "<i class='las la-angle-right'></i>"],
    responsive: {
        0: {
            items: 2,
            margin: 10,
        },
        576: {
            items: 2,
            margin: 10,
        },
        768: {
            items: 3,
        },
        992: {
            items: 4,
        },
        1025: {
            items: 6,
        },
        1200: {
            items: 6,
        },
    }, 
});



$('#latestProductsCarousel').owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: ["<i class='las la-angle-left'></i>", "<i class='las la-angle-right'></i>"],
    responsive: {
        0: {
            items: 1,
            margin: 10,
        },
        576: {
            items: 1,
            margin: 10,
        },
        768: {
            items: 3,
        },
        992: {
            items: 4,
        },
        1025: {
            items: 6,
        },
        1200: {
            items: 6,
        },
    }, 
});



$('#SpecialOfferCarousel').owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: ["<i class='las la-angle-left'></i>", "<i class='las la-angle-right'></i>"],
    responsive: {
        0: {
            items: 2,
            margin: 10,
        },
        576: {
            items: 2,
            margin: 10,
        },
        768: {
            items: 3,
        },
        992: {
            items: 4,
        },
        1025: {
            items: 6,
        },
        1200: {
            items: 6,
        },
    }, 
})
// Store Product 
$(".seller_store").owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: ["<i class='las la-angle-left'></i>", "<i class='las la-angle-right'></i>"],
    responsive: {
        0: {
            items: 2,
            margin: 10,
        },
        576: {
            items: 2,
            margin: 10,
        },
        768: {
            items: 3,
        },
        992: {
            items: 3,
        },
        1025: {
            items: 4,
        },
        1200: {
            items: 4,
        },
    },
});
// Store Product End 


//   Clients
$("#suppliers_sliders").owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: ["<i class='las la-angle-left'></i>", "<i class='las la-angle-right'></i>"],
    responsive: {
        0: {
            items: 2,
        },
        600: {
            items: 5,
        },
        1000: {
            items: 8,
        },
    },
});
// Clients End 

$(window).scroll(function () {
    var size = $(window).width();
    if (size > 1199) {
        var scroll = $(window).scrollTop();
        if (scroll > 150) {
            $(document).find("#header_wrapper").addClass("scroll_fixed");
        } else {
            $(document).find("#header_wrapper").removeClass("scroll_fixed");
        }
    }
});

// mobile navigation toggle
$(".mobile_nav_button").click(function () {
    $(document).find(".mobile_nav").addClass("toggle_in_mobile");
});
$(".nav_closer").click(function () {
    $(document).find(".mobile_nav").removeClass("toggle_in_mobile");
});

// mobile category slide
$("#mobile_cat_slider").owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    responsiveClass: true,
    responsive: {
        0: {
            items: 4,
        },
        600: {
            items: 5,
        },
        1000: {
            items: 9,
            loop: true,
        },
    },
});

// counter product counting
$(document).ready(function () {
    $(".count").prop("disabled", true);
    $(document).on("click", ".plus", function () {
        var quantity = $(this).data('id');

        $(".count").val(parseInt($(".count").val()) + 1);
        if ($(".count").val() >= quantity) {
            $(".count").val(quantity);
        }
    });
    $(document).on("click", ".minus", function () {
        $(".count").val(parseInt($(".count").val()) - 1);
        if ($(".count").val() <= 1) {
            $(".count").val(1);
        }
    });
});
// counter product counting End

// *******************************************
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(".image-upload-wrap").hide();

            $(".file-upload-image").attr("src", e.target.result);
            $(".file-upload-content").show();

            $(".image-title").html(input.files[0].name);
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        removeUpload();
    }
}

function removeUpload() {
    $(".file-upload-input").replaceWith($(".file-upload-input").clone());
    $(".file-upload-content").hide();
    $(".image-upload-wrap").show();
}
$(".image-upload-wrap").bind("dragover", function () {
    $(".image-upload-wrap").addClass("image-dropping");
});
$(".image-upload-wrap").bind("dragleave", function () {
    $(".image-upload-wrap").removeClass("image-dropping");
});

// *******************************************
// search images search toggle
$(".search_holder i").click(function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(".picture_upload_search").toggle();
});
$("body").click(function () {
    $(".picture_upload_search").hide();
});
// **************************************************

$(".sub_nav_menu > li").has(".nav_mega_menu").addClass("add_arrow");
// **********************************************************************

$(".switch_list").click(function () {
    $(document)
        .find(".util_card")
        .parent()
        .removeClass("col-lg-3")
        .addClass("col-lg-12 grid_change");
    $(document)
        .find(".util_card")
        .parent()
        .removeClass("col-md-6")
        .addClass("col-md-12");
});
$(".switch_grid").click(function () {
    $(document)
        .find(".util_card")
        .parent()
        .removeClass("col-lg-12 grid_change")
        .addClass("col-lg-3");
    $(document)
        .find(".util_card")
        .parent()
        .removeClass("col-md-12")
        .addClass("col-md-6");
});

$(document).ready(function () {
    $(".mobile_nav .item_category ul li")
        .has(".cat_sub_menu")
        .addClass("pointer_arrow");
});

// *************************mobile nave sub menu toggle **********************
var event = "ontouchstart" in window ? "click" : "mouseenter mouseleave";

$(".mobile_nav .item_category ul li a").on(event, function () {
    $(this).parent().toggleClass("open");
});

// ****************************************************************

// Cart Slide Toggle
$(document).ready(function () {
    $('.cart a, .close-btn').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.cart-slide').toggleClass('active');
    });
    $('.cart-slide').click(function (e) {
        e.stopPropagation();
    });
    $('body').click(function () {
        $('.cart-slide').removeClass('active');
    });
    $('.track-order-list').click(function () {
        $('.cart-slide').removeClass('active');
    });
    $('.download-app').click(function () {
        $('.cart-slide').removeClass('active');
    });
    $('.cart a').click(function () {
        $('.downoad-app-wrap').slideUp('fast');
    });
    $('.cart a').click(function () {
        $('.track-order-wrap').slideUp('fast');
    });
});
// Cart Slide Toggle End 


$('.p_details_head a').click(function () {
    $('.p_details_head a i').toggleClass('las');
})


// *********************recent product index****************************

$(".owl_slider").owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    responsiveClass: true,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 1,
        },
        1000: {
            items: 1,
            loop: true,
        },
    },
});


// Images Zoom
$(".images").ezPlus({
    borderSize: 1,
    zoomLens:false,
    lensSize:10,
    // scrollZoom:true,
    borderColour:'#e9e9e9',
});
// Images Zoom End

// Thumb Slider
$(window).load(function () {
    // The slider being synced must be initialized first
    $("#carousels").flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 80,
        itemMargin: 10,
        asNavFor: "#sliders",
    });

    $("#sliders").flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: "#carousels",
    });




});
// Thumb Slider End

$(".sideBar_menu > li").each(function () {
    $(this).click(function () {
        // $('.sideBar_menu > li').removeClass('visible_me');
        if ($(this).has(".sidebar_sub_Menu")) {
            $(this).toggleClass("visible_me");
        }
    });
});

// **********************************************************************************

$("ul.sideBar_menu li").has(".sidebar_sub_Menu").addClass("this_arrow");

// **********************************************************************************




// Download App
$(document).ready(function () {
    $('.download-app').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.downoad-app-wrap').slideToggle('fast');
    });
    $('.downoad-app-wrap').click(function (e) {
        e.stopPropagation();
    });
    $('body').click(function () {
        $('.downoad-app-wrap').slideUp('fast');
    });
    $('.download-app').click(function () {
        $('.track-order-wrap').slideUp('fast');
    });
});
// Download App End 

// Track Order 
$(document).ready(function () {
    $('.track-order-list').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.track-order-wrap').slideToggle('fast');
    });
    $('.track-order-wrap').click(function (e) {
        e.stopPropagation();
    });
    $('body').click(function () {
        $('.track-order-wrap').slideUp('fast');
    });
    $('.track-order-list').click(function () {
        $('.downoad-app-wrap').slideUp('fast');
    });
});
// Track Order End 



// Mobile Filter 
$('.mobile-filters, .cat-close').click(function () {
    $('.cat-filter').toggleClass('active');
})
// Mobile Filter End 


// Dashboard 
$('.dash-toggle').click(function () {
    $('.dashboard_sidebar, .dashboard-main-wrapper').toggleClass('open');
})
// Dashboard End 



// Scroll Top Js
$(function () {
    // Scroll Event
    $(window).on("scroll", function () {
        var scrolled = $(window).scrollTop();
        if (scrolled > 600) $(".go-top").addClass("active");
        if (scrolled < 600) $(".go-top").removeClass("active");
    });
    // Click Event
    $(".go-top").on("click", function () {
        $("html, body").animate({ scrollTop: "0" }, 300);
    });
});

// WOW Animation JS
if ($(".wow").length) {
    var wow = new WOW({
        mobile: false,
    });
    wow.init();
}
// Scroll Top Js ENd



// Gallery
$(document).ready(function () {
    $('.lightgallery').lightGallery();
});
// Gallery End


// Notification Box 

$(document).ready(function () {
    $('.notification-box-item').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.notification-box').slideToggle('fast');
    });
    $('.notification-box').click(function (e) {
        e.stopPropagation();
    });
    $('body').click(function () {
        $('.notification-box').slideUp('fast');
    });
    $('.cart a').click(function () {
        $('.notification-box').slideUp('fast');
    });
});
// Notification Box End 



// Increase Product Box Height
$(document).ready(function () {
    if ($('.enable-del').hasClass('enable-del')) {
        $('.enable-del').closest('.bulk-section').addClass('show-del');
    } else {
        $('.enable-del').closest('.bulk-section').removeClass('show-del');
    }


    if ($('.enable-del').hasClass('enable-del')) {
        $('.enable-del').closest('.overall-product').addClass('show-del');
    } else {
        $('.enable-del').closest('.overall-product').removeClass('show-del');
    }

    if ($('.enable-del').hasClass('enable-del')) {
        $('.enable-del').closest('.recent-viewed-product').addClass('show-del');
    } else {
        $('.enable-del').closest('.recent-viewed-product').removeClass('show-del');
    }

    if ($('.enable-del').hasClass('enable-del')) {
        $('.enable-del').closest('.category-list-section').addClass('show-del');
    } else {
        $('.enable-del').closest('.category-list-section').removeClass('show-del');
    }

    if ($('.enable-del').hasClass('enable-del')) {
        $('.enable-del').closest('#grid').addClass('show-del');
    } else {
        $('.enable-del').closest('#grid').removeClass('show-del');
    }

    if ($('.enable-del').hasClass('enable-del')) {
        $('.enable-del').closest('#list').addClass('show-del');
    } else {
        $('.enable-del').closest('#list').removeClass('show-del');
    }

});
// Increase Product Box Height End



    // Gallery
    $(document).ready(function(){
        $('.review-gallery').lightGallery();
      });
      // Gallery End

