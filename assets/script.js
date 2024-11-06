document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('registration-form')
  const emailField = document.getElementById('email')
  const confirmEmailField = document.getElementById('confirm-email')
  const passwordField = document.getElementById('password')
  const confirmPasswordField = document.getElementById('confirm-password')
  const passwordError = document.getElementById('password-error')
  const emailError = document.createElement('div')

  emailError.id = 'email-error'
  emailError.style.display = 'none'
  emailError.style.color = 'red'
  emailError.style.fontSize = '0.9em'
  confirmEmailField.parentNode.insertBefore(
    emailError,
    confirmEmailField.nextSibling
  )

  if (form) {
    form.addEventListener('submit', function (e) {
      let isValid = true

      if (emailField.value !== confirmEmailField.value) {
        e.preventDefault()
        emailError.textContent = 'Los correos electrónicos no coinciden.'
        emailError.style.display = 'block'
        isValid = false
      } else {
        emailError.style.display = 'none'
      }

      if (passwordField.value !== confirmPasswordField.value) {
        e.preventDefault()
        passwordError.textContent = 'Las contraseñas no coinciden.'
        passwordError.style.display = 'block'
        isValid = false
      } else {
        passwordError.style.display = 'none'
      }

      if (!isValid) e.preventDefault()
    })
  }
})
