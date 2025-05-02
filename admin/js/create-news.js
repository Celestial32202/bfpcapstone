var success = "success",
error = "error",
warning = "warning",
hide = "hide",
show = "show",
info = "info";

function limitInput(element, maxLength) {
    if (element.value.length >= maxLength) {
        element.value = element.value.slice(0, maxLength);
    }
}

function restrictInput(event) {
    const key = event.key;
    const target = event.target;

    if (!/^\d$/.test(key) ||
        (target.value.length === 0 && key !== '0') ||
        (target.value.length === 1 && key !== '9')) {
        event.preventDefault();
    }
}
function checkFormValidity() {
    const form = document.getElementById("submit_news_form");
    const submitBtn = document.getElementById("btn-submit-news");
    // const positionSelect = document.getElementById("position");
    // const branchSelect = document.getElementById("branch");
    const requiredInputs = form.querySelectorAll("input[required]");

    let allInputsFilled = Array.from(requiredInputs).every(input => input.value.trim());

    if (allInputsFilled) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }

    // if (positionSelect.value === "Fire Officer" || positionSelect.value === "Fire Officer Supervisor") {
    //     submitBtn.disabled = !(allInputsFilled && branchSelect.value !== "branch-none");
    // } else {
    //     submitBtn.disabled = !allInputsFilled;
    // }
}

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOMContentLoaded");
    initFirebase();
    const form = document.getElementById("submit_news_form");

    form.querySelectorAll("input[required]").forEach(input => {
        input.addEventListener("input", checkFormValidity);
        input.addEventListener("change", checkFormValidity);
    });

    $('#btn-submit-news').click(function() {
        var newsImage = $('#news-image')[0].files[0];
        let subject = document.getElementById("news-subject").value;
        let otherDetails = document.getElementById("news-other-details").value;
        let description = document.getElementById("news-description").value;
        var news = "News";
        var imageUploadURL = "";
        var s = "/";
        var storageReference = firebase.storage().ref();
        showModal('#modal-loading', show);
        if (newsImage == undefined) {
            toast("Please upload image attachment!", warning);
            hideProgressModal();
            return;
        }
        toast("Uploading " + news + " attachment file...", info);
        let uploadingImage = storageReference.child("IMAGES/" + newsImage.name).put(newsImage);
        uploadingImage.on('state_changed', function(data) {
            toast(news + " Image uploading in progress...", warning);
        }, function(err) {
            toast(err, error);
            hideProgressModal();
            return;
        }, function() {
            uploadingImage.snapshot.ref.getDownloadURL().then(function(imgURL) {
                imageUploadURL = imgURL;
                toast(news + " image successfully uploaded!", success);
                hideProgressModal();
                // localStorage.setItem("news_image_url", imageUploadURL);
                console.log("Success imageUploadURL ->", imageUploadURL);

                let formData = new FormData();
                formData.append("news_image_url", imageUploadURL);
                formData.append("news_subject", subject);
                formData.append("news_other_details", otherDetails);
                formData.append("news_description", description);

                console.log("incident report formData ->", formData);

                fetch("news/create-news.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text()) // ✅ Read as text first
                .then(text => { 
                    console.log("✅ Image News Submitted!");
                    setImage('#news-display-image', "https://rb.gy/ahvfma");
                    emptyInputText(['#news-subject', '#news-other-details', '#news-description', '#news-image']);
                    // try {
                    //     let data = JSON.parse(text);
                    //     if (data.success) {
                    //         console.log("✅ Image News Submitted!");
                    //         // localStorage.setItem("resident_submitted_incident_id", data.incident_id);
                    //         // window.open('../testing/video-stream/user-images/regular_user.html');
                    //     } else {
                    //         console.log("❌ Error:", data.error);
                    //         alert("❌ Error Submitting News: " + data.error);
                    //     }
                    // } catch (error) {
                    //     console.error("❌ JSON Parse Error:", error, "Response:", text);
                    //     alert("❌ Server returned an invalid response.");
                    // }
                })
                .catch(error => console.error("❌ Fetch Error:", error));



            });
        });
    });
});

function displayImage(elem, id) {
    var image = document.getElementById(id);
    image.src = elem.value;        
}

function showModal(id, value) {
    $(id).modal(value);
}

function hideProgressModal() {
    setTimeout(function() {
        showModal('#modal-loading', hide);
    }, 690);
}

function setImage(i, v) {
    $(i).attr("src", v);
}

function emptyInputText(v) {
    for (i=0; i<v.length; i++) {
        $(v[i]).val("");
    }
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

function initFirebase() {
    const firebaseConfig = {
        apiKey: "AIzaSyDjWIssMvyA02E0e2df6JsTdDG6VDEvrNk",
        authDomain: "auscii.firebaseapp.com",
        databaseURL: "https://auscii-default-rtdb.firebaseio.com",
        projectId: "auscii",
        storageBucket: "auscii.appspot.com",
        messagingSenderId: "63244062075",
        appId: "1:63244062075:web:4d8704eef05c5986a941de"
    };
    firebase.initializeApp(firebaseConfig);
}