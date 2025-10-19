// ------------------ Auth JS ------------------
document.addEventListener("DOMContentLoaded", function() {

    const alertBox = document.getElementById("alertBox");
    if(!alertBox) return;

    // ------------------ Forms ------------------
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");
    const verifyForm = document.getElementById("verifyForm");
    const changeEmailForm = document.getElementById("changeEmailForm");
    const forgotEmailForm = document.getElementById("forgotEmailForm");
    const fpVerifyForm = document.getElementById("fpVerifyForm");
    const fpNewPasswordForm = document.getElementById("fpNewPasswordForm");
    const formTitle = document.getElementById("formTitle");
    const resendCode = document.getElementById("resendCode");
    const resendTimer = document.getElementById("resendTimer");
    const showChangeEmailForm = document.getElementById("showChangeEmailForm");

    // ------------------ Inputs ------------------
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm_password");
    const togglePassword = document.getElementById("togglePassword");
    const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
    const strengthBar = document.getElementById("strengthBar");
    const strengthText = document.getElementById("strengthText");
    const confirmText = document.getElementById("confirmText");

    const firstName = document.getElementById("first_name");
    const lastName = document.getElementById("last_name");
    const registerEmail = registerForm?.querySelector("input[name='email']");

    // ------------------ Helper ------------------
    function addValidation(input, msg){
        if(!input) return;
        const text = document.createElement("small");
        input.parentNode.appendChild(text);
        input.addEventListener("input", () => {
            if(input.value.trim() !== "") {
                input.style.borderColor = "green";
                text.textContent = "";
            } else {
                input.style.borderColor = "red";
                text.textContent = msg;
                text.className = "text-danger small";
            }
        });
    }

    addValidation(firstName, "First name cannot be empty");
    addValidation(lastName, "Last name cannot be empty");
    addValidation(registerEmail, "Please enter a valid email address");

    // ------------------ Password toggle ------------------
  // Reusable toggle password function
  document.querySelectorAll('.toggle-password').forEach(function(toggle) {
    toggle.addEventListener('click', function() {
      const input = this.parentElement.querySelector('input');
      const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
      input.setAttribute('type', type);

      // Toggle eye icon
      this.innerHTML = type === 'password' 
        ? '<i class="fas fa-eye"></i>' 
        : '<i class="fas fa-eye-slash"></i>';
    });
  });


    // ------------------ Password strength ------------------
    if(password && strengthBar){
        password.addEventListener("input", ()=> {
            const val = password.value;
            let strength = 0;
            if(/[a-z]/.test(val)) strength++;
            if(/[A-Z]/.test(val)) strength++;
            if(/[0-9]/.test(val)) strength++;
            if(/[^a-zA-Z0-9]/.test(val)) strength++;
            if(val.length >= 8) strength++;

            const width = (strength / 5) * 100;
            strengthBar.style.width = width + "%";
            if(strength <= 2){ 
                strengthBar.className="progress-bar bg-danger"; 
                strengthText.textContent="Weak password"; 
                password.style.borderColor="red"; 
            }
            else if(strength <= 4){ 
                strengthBar.className="progress-bar bg-warning"; 
                strengthText.textContent="Medium password"; 
                password.style.borderColor="orange"; 
            }
            else{ 
                strengthBar.className="progress-bar bg-success"; 
                strengthText.textContent="Strong password"; 
                password.style.borderColor="green"; 
            }
        });
    }

    // ------------------ Confirm password ------------------
    if(confirmPassword){
        confirmPassword.addEventListener("input", ()=> {
            if(confirmPassword.value==="") { 
                confirmPassword.style.borderColor=""; 
                confirmText.textContent=""; 
            }
            else if(confirmPassword.value === password.value){ 
                confirmPassword.style.borderColor="green"; 
                confirmText.textContent="Passwords match"; 
                confirmText.className="text-success small"; 
            }
            else{ 
                confirmPassword.style.borderColor="red"; 
                confirmText.textContent="Passwords do not match"; 
                confirmText.className="text-danger small"; 
            }
        });
    }

    // ------------------ AJAX Handler ------------------
    function ajaxSubmit(form, action, successCallback){
        form.addEventListener("submit", function(e){
            e.preventDefault();
            const fd = new FormData(form);
            fd.append("action", action);

            fetch("../handlers/auth_handler.php",{method:"POST", body:fd})
            .then(res => res.json())
            .then(r => {
                alertBox.innerHTML = r.message;
                if(r.success) successCallback(r);
                else if(typeof grecaptcha !== "undefined") grecaptcha.reset();
            })
            .catch(err=>{
                console.error(err);
                alertBox.innerHTML='<div class="alert alert-danger">Server error. Try again later.</div>';
            });
        });
    }

    // ------------------ Resend Timer ------------------
    function startResendTimer(seconds = 300) {
        if(!resendCode || !resendTimer) return;
        resendCode.style.display = "none";
        let remaining = seconds;

        const interval = setInterval(() => {
            const min = String(Math.floor(remaining/60)).padStart(2,'0');
            const sec = String(remaining%60).padStart(2,'0');
            resendTimer.textContent = `Resend code available in ${min}:${sec}`;
            remaining--;

            if(remaining < 0){
                clearInterval(interval);
                resendTimer.textContent = '';
                resendCode.style.display = "inline";
            }
        }, 1000);
    }

    // ------------------ Form Bindings ------------------
    if(registerForm){
        ajaxSubmit(registerForm, "register", ()=> {
            registerForm.style.display = "none";
            verifyForm.style.display = "block";
            formTitle.textContent = "Verify Your Email";
            startResendTimer(300);
        });
    }

    if(loginForm){
        ajaxSubmit(loginForm, "login", (r)=>{
            if(r.require_verification){
                loginForm.style.display = "none";
                verifyForm.style.display = "block";
                formTitle.textContent = "Complete your login";
                startResendTimer(300);
            } else window.location.href = "../index.php";
        });
    }

    if(verifyForm){
        ajaxSubmit(verifyForm, "verify", ()=> {
            verifyForm.style.display = "none";
            if(fpNewPasswordForm) fpNewPasswordForm.style.display = "block";
            else window.location.href="../index.php";
        });
    }

    if(fpNewPasswordForm){
        ajaxSubmit(fpNewPasswordForm, "set_new_password", ()=> {
            fpNewPasswordForm.style.display = "none";
            alertBox.innerHTML = '<div class="alert alert-success">Password updated successfully. Redirecting to login...</div>';
            setTimeout(()=> window.location.href="login.php", 2000);
        });
    }

    if(resendCode){
        resendCode.addEventListener("click", function(e){
            e.preventDefault();
            const fd = new FormData();
            fd.append("action","resend");
            fetch("../handlers/auth_handler.php",{method:"POST", body:fd})
            .then(res=>res.json())
            .then(r=>{
                alertBox.innerHTML = r.message;
                if(r.success) startResendTimer(300);
            })
            .catch(err=>{ console.error(err); alertBox.innerHTML='<div class="alert alert-danger">Server error. Try again later.</div>'; });
        });
    }

});


