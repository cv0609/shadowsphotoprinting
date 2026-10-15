// header js

function openNav() {
    var nav = document.getElementById("mySidenavs");
    if (nav && (nav.classList.contains("intro") || parseInt(nav.style.width) > 0)) {
        closeNav();
        return;
    }
    if (nav) {
        nav.classList.add("intro");
        nav.style.width = "290px";
    }
}

function closeNav() {
    var nav = document.getElementById("mySidenavs");
    if (nav) {
        nav.classList.remove("intro");
        nav.style.width = "0";
    }
}

$(document).on('click', function (e) {
    var nav = document.getElementById("mySidenavs");
    if (nav && (nav.classList.contains("intro") || parseInt(nav.style.width) > 0)) {
        if (!$(e.target).closest('#mySidenavs, .toggle-bar, .toggle_menu').length) {
            closeNav();
        }
    }
});

//  #button

var btn = $('#button');

$(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
        btn.addClass('show');
    } else {
        btn.removeClass('show');
    }
});

btn.on('click', function (e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: 0 }, '300');
});

// Sticky Navigation
$(document).ready(function () {
    var $navWrapper = $('.header-nav-wrapper');
    var $nav = $('.outside-second');

    if ($nav.length && $navWrapper.length) {
        function updateStickyNav() {
            if ($(window).width() > 991) {
                var scrollTop = $(window).scrollTop();
                var navTop = $navWrapper.offset().top;

                if (scrollTop >= navTop) {
                    if (!$nav.hasClass('is-sticky')) {
                        $navWrapper.css('min-height', $nav.outerHeight() + 'px');
                        $nav.addClass('is-sticky');
                    }
                } else {
                    if ($nav.hasClass('is-sticky')) {
                        $nav.removeClass('is-sticky');
                        $navWrapper.css('min-height', '');
                    }
                }
            } else {
                if ($nav.hasClass('is-sticky')) {
                    $nav.removeClass('is-sticky');
                    $navWrapper.css('min-height', '');
                }
            }
        }

        $(window).on('scroll', updateStickyNav);
        $(window).on('resize', updateStickyNav);
        $(window).on('load', updateStickyNav);
        updateStickyNav();
    }
});

var all_images = [];

document.getElementById('selectfiles').addEventListener('click', function () {
    document.getElementById('fileInput').click();
});

document.getElementById('fileInput').addEventListener('change', function () {
    var files = this.files;
    if (files.length > 0) {
        document.getElementById('uploadfiles').style.display = 'inline-block';
        displaySelectedFiles(files);
        for (var i = 0; i < files.length; i++) {
            all_images.push(files[i]);
        }
    } else {
        // document.getElementById('uploadfiles').style.display = 'none';
        // document.getElementById('selectedFiles').innerHTML = ''; // Clear selected files display
    }
});

function displaySelectedFiles(files) {
    var selectedFilesDiv = document.getElementById('selectedFiles');
    for (var i = 0; i < files.length; i++) {
        selectedFilesDiv.innerHTML += '<p>' + files[i].name + '</p>';
    }
}

document.getElementById('uploadForm').addEventListener('submit', function (event) {
    var form = this;
    var fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.name = 'allimages[]';
    fileInput.multiple = true;
    fileInput.style.display = 'none';

    var dataTransfer = new DataTransfer();
    all_images.forEach(function(file) {
        dataTransfer.items.add(file);
    });
 
    fileInput.files = dataTransfer.files;
    form.appendChild(fileInput);
});