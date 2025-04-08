document.addEventListener('DOMContentLoaded', () => {
    const   forms = document.querySelector(".forms"),
            pwShowHide = document.querySelectorAll(".eye-icon"),
            links = document.querySelectorAll(".link"),
            form = document.getElementById('register_form');

    pwShowHide.forEach(eyeIcon => {
        eyeIcon.addEventListener("click", () => {
            let pwFields = eyeIcon.parentElement.parentElement.querySelectorAll(".password");
        
        pwFields.forEach(password => {
            if(password.type === "password"){
                password.type = "text";
                eyeIcon.classList.replace("bx-hide", "bx-show");
                return;
            }
            password.type = "password";
            eyeIcon.classList.replace("bx-show", "bx-hide");
        })
        
    })
    
})
const birthdateInput = document.getElementById("birthdate");
const today = new Date();
const year = today.getFullYear();
const month = String(today.getMonth() + 1).padStart(2, "0"); 
const day = String(today.getDate()).padStart(2, "0");
const formattedDate = `${year}-${month}-${day}`;
birthdateInput.max = formattedDate;
// const passwordInput = document.getElementById('password');
//         const dialogBox = document.getElementById('dialog-box');
//         const overlay = document.getElementById('overlay');
//         const closeDialog = document.getElementById('close-dialog');

//         passwordInput.addEventListener('click', (event) => {
//             const rect = event.target.getBoundingClientRect();
//             const dialogBoxWidth = dialogBox.offsetWidth;
//             const dialogBoxHeight = dialogBox.offsetHeight;

//             dialogBox.style.top = `${rect.top + window.scrollY}px`;
//             dialogBox.style.left = `${rect.right + window.scrollX}px`;
//             dialogBox.style.display = 'block';
//             overlay.style.display = 'block';
//         });

//         closeDialog.addEventListener('click', () => {
//             dialogBox.style.display = 'none';
//             overlay.style.display = 'none';
//         });

//         overlay.addEventListener('click', () => {
//             dialogBox.style.display = 'none';
//             overlay.style.display = 'none';
//         });
links.forEach(link => {
    link.addEventListener("click", e => {
       e.preventDefault(); //preventing form submit
       forms.classList.toggle("show-signup");
    })
})
const passwordInput = document.getElementById('password');
const guidanceText = document.getElementById('password-guidance');

passwordInput.addEventListener('focus', () => {
  guidanceText.style.display = 'block';
});

passwordInput.addEventListener('blur', () => {
  guidanceText.style.display = 'none';
});


});