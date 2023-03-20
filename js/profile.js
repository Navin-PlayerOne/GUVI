// Get the form and its inputs
const form = document.querySelector('form');
const inputs = form.querySelectorAll('input');

// Get the buttons
const editBtn = document.getElementById('editBtn');
const saveBtn = document.getElementById('saveBtn');
const cancelBtn = document.getElementById('cancelBtn');

// Function to enable input fields
function enableInputs() {
  inputs.forEach((input) => {
    input.removeAttribute('disabled');
  });
}

// Function to disable input fields
function disableInputs() {
  inputs.forEach((input) => {
    input.setAttribute('disabled', true);
  });
}

// Add event listener to the Edit button
editBtn.addEventListener('click', () => {
  enableInputs();
  editBtn.classList.add('d-none');
  saveBtn.classList.remove('d-none');
  cancelBtn.classList.remove('d-none');
});

// Add event listener to the Save button
saveBtn.addEventListener('click', () => {
  $.ajax({
    url: 'php/updateprofile.php',
    method: 'POST',
    data: {
      'token' : localStorage.getItem('tokenId'),
      'fname' : fnameButton.value,
      'lname' : lnameButton.value,
      'email' : email.value,
      'phone' : phone.value
    },
    success: function(response) {
      let res = JSON.parse(response)
      if(res.status){
        window.location.href = 'login.html'
      }
      else{
        //if success update value
        fnameButton.value = res['firstname']
        lnameButton.value = res['lastname']
        email.value = res['email']
        phone.value = res['phone']

        loadingIcon.style.display = 'none';
        document.body.style.display = 'block';
      }
    },  
    error: function(xhr, status, error) {
      // Handle errors
      console.log(error);
    }
});
  disableInputs();
  editBtn.classList.remove('d-none');
  saveBtn.classList.add('d-none');
  cancelBtn.classList.add('d-none');
  // Send the form data to the server using AJAX
  // ...
});

// Add event listener to the Cancel button
cancelBtn.addEventListener('click', () => {
  disableInputs();
  editBtn.classList.remove('d-none');
  saveBtn.classList.add('d-none');
  cancelBtn.classList.add('d-none');
});

const fnameButton = document.getElementById('firstName')
const lnameButton = document.getElementById('lastName')
const email = document.getElementById('email')
const phone = document.getElementById('phone')


//when the page is loaded check for the token in local storage
let token = localStorage.getItem('tokenId')
if(token!=undefined && token!=null && token!==""){
    
    const loadingIcon = document.getElementById('loading');
    document.body.style.display = 'none';
    loadingIcon.style.display = 'block';
    
    $.ajax({
        url: 'php/profile.php',
        method: 'POST',
        data: {token},
        success: function(response) {
          let res = JSON.parse(response)
          if(res.status){
            window.location.href = 'login.html'
          }
          else{
            fnameButton.value = res['firstname']
            lnameButton.value = res['lastname']
            email.value = res['email']
            phone.value = res['phone']

            loadingIcon.style.display = 'none';
            document.body.style.display = 'block';
          }
        },  
        error: function(xhr, status, error) {
          // Handle errors
          console.log(error);
        }
    });
}
else{
    window.location.href = 'login.html'
}