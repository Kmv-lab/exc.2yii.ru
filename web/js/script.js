$(document).ready(function(){
    if(document.body.clientWidth>1024){
    var menu =  $('.wrapper-for-mobile');
    var header =  $('.wrapper-for-mobile .header');
   // menu.css('z-index', '100');
    var next = $('.wrapper-for-mobile + *');
    var height = header.outerHeight();
    var menu_height = menu.find('.menu-top').outerHeight();
        $(window).scroll(function(){
            if ($(this).scrollTop() > height) {
                menu.addClass('stiky');
                next.css('margin-top', menu_height+'px');
            } else {
                menu.removeClass('stiky');
                next.css('margin-top', '0px');
            }
        });
    }

    $('#showMenu').click(function() {
        $('#mainMenu').animate({'width': 'toggle'});
    });

    $('#show-filters').click(function() {
        $('.filters').animate({'height': 'toggle'});
    });

    $(document).ready(function() {
        $("a.gallery").fancybox();
    });

    $('a[href="#"]').click(function(event){
        event.preventDefault();
        return false;
    });

    $('.elem-of-table-price').click(function() {
        let id = $(this).attr('id');
        id = parseInt(id.replace(/\D+/g,""));
        $('#prices'+id).animate({'height': 'toggle'});
    });


    $('form#testi').on('beforeSubmit', function () {
        var form = $(this);
        jQuery.ajax({
            url: form.attr('action'),
            type: "POST",
            dataType: "json",
            data: form.serialize(),
            success: function(response) {
                $('.add-review.el-form-review').html('<div>Благодарим Вас за отзыв!</div>')
            }
        });
        return false;
    });
    $('form.contact-form').on('beforeSubmit', function () {
        var form = $(this);
        jQuery.ajax({
            url: form.attr('action'),
            type: "POST",
            dataType: "json",
            data: form.serialize(),
            success: function(response) {
                form.after('<div>Мы скоро с Вами свяжемся</div>');
                form.remove();
            }
        });
        return false;
    });
    $('.reserve .btn').click(function(){
        var name_country = $(this).parents('.item').find('.country').text();
        var name_form = $('#modal-form .el-header').text();
        $('#modal-form .form-name').val(name_form+' "'+name_country+'"');
    });

    $('.btn-one-phone').click(function(){
        var $this = $(this);
        jQuery.ajax({
            url: '/site/sendphone/',
            type: "POST",
            dataType: "json",
            data: 'phone='+$('.one-phone').val(),
            success: function(response) {
                $this.after('<div>Мы скоро с Вами свяжемся</div>');
                $this.remove();
                $('.one-phone').remove();
            }
        });
    });

    $('#testi-phone, .phonemask').inputmask("+7 (999) 999-99-99",{
        //'clearMaskOnLostFocus': false,
        'autoUnmask': false
    });
    $('.selectBox').SumoSelect();
		//placeholder: 'This is a placeholder',
		//csvDispCount: 3

// клик по мобильному бутерброду
    $('a.burger').click(function(){
        $(this).toggleClass('close');
        $('.menu-top .menu').toggleClass('opened');
    })


//  кнопка контакты в шапке
    $('.header .links .show-contacts').click(function(){
        $('.header .offices').toggleClass('opened');
        $('.header .links .contacts').toggleClass('show');
        $(this).toggleClass('on');
    })
// клик по стрелкам моб меню
    $('.menu-top .menu .item > a .arrow').click(function(event){
        $(this).toggleClass('on');
        $(this).parent().parent().find('>.lvl-2').toggleClass('show');
        $(this).parent().parent().find('>.lvl-3').toggleClass('show');
        event.preventDefault()
    })

    let i = 1;
    $.each($('.el-inner-owl'), function() {
        $(this).addClass('carusel'+i++);
    });
    
    i = 1;
    $.each($('.el-inner-owl'), function() {
        $('.carusel'+i++).owlCarousel({
            loop:false,
            margin:47,
            nav:false,
            navText: ['',''],
            dots:true,
            items:4,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2,
                    nav:false
                },
                768:{items:3},
                1025:{items:4,margin:30},
                1200:{items:4,margin:30},
            }
        });
        console.log("sss");
    });


    if(document.body.clientWidth>=769){
        k = $('.slider-main .owl-carousel').owlCarousel({
            loop:false,
            margin:0,
            nav:false,
            navText: ['',''],
            dots:true,
            items:1,
            margin:10,
        });
    }

    if(document.body.clientWidth>=769 && document.body.clientWidth<=1024){
        $('.reviews-block .reviews').owlCarousel({
            loop:false,
            margin:0,
            nav:false,
            navText: ['',''],
            dots:true,
            items:1,
            margin:10,
        });
    }
    if(document.body.clientWidth<=768){
        $('.benefits-block .items').owlCarousel({
            loop:false,
            margin:0,
            nav:false,
            navText: ['',''],
            dots:true,
            items:1,
            margin:10,
        });
        $('.reviews-block .reviews').owlCarousel({
            loop:false,
            margin:0,
            nav:false,
            navText: ['',''],
            dots:true,
            items:1,
            margin:10,
        });
        

    }


    /*
    $('.services-slider').owlCarousel({
        loop:false,
        margin:60,
        nav:true,
        navText: ['',''],
        dots:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:3
            }
        }
    });

    $('.partners-slider').owlCarousel({
        loop:false,
        autoplay:true,
        margin:60,
        nav:true,
        navText: ['',''],
        dots:false,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:5
            }
        }
    });
    */
})
