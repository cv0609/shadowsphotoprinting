// header js

function openNav() {
    document.getElementById("mySidenavs").style.width = "290px";
}

function closeNav() {
    document.getElementById("mySidenavs").style.width = "0";
}

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