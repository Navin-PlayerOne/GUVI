const email = document.getElementById('email')
const password = document.getElementById('password')

document.getElementById('submit').addEventListener('click',event=>{
    //prevent login from form if all the input feilds are valid
    if(!email.validity.valueMissing && !password.validity.valueMissing){
        event.preventDefault()
        console.log("login prevented by form")
        //make an ajax request to php server for login
        let formData = $('#login').serialize()
        console.log(formData)
        $.ajax({ 
            url: 'php/login.php',
            method: 'POST',
            data: formData,
            success: function(response) {
              // Handle the server response
              console.log(response);
            },
            error: function(xhr, status, error) {
              // Handle errors
              console.log(error);
            }
          });
    }
})