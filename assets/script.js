document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('registration-form')
  const emailField = document.getElementById('email')
  const confirmEmailField = document.getElementById('confirm-email')
  const passwordField = document.getElementById('password')
  const confirmPasswordField = document.getElementById('confirm-password')
  const userRoleField = document.getElementById('user_role')
  const vendedorMessage = document.getElementById('vendedor-message')

  // Div para mostrar errores de validación
  const emailError = document.createElement('div')
  emailError.id = 'email-error'
  emailError.style.color = 'red'
  emailError.style.display = 'none'
  emailField.parentNode.insertBefore(emailError, confirmEmailField.nextSibling)

  const passwordError = document.createElement('div')
  passwordError.id = 'password-error'
  passwordError.style.color = 'red'
  passwordError.style.display = 'none'
  passwordField.parentNode.insertBefore(
    passwordError,
    confirmPasswordField.nextSibling
  )

  // Mostrar mensaje adicional si se selecciona "Vendedor"
  userRoleField.addEventListener('change', function () {
    vendedorMessage.style.display =
      userRoleField.value === 'vendedor' ? 'block' : 'none'
  })

  // Validación en el formulario
  if (form) {
    form.addEventListener('submit', function (e) {
      let isValid = true

      // Validación de correos electrónicos
      if (emailField.value !== confirmEmailField.value) {
        e.preventDefault()
        emailError.textContent = 'Los correos electrónicos no coinciden.'
        emailError.style.display = 'block'
        isValid = false
      } else {
        emailError.style.display = 'none'
      }

      // Validación de contraseñas
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
