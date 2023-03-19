const fname =document.getElementById('fname')
const lname =document.getElementById('lname')
const email = document.getElementById('email')
const password = document.getElementById('password')

document.getElementById('submit').addEventListener('click',event=>{
    //prevent login from form if all the input feilds are valid
    if(!email.validity.valueMissing && !password.validity.valueMissing && !fname.validity.valueMissing && !lname.validity.valueMissing){
        event.preventDefault()
        console.log("signup prevented by default form")
        //make an ajax request to php server for login
        let formData = $('#signup').serialize()
        console.log(formData)
        $.ajax({ 
            url: 'php/register.php',
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