// Create a root reference
// var storageRef = firebase.storage().ref();

// // Create a reference to 'mountains.jpg'
// var mountainsRef = storageRef.child('mountains.jpg');

// // Create a reference to 'images/mountains.jpg'
// var mountainImagesRef = storageRef.child('images/mountains.jpg');

// ref.put(file).then((snapshot) => {
//     console.log('Uploaded a blob or file!');
// });

$('#btn-upload-image').click(function() {
    showModal('#modal-resident-user-upload-image', show);

    setImage('#modal-resident-user-preview-image', noImage)

    var inp = document.querySelector('input');
    inp.addEventListener('change', function(e){
        var file = this.files[0];
        var reader = new FileReader();
        reader.onload = function(){
            document.getElementById('modal-resident-user-preview-image').src = this.result;
        };
        reader.readAsDataURL(file);
    },false);
    
    $('#btn-submit-upload-image').click(function() {
        var residentImage = $('#resident-user-file-image')[0].files[0];
        var residents = "Residents";
        var imageUploadURL = "";
        showModal('#modal-resident-user-upload-image', hide);
        showModal('#modal-loading', show);
        if (residentImage == undefined) {
            toast(requiredImage, warning);
            hideProgressModal();
            return;
        }
        toast(uploading + residents + imageAttachment, info);
        let uploadingImage = storageReference.child(imagesRef + residentImage.name).put(residentImage);
        uploadingImage.on('state_changed', function(data) {
            toast(residents + s + uploadingProgress, warning);
        }, function(err) {
            toast(err, error);
            hideProgressModal();
            return;
        }, function() {
            uploadingImage.snapshot.ref.getDownloadURL().then(function(imgURL) {
                hideProgressModal();
                imageUploadURL = imgURL;
                toast(residents + successUpload, success);
                showModal('#modal-success-incident-report', show);
                localStorage.setItem("resident_submitted_resident_image_URL", imageUploadURL);
                console.log("Success imageUploadURL ->", imageUploadURL);
            });
        });
    });
});

$('#btn-cancel-upload-image').click(function() {
    showModal('#modal-resident-user-upload-image', hide);
});

$('#btn-continue').click(function() {
    showModal('#modal-success-incident-report', hide);
    window.location.href = "../../index.php";
    
    // window.location.href = "../../../bfpcapstone/index.php";
    // window.location.href = "https://baranggay-magtanggol.online/index.php";
});

function showModal(id, value) {
    $(id).modal(value);
}

function hideProgressModal() {
    setTimeout(function() {
        showModal('#modal-loading', hide);
    }, 690);
}

function emptyInputText(v) {
    for (i=0; i<v.length; i++) {
        $(v[i]).val(e);
    }
}

function emptyDisplayText(v) {
    for (i=0; i<v.length; i++) {
        $(v[i]).html(e);
    }
}

function DOMdisplayImage(elem, id) {
    var image = document.getElementById(id);
    image.src = elem.value;        
}

function setImage(i, v) {
    $(i).attr("src", v);
}

function icon(b, t) {
    $(b).html(t);
}

function button(b, t) {
    $(b).html(t);
}

function input(id, value, flag) {
    $(id).prop(value, flag);
}

function text(i, v) {
    $(i).html(v);
}

function inputText(i, v) {
    $(i).val(v);
}

function setDefaultToZero(v) {
    for (i=0; i<v.length; i++) {
        $(v[i]).val(0);
    }
}

function displayElement(i, f) {
    $(i).css({'display': f});
}

function disabledElement(i, f) {
    if (f) {
        $(i).prop('disabled', true);
        return;
    }
    $(i).removeAttr('disabled');
}

function toast(message, style) {
    if (style == success) {
      toastr.success(message);
    } else if (style == error) {
      toastr.error(message);
    } else if (style == info) {
      toastr.info(message);
    } else if (style == warning) {
      toastr.warning(message);
    }
}