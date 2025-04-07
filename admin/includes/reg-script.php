<!-- <script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("register_form");
    const submitBtn = document.getElementById("register_btn");
    const passwordField = document.getElementById("password");
    const generateBtn = document.querySelector(".btn-primary.generate"); // Select only the Generate button
    const inputs = form.querySelectorAll("input[required], select[required]");

    function checkFormValidity() {
        let allFilled = true;

        inputs.forEach(input => {
            if (input.type === "select-one") {
                if (input.value === "position-none" || input.value.trim() === "") {
                    allFilled = false;
                }
            } else {
                if (input.value.trim() === "") {
                    allFilled = false;
                }
            }
        });

        submitBtn.disabled = !allFilled;
    }
    // Event listeners for real-time validation
    inputs.forEach(input => {
        input.addEventListener("input", checkFormValidity);
        input.addEventListener("change", checkFormValidity);
    });

    // Run initial validation
    checkFormValidity();

    // Generate Password Function
    function generatePassword() {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+";
        let password = "";
        for (let i = 0; i < 20; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        passwordField.value = password;
        passwordField.removeAttribute("disabled");

        // 🔥 Trigger validation after setting the password
        checkFormValidity();
    }

    // Attach the password generation function to the Generate button
    if (generateBtn) {
        generateBtn.addEventListener("click", generatePassword);
    }
});
</script> -->