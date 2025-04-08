<?php 
$currentPage = 'contact-page';
include ('includes/header.php'); 
include ('includes/navbar.php'); 
?>
<div class="container-fluid page-header  p-0" style="background-color: #fff4dc;">
        <div class="container-fluid page-header-inner py-5">
            <div class="container align-items-left">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-start text-uppercase">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Contact</li>
                    </ol>
                </nav>
                <h1 class="display-3 font-weight-bold text-white mb-3 animated slideInDown">Contact Us</h1> 
            </div>
        </div>
</div>
<div class="container-fluid py-5">
        <div class="container py-5">
            <div class="mx-auto text-center  fadeIn"  style="max-width: 500px;">
                <div class="btn btn-sm border rounded-pill px-3 mb-3" style="color: #261ff3">Contact Us</div>
                <h1 class="mb-4">If You Have Any Query, Please Contact Us</h1>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <p class="text-center mb-4">We'd love to hear from you! Whether you have a question, feedback, or just want to say hello, please fill out the form below and we'll get back to you as soon as possible.</p>
                    <div>
                        <div id="responseMessage">
                            
                        </div>
                        <!-- <div class='alert alert-danger'>reCAPTCHA verification failed. Please try again.</div> -->
                        <form id="contact_form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Your Name">
                                        <label for="name">Your Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Your Email">
                                        <label for="email">Your Email</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="subject" name="subject"placeholder="Subject">
                                        <label for="subject">Subject</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a message here" id="message" name="message" style="height: 150px"></textarea>
                                        <label for="message">Message</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
include ('includes/footer.php');
?>
<script>
document.getElementById('contact_form').addEventListener('submit', function(event) {
    event.preventDefault(); //

    let formData = new FormData(this);

    fetch('functions/contact-form-function.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        let responseMessage = document.getElementById('responseMessage');
        if (data.success) {
            responseMessage.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
            console.log(data.message);
        } else {
            responseMessage.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
            console.log(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
</script>
</body>
</html>