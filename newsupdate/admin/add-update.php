<?php
include('includes/header.php');
include('includes/navbar.php');
?>
<div class="col-sm-6 col-xl-12">
    <div class="bg-secondary rounded h-100 p-4 ">
        <div class="row">
            <div class="col-sm-6 col-xl-1"></div>
            <div class="col-sm-6 col-xl-10">
                <h3 class="mt-2 mb-3">Post New Update</h3>
                <div class="alert alert-primary bg-danger text-white" id="failed-message" role="alert" style="display:none;"></div>
                <div class="alert alert-success bg-success text-white" id="success-message" role="alert" style="display:none;"></div>
                <form id="uploadForm" name="addpost" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="update-title" class="form-label">Update Titles</label>
                        <input type="text" class="form-control" name="update-title" id="update-title" placeholder="Input Update Title">
                    </div>
                    <div class="mb-3">
                        <label for="update-description" class="form-label">Description</label>
                        <textarea class="summernote" name="update_description" id="update_description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="update-description" class="form-label">Upload Image</label>
                        <div class="uploadtest">
                            <div class="upload-wrappertest">
                                <div class="input-wrapper">
                                    <div class="upload-areatest">
                                        <div class="upload-area-imgtest">
                                            <img src="img/upload.png" alt="">
                                            <p class="upload-area-texttest">Select images or <span>browse</span>.</p>
                                        </div>
                                    </div>
                                    <input type="file" class="visually-hiddentest" id="upload-inputtest" name="upload-inputtest" multiple>
                                </div>
                                <div class="upload-details">
                                    <div class="upload-info">
                                        <p>
                                            <span class="upload-info-valuetest">0</span> file(s) uploaded.
                                        </p>
                                    </div>
                                    <div class="upload-imgtest">
                                        <!-- images will be displayed here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-check form-switch pb-3">
                        <input type="hidden" name="notify" id="notify" value="1">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Notify everyone</label>
                    </div>

                    <button name="submit" type="submit" class="btn btn-primary">Post</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
include('includes/footer.php');
?>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: 'Create your content here.',
            tabsize: 5,
            height: 270,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
        onPaste: function(e) {
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/plain');
            e.preventDefault();
            document.execCommand('insertText', false, bufferText);
        }
    }
        });

        let allFiles = [];

        $(".upload-areatest").click(function() {
            $('#upload-inputtest').trigger('click');
        });

        $('#upload-inputtest').change(function(event) {
            if (event.target.files) {
                let newFiles = Array.from(event.target.files);
                allFiles = allFiles.concat(newFiles);
                displayFiles(allFiles);
                event.target.value = '';
            }
        });

        function displayFiles(files) {
            $('.upload-imgtest').html('');
            files.forEach(file => {
                let reader = new FileReader();
                reader.onload = function(event) {
                    let html = `
                    <div class="uploaded-imgtest" style="margin-bottom: 10px;">
                        <img src="${event.target.result}" alt="Uploaded Image">
                        <button type="button" class="remove-btntest">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                    $(".upload-imgtest").append(html);
                };
                reader.readAsDataURL(file);
            });

            $('.upload-info-valuetest').text(files.length);
        }

        $(document).on('click', '.remove-btntest', function(event) {
            let imgDiv = $(event.target).closest('.uploaded-imgtest');
            let imgIndex = imgDiv.index();
            allFiles.splice(imgIndex, 1);
            displayFiles(allFiles);
        });

        $('#uploadForm').submit(function(event) {
            event.preventDefault();

            let formData = new FormData(this);
            allFiles.forEach(file => {
                formData.append('upload-inputtest[]', file);
            });
            $.ajax({
                url: 'functions/functions.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    try {
                        response = JSON.parse(response);
                        if (response.response) {
                            const successMessageDiv = document.getElementById('success-message');
                            successMessageDiv.style.display = 'block';
                            successMessageDiv.textContent = response.message;
                            document.getElementById('uploadForm').reset();
                            $('#update_description').summernote('reset');
                            $('.upload-imgtest').empty();
                            allFiles = [];
                            setTimeout(function() {
                                successMessageDiv.style.display = 'none';
                            }, 3000);
                        } else {
                            const failedMessageDiv = document.getElementById('failed-message');
                            failedMessageDiv.style.display = 'block';
                            failedMessageDiv.textContent = response.message;
                            document.getElementById('uploadForm').reset();
                            $('#update_description').summernote('reset');
                            $('.upload-imgtest').empty();
                            setTimeout(function() {
                                failedMessageDiv.style.display = 'none';
                            }, 3000);
                        }
                    } catch (error) {
                        console.error("Error parsing JSON response:", error);
                        const failedMessageDiv = document.getElementById('failed-message');
                        failedMessageDiv.style.display = 'block';
                        failedMessageDiv.textContent = "An error occurred while processing your request.";
                        setTimeout(function() {
                            failedMessageDiv.style.display = 'none';
                        }, 3000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request error:", error);
                    const failedMessageDiv = document.getElementById('failed-message');
                    failedMessageDiv.style.display = 'block';
                    failedMessageDiv.textContent = "An error occurred while processing your request.";
                    setTimeout(function() {
                        failedMessageDiv.style.display = 'none';
                    }, 3000);
                }
            });
        });

        document.getElementById('flexSwitchCheckChecked').addEventListener('change', function() {
            document.getElementById('notify').value = this.checked ? '1' : '0';
        });
    });
</script>
</body>
</html>
