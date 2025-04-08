<script>
        $(document).ready(function (c) {
            $('.alert-close').on('click', function (c) {
                $('.main-mockup').fadeOut('slow', function (c) {
                    $('.main-mockup').remove();
                    window.location.href = "../index.php";
                });
            });
        });
        const passwordInput = document.getElementById("password");
        const passwordInput_2nd = document.getElementById("confirm-password");
        const requirementList = document.querySelectorAll(".requirement-list li");
        const eyeIcon = document.querySelector('i');
        const hidden_Strngth_List = document.getElementById('pass_strngth_list');
        const reg_btn = document.querySelector('.btn');
        const not_match = document.querySelector(".not_match");
        const match_or_not = document.querySelector(".match_or_not");
        const form = document.getElementById('register_form');
        const termsCheckbox = document.getElementById('terms');
        const submitButton = document.querySelector('.btn');
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
        const updateIndicators = (requirements) => {
            requirements.forEach((requirement, index) => {
                const requirementItem = requirementList[index];
                if (requirement) {
                    requirementItem.classList.add("valid");
                    requirementItem.firstElementChild.className = "fa-solid fa-check";
                } else {
                    requirementItem.classList.remove("valid");
                    requirementItem.firstElementChild.className = "fa-solid fa-circle";
                }
            });
        };
            passwordInput.addEventListener("input", () => {
            const password = passwordInput.value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", window.location.href, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const requirements = response.requirements;
                    updateIndicators(requirements);

                    const allRequirementsMet = Object.values(requirements).every((requirement) => requirement);
                    if (allRequirementsMet) {
                        passwordInput_2nd.removeAttribute("disabled");
                    }
                    else{
                        passwordInput_2nd.setAttribute("disabled","disabled");
                    }
                    }
                };
                xhr.send("password=" + encodeURIComponent(password));
            });
            passwordInput_2nd.addEventListener("input", () => {
                if(passwordInput_2nd.value.trim() === ''){
                    match_or_not.innerText = "Confirm your password";
                    match_or_not.style.color = "#a6a6a6";
                    not_match.style.display = "block";
                    not_match.style.color = "#a6a6a6";
                }
                else if(passwordInput.value !== passwordInput_2nd.value){
                    match_or_not.innerText = "Password didn't matched";
                    not_match.style.display = "block";
                    not_match.style.color = "#D93025";
                    match_or_not.style.color = "#D93025";
                    reg_btn.setAttribute("disabled","disabled");
                }
                else if(passwordInput.value === passwordInput_2nd.value){
                    match_or_not.innerText = "Password matched";
                    not_match.style.display = "none";
                    match_or_not.style.color = "#4070F4";

                    reg_btn.removeAttribute("disabled");
                }
            });
            termsCheckbox.addEventListener('change', function () {
                submitButton.disabled = !this.checked;
            });
            form.addEventListener('submit', function (e) {
                if (!termsCheckbox.checked) {
                    e.preventDefault();
                    alert('You must accept the terms and conditions to register.');
                }
            });
            passwordInput.addEventListener('focus',showHiddenClass);
            passwordInput_2nd.addEventListener('focus',showHiddenClass_2);
            function showHiddenClass() {
                const hidden_Strngth_List = document.getElementById('pass_strngth_list');
                eyeIcon.classList.remove('hidden');
                hidden_Strngth_List.classList.remove('hidden');
                if(passwordInput_2nd.value.trim() !== ''){
                    passwordInput_2nd.value= '';
                not_match.style.display = "none";
                match_or_not.style.display = "none";
                match_or_not.innerText = "Confirm your password";
                match_or_not.style.color = "#a6a6a6";
                not_match.style.color = "#a6a6a6";
                reg_btn.setAttribute("disabled","disabled");
                }
                else if( passwordInput_2nd.value.trim() === ''){
                    not_match.style.display = "none";
                match_or_not.style.display = "none";
                }
            }
            function showHiddenClass_2() {
                hidden_Strngth_List.classList.add('hidden');
                if(passwordInput_2nd.value.trim() === ''){
                not_match.style.display = "block";
                match_or_not.style.display = "block";
                }
            }     
        eyeIcon.addEventListener("click", () => {
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        passwordInput_2nd.type = passwordInput_2nd.type === "password" ? "text" : "password";
        eyeIcon.className = `fa-solid fa-eye${passwordInput.type === "password" ? "" : "-slash"}`;
        });
        
        document.addEventListener('click', function(event) {
            const click_Target = event.target;           
            if (click_Target !== passwordInput && 
            click_Target !== eyeIcon && 
            !hidden_Strngth_List.contains(click_Target) ) {
                
                if (passwordInput.value.trim() === '') {
                    eyeIcon.classList.add('hidden');
                    hidden_Strngth_List.classList.add('hidden');
                }  
                
            }
        });
    </script>