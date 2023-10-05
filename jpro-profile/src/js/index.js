$(window).scroll(function(){
    if ($(this).scrollTop() > 300){
        $('.btn_gotop').show();
    } else{
        $('.btn_gotop').hide();
    }
});

$(document).ready(function() {
    expandAll();

    $('.owl-carousel').owlCarousel({
        // stagePadding: 50,
        loop:true,
        items:2,
        // autoplay:true,
        // autoplayTimeout:5000,
        // autoplayHoverPause:true,
        margin:10
    });

    $('#about-info').click(function(){
        location.href = "mailto:woghks778803@gmail.com";
    });
    $('#about-boj').click(function(){
        // window.open('https://www.linkedin.com/in/jae-hwan-jeong-066349222/', '_blank');
        window.open('https://www.acmicpc.net/user/woghks7788', '_blank');
    });
    $('#about-github').click(function(){
        window.open('https://github.com/woghks778803', '_blank');
    });
    $('#about-blog').click(function(){
        window.open('https://www.jpro.website/', '_blank');
    });

    $('.btn_gotop').click(function(){
        $('html, body').animate({scrollTop:0},400);
        return false;
    });

    // Get all of the images that are marked up to fade in
    const images = document.querySelectorAll('.js-lazyload-image');

    const sections = document.querySelectorAll('.section');

    let config = {
        rootMargin: '0px',
        threshold: 0
    };

    let observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
            intersectionHandler(entry);
            } 

        });
        }, config);

        sections.forEach(section => {
        observer.observe(section);
    });

    function intersectionHandler(entry) {
        const current = document.querySelector('.section.active');
        const next = entry.target;
        const header = next.querySelector(".section--header");

        if (current) {
            current.classList.remove('active');
        }
        if (next) {
            console.log(next)
            if(next.dataset.bgcolor == "#949393" || next.dataset.bgcolor == "#333333"){
                $(".about-contain").css("background-color", "#444444");
                $(".wrapper").css("background-color", "#444444");
                $(".card_title").css("color", "#444444");
            }else if(next.dataset.bgcolor == "#386d96"){
                $(".about-contain").css("background-color", "#314a69");
                $(".wrapper").css("background-color", "#314a69");
                $(".card_title").css("color", "#314a69");
            }else if(next.dataset.bgcolor == "#5a9e6c"){
                $(".about-contain").css("background-color", "#134409");
                $(".wrapper").css("background-color", "#134409");
                $(".card_title").css("color", "#134409");
            }
            next.classList.add('active');
            document.body.style.setProperty("--color-bg", next.dataset.bgcolor);
        }
    }

    images.forEach(image => {
        observer.observe(image);
    });

    
});

function expandAll(){
    $(".collapsible-header").addClass("active");
    $(".collapsible").collapsible({accordion: false});
}

function collapseAll(){
    $(".collapsible-header").removeClass(function(){
        return "active";
    });
    $(".collapsible").collapsible({accordion: true});
    $(".collapsible").collapsible({accordion: false});
}

// function port1_expandAll(){
//     $("#ul-port1 .collapsible-header").addClass("active");
//     $("#ul-port1 .collapsible").collapsible({accordion: false});
// }

// function port1_collapseAll(){
//     $("#ul-port1 .collapsible-header").removeClass(function(){
//         return "active";
//     });
//     $("#ul-port1 .collapsible").collapsible({accordion: true});
//     $("#ul-port1 .collapsible").collapsible({accordion: false});
// }

function preloadImage(img) {
    const src = img.getAttribute('data-src');
    if (!src) { return; }
    img.src = src;
}