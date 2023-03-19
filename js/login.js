const email = document.getElementById('email')
const password = document.getElementById('password')
const message = document.getElementById('lmsg')

document.getElementById('submit').addEventListener('click',event=>{
    //prevent login from form if all the input feilds are valid
    if(!email.validity.valueMissing && !password.validity.valueMissing){
        event.preventDefault()
        message.innerHTML=''
        console.log("login prevented by form")
        //make an ajax request to php server for login
        let formData = $('#login').serialize()
        $.ajax({ 
            url: 'php/login.php',
            method: 'POST',
            data: formData,
            success: function(response) {
              console.log(response)
              let res = JSON.parse(response)
              if(res.token){
                localStorage.setItem('tokenId',res.token)
                window.location.href = 'profile.html'
              }
              else{
                message.innerHTML=res.message
              }
            },
            error: function(xhr, status, error) {
              // Handle errors
              console.log(error);
            }
          });
    }
})