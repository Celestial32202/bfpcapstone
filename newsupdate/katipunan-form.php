<?php 
$currentPage = 'katipunan-form';
include ('includes/header.php'); 
include ('includes/navbar.php'); 

require_once ('config.php');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['SESSION_EMAIL'])) {
    $user_email = $_SESSOIN['SESSION_EMAIL'];
    $stmt = $conn->prepare("SELECT survey_confirm FROM users_creds WHERE user_email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $stmt->bind_result($survey_confirm);
    $stmt->fetch();
    $stmt->close();
    unset($user_email);
    if ($survey_confirm == 'survey valid' || !empty($survey_confirm)) {
        $_SESSION['errors'] = "<div class='alert alert-info'>You have submitted the form already.</div>";
        header('Location: index.php'); 
        exit();
    }
}else{
    header('Location: forms/loginform.php');
    exit();
}

if (isset($_POST['submit_form'])) {
    $user_email= 'celestial32202@gmail.com';
    $address = sanitize_input($_POST['address']);
    $civil_status = sanitize_input($_POST['civil_status']);
    $age_group = sanitize_input($_POST['age_group']);
    $youth_class = sanitize_input($_POST['youth_class']);
    $youth_class_needs = sanitize_input($_POST['youth_class_needs']);
    $work_status = sanitize_input($_POST['work_status']);
    $educ_background = sanitize_input($_POST['educ_background']);
    $skvoter = sanitize_input($_POST['skvoter']);
    $voted = sanitize_input($_POST['voted']);
    $survey_confirm = 'survey valid';
    if (empty($address) || empty($civil_status) || empty($age_group) || empty($youth_class) || empty($youth_class_needs) || empty($work_status) || empty($educ_background) || empty($skvoter) || empty($voted)) {
        $_SESSION['errors'] = "<div class='alert alert-danger'>All fields are required.</div>";
        header('Location: katipunan-form.php');
        exit();
    }
    $stmt = $conn->prepare("INSERT INTO survey_information (user_email, full_address, civil_status, age_group, youth_class, youth_class_needs, work_status, educ_background, sk_voter, voted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $user_email, $address, $civil_status, $age_group, $youth_class, $youth_class_needs, $work_status, $educ_background, $skvoter, $voted);
    if ($stmt->execute()) {
        $user_stmt = $conn->prepare("INSERT INTO users_creds (survey_confirm) VALUE (?)");
        $user_stmt->bind_param("s", $survey_confirm);
        if ($stmt->execute()) {
            $_SESSION['info'] = "<div class='alert alert-info'>Survey Form applied!</div>";
            $stmt->close();
            $conn->close(); 
            header('Location: index.php');
            exit();
        }else{
            $_SESSION['info'] = "<div class='alert alert-danger'>something went wrong</div>"; // Get error message from database connection
            $stmt->close(); 
            $conn->close(); 
            header('Location: katipunan-form.php');
            exit();
        }
        
    } else {
        $_SESSION['info'] = "<div class='alert alert-danger'>". mysqli_error($conn) ."</div>"; // Get error message from database connection
        $stmt->close(); 
        $conn->close(); 
        header('Location: katipunan-form.php');
        exit();
    }
      
}
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
?>
<section class="information-form">
    <div class="form-container">
      <header>Batang Magtanggol Youth Organization - Survey Questionnaire </header>
      <div id="notification" style="padding-top: 10px;">
      <?php 
            if(isset($_SESSION['errors'])){
                echo $_SESSION['errors'];
                unset($_SESSION['errors']);
            }
            if(isset($_SESSION['info'])) {
                echo $_SESSION['info'];
                unset($_SESSION['info']);
            }
        ?>
      </div>
      <form action="" class="form" method="post">
        <div class="form-columns">
            <div class="form-column">
                <div class="survey-input-box">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" placeholder="Enter Address" required />
                </div>
                <div class="survey-input-box">
                    <label for="civil-status" class="form-label">Civil Status</label>
                    <select class="form-select mb-3" aria-label="Default select example" id="civil-status" name="civil_status">
                        <option value="" selected>Select Option</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Separated">Separated</option>
                        <option value="Annulled">Annulled</option>
                        <option value="Unknown">Unknown</option>
                        <option value="Live-in">Live-in</option>
                    </select>
                </div>
                <div class="survey-input-box">
                    <label for="age-group" class="form-label">Youth Age Group:</label>
                    <select class="form-select mb-3" aria-label="Default select example" id="age-group" name="age_group">
                        <option value="" selected>Select Option</option>
                        <option value="Child Youth (15-17 yrs old)">Child Youth (15-17 yrs old)</option>
                        <option value="Core Youth (18-24 yrs old)">Core Youth (18-24 yrs old)</option>
                        <option value="Young Adult (24-30 yrs old)">Young Adult (24-30 yrs old)</option>
                    </select>
                </div>
                <div class="survey-input-box">
                    <label for="youth-class" class="form-label">Youth Classification:</label>
                    <select class="form-select mb-3" aria-label="Default select example" id="youth-class" name="youth_class">
                        <option value="" selected>Select Option</option>
                        <option value="In school youth">In school youth</option>
                        <option value="Out of school youth">Out of school youth</option>
                        <option value="Working youth">Working youth</option>
                        <option value="N/A">N/A</option>
                    </select>
                </div>
                <div class="survey-input-box">
                    <label for="youth-class-needs" class="form-label">Youth Classification: Youth with Specific Needs:</label>
                    <select class="form-select mb-3" aria-label="Default select example" id="youth-class-needs" name="youth_class_needs">
                        <option value="" selected>Select Option</option>
                        <option value="Person with disabilities">Person with disabilities</option>
                        <option value="Children in conflict with law">Children in conflict with law</option>
                        <option value="Indigenous people">Indigenous people</option>
                        <option value="N/A">N/A</option>
                    </select>
                </div>
            </div>
            <div class="form-column">
                <div class="survey-input-box">
                    <label for="work-status" class="form-label">Work Status:</label>
                    <select class="form-select mb-3" aria-label="Default select example" id="work-status" name="work_status">
                        <option value="" selected>Select Option</option>
                        <option value="Employed">Employed</option>
                        <option value="Unemployed">Unemployed</option>
                        <option value="Self-Employed">Self-Employed</option>
                        <option value="Currently looking for a job">Currently looking for a job</option>
                        <option value="Not interested in looking for a job">Not interested in looking for a job</option>
                    </select>
                </div>
                <div class="survey-input-box">
                    <label for="educ-background" class="form-label">Educational Background:</label>
                    <select class="form-select mb-3" aria-label="Default select example" id="educ-background" name="educ_background">
                        <option value="" selected>Select Option</option>
                        <option value="Elementary Level">Elementary Level</option>
                        <option value="Elementary Graduate">Elementary Graduate</option>
                        <option value="High School Level">High School Level</option>
                        <option value="High School Graduate">High School Graduate</option>
                        <option value="Senior High Level">Senior High Level</option>
                        <option value="Senior High Graduate">Senior High Graduate</option>
                        <option value="Vocational Graduate">Vocational Graduate</option>
                        <option value="College Level">College Level</option>
                        <option value="College Graduate">College Graduate</option>
                        <option value="Master Level">Master Level</option>
                        <option value="Master Graduate">Master Graduate</option>
                        <option value="Doctorate Level">Doctorate Level</option>
                        <option value="Doctorate Graduate">Doctorate Graduate</option>
                    </select>
                </div>
                <div class="gender-box">
                    <h3>Registered SK Voter?</h3>
                    <div class="gender-option">
                        <div class="gender">
                            <input type="radio" id="check-yes-voter" value="Yes-voter" name="skvoter" checked />
                            <label for="check-yes-voter">Yes</label>
                        </div>
                        <div class="gender">
                            <input type="radio" id="check-no-voter" value="No-voter" name="skvoter" />
                            <label for="check-no-voter">No</label>
                        </div>
                    </div>
                </div>
                <div class="gender-box">
                    <h3>Voted Last Election?</h3>
                    <div class="gender-option">
                        <div class="gender">
                            <input type="radio" id="check-yes-voted" value="Yes-voted" name="voted" checked />
                            <label for="check-yes-voted">Yes</label>
                        </div>
                        <div class="gender">
                            <input type="radio" id="check-no-voted" value="No-voted" name="voted" />
                            <label for="check-no-voted">No</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button name="submit_form" class="btn" type="submit">Submit</button>
    </form>
    </div>
  </section>
<?php include ('includes/footer.php'); ?>
<script>
    const scheduleDateInput = document.getElementById("schedule-date");
    const currentDate = new Date();
    currentDate.setUTCHours(0, 0, 0, 0); 
    const estOffset = 5 * 60 * 60 * 1000;
    const nextDay = new Date(currentDate.getTime() + estOffset);
    nextDay.setDate(nextDay.getDate() + 1);
    scheduleDateInput.min = nextDay.toISOString().split('T')[0];
    const dateInput = document.getElementById("schedule-date");
    const timeButtons = document.querySelectorAll(".time-button");

    document.querySelector("form").addEventListener("submit", function(event) {
    const selectedTime = document.querySelector(".time-button.selected");

    if (!selectedTime) {
        event.preventDefault();
        alert("Please select a time slot before submitting.");
    }
    });
      
    dateInput.addEventListener("change", () => {
          if (dateInput.value) {
              timeButtons.forEach(button => {
                  button.removeAttribute("disabled");
              });
          } else {
              timeButtons.forEach(button => {
                  button.setAttribute("disabled", "true");
              });
          }
      });
      
      timeButtons.forEach(button => {
        button.addEventListener('click', () => {
          timeButtons.forEach(btn => btn.classList.remove('selected'));
          button.classList.add('selected');
          const selectedTime = button.getAttribute("data-time");
          document.getElementById("selected_time").value = selectedTime;
          
        });
      });
      const birthdateInput = document.getElementById("birthdate");
      
      const today = new Date();
      const year = today.getFullYear();
      const month = String(today.getMonth() + 1).padStart(2, "0"); 
      const day = String(today.getDate()).padStart(2, "0");
      const formattedDate = `${year}-${month}-${day}`;
      birthdateInput.max = formattedDate;

      document.getElementById("schedule-date").addEventListener("change", function() {
          var selectedDate = this.value;
          document.getElementById("hidden-schedule-date").value = selectedDate;
      });

      document.querySelectorAll(".time-button").forEach(function(button) {
          button.addEventListener("click", function() {
              var selectedTime = this.textContent;
              document.getElementById("hidden-schedule-time").value = selectedTime;
          });
      });
      document.addEventListener("DOMContentLoaded", function() {
            var messageElement = document.getElementById('notification');
            setTimeout(function() {
                messageElement.style.display = 'none';
            }, 10000);
        });
      </script>
</body>
</html>