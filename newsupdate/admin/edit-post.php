<?php
include('includes/header.php');
include('includes/navbar.php');

$postid = intval($_GET['id']);
$display = get_update_edit($postid);
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
                    <?php
                    if ($display) {
                    ?>
                        <div class="mb-3">
                            <label for="update-title" class="form-label">Update Titles</label>
                            <input type="text" class="form-control" name="update-title" id="update-title" value="<?php echo htmlspecialchars($display['update_title']); ?>" placeholder="Input Update Title">
                        </div>
                        <div class="mb-3">
                            <label for="update-description" class="form-label">Description</label>
                            <textarea class="summernote" name="update_description" id="update_description" required><?php echo $display['update_desc']; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="update-description" class="form-label">Upload Image</label>
                            <div class="upload-edit">
                                <div class="upload-wrapper-edit">
                                    <div class="upload-details-edit">
                                        <div class="upload-info">
                                            <p>
                                                <span class="upload-info-value-editt">
                                                    <?php
                                                    $images = json_decode($display['update_img'], true);
                                                    echo !empty($images) ? count($images) : 0;
                                                    ?>
                                                </span> image(s) uploaded.
                                            </p>
                                        </div>
                                        <div class="upload-img-edit">
                                            <?php
                                            if (!empty($images)) {
                                                foreach ($images as $image) {
                                            ?>
                                                    <div class="uploaded-img-edit" style="margin-bottom: 10px;">
                                                        <img src="update-img/<?php echo $image; ?>" alt="Uploaded Image">
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
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
            let id = <?php echo $postid; ?>;
            formData.append('id', id);
            $.ajax({
                url: 'functions/edit-functions.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    try {
                        response = JSON.parse(response);
                        if (response.response) {
                            window.location.href = 'manage-update.php';
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

    });
</script>
</body>

</html>