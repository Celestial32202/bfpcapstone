<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<section id="contact" class="contact pt-5">
    <div class="container pt-5">
        <div class="section-header">
            <h2>Fire Incident Report Form</h2>
            <p>Please enter the fire incident area and your contact details; we will request a video call upon receiving
                your message for a faster response.</p>
        </div>
    </div>
    <div class="container">
        <div class="row gy-5 gx-lg-5">
            <div class="col-lg-12 mb-5 d-flex justify-content-center align-items-center d-none" id="vid-stream">
                <div class="info text-center">
                    <div class="info">
                        <div class="info-spinner" id="info-spinner" style="display:none;">
                            <div class="spinner"></div>
                            <h4 class="mt-4 ">Please wait a few moments while we review your report.
                                You may receive a video call request from us shortly.</h4>
                        </div>
                        <div class="load-spinner" id="vid-spinner" style="display:none;">
                            <div class="spinner"></div>
                            <h4 class="mt-4 ">Video Stream Loading...</h4>
                        </div>
                        <div class="message-done" id="message-done" style="display:none;">
                            <div class="spinner"></div>
                            <h4 class="mt-4">Process done leave the rest to us.
                                <br>You will receive a link for the tracking of rescuers from us shortly.
                            </h4>
                        </div>
                        <div class="message-declined" id="message-declined" style="display:none;">
                            <h4 class="mt-4">Your report has been declined.
                                <br>Based on our review the report submitted is lacking.
                            </h4>
                        </div>
                        <div class="message-reconnecting" id="message-reconnecting" style="display:none;">
                            <div class="spinner"></div>
                            <h4 class="mt-4">Admin call is reconnecting please wait...
                            </h4>
                        </div>
                        <div class="user-reconnecting" id="user-reconnecting" style="display:none;">
                            <div class="spinner"></div>
                            <h4 class="mt-4">Reconnecting in admin call in <span id="countdown">3</span>...</h4>
                        </div>
                        <div id="videoContainer" style="display: none;">
                            <h4 class="">Your Video Stream</h4>
                            <video id="userVideo" autoplay playsinline></video>
                            <button class="vid-button mt-2 end-vid-button" onclick="endCall()">End Call</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container d-flex justify-content-center align-items-center ">
                <div class="col-lg-6 col-md-8 col-sm-10" id="report-form">
                    <form action="" method="post" role="form" class="php-email-form" id="incidentForm">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Your Name"
                                    required>
                            </div>
                            <div class="col-md-6 form-group mt-3 mt-md-0">
                                <input type="text" class="form-control" name="contact_number" id="contact-number"
                                    placeholder="Your Contact Number" required>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <input type="text" class="form-control" name="location" id="location"
                                placeholder="Specific Location" required>
                        </div>
                        <div class="form-group mt-3">
                            <textarea class="form-control" name="message" id="message" placeholder="Additional Info"
                                required></textarea>
                        </div>

                        <div class="text-center"><button type="submit" name="submit_form">Send Message</button></div>
                    </form>
                </div><!-- End  Form -->
            </div>
        </div>
        <div class="modal fade" id="redirectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">
                    <div class="modal-body">
                        <div id="callRequestModal">
                            <h4>Admin is requesting a call. Accept?</h4>
                            <button class="vid-button mt-2 accept-vid-button" onclick="acceptCall()">Accept</button>
                            <button class="vid-button mt-2 decline-vid-button" onclick="">Decline</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://www.gstatic.com/firebasejs/6.4.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.4.2/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.4.2/firebase-auth.js"></script>

<script src="assets/js/global.js"></script>
<script src="assets/js/config.js"></script>
<script src="assets/js/user-request.js"> </script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>