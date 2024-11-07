document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('registration-form')
  const emailField = document.getElementById('email')
  const confirmEmailField = document.getElementById('confirm-email')
  const passwordField = document.getElementById('password')
  const confirmPasswordField = document.getElementById('confirm-password')
  const userRoleField = document.getElementById('user_role')
  const vendedorMessage = document.getElementById('vendedor-message')

  function validatePassword(password) {
    const minLength = password.length >= 8
    const hasUpperCase = /[A-Z]/.test(password)
    const hasLowerCase = /[a-z]/.test(password)
    const hasNumber = /[0-9]/.test(password)
    const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(password)

    return {
      isValid:
        minLength && hasUpperCase && hasLowerCase && hasNumber && hasSymbol,
      requirements: {
        minLength,
        hasUpperCase,
        hasLowerCase,
        hasNumber,
        hasSymbol,
      },
      strength: [
        minLength,
        hasUpperCase,
        hasLowerCase,
        hasNumber,
        hasSymbol,
      ].filter(Boolean).length,
    }
  }

  // Create the container for the strength indicators
  const strengthContainer = document.createElement('div')
  strengthContainer.style.marginTop = '0.5rem'
  strengthContainer.style.fontSize = '0.85rem'

  const strengthBar = document.createElement('div')
  strengthBar.style.height = '5px'
  strengthBar.style.borderRadius = '3px'
  strengthBar.style.marginBottom = '0.5rem'
  strengthBar.style.transition = 'all 0.3s'
  strengthContainer.appendChild(strengthBar)

  const requirementsList = document.createElement('ul')
  requirementsList.style.margin = '0'
  requirementsList.style.padding = '0'
  requirementsList.style.listStyle = 'none'
  strengthContainer.appendChild(requirementsList)

  const requirements = [
    { key: 'minLength', text: 'Mínimo 8 caracteres' },
    { key: 'hasUpperCase', text: 'Al menos una mayúscula' },
    { key: 'hasLowerCase', text: 'Al menos una minúscula' },
    { key: 'hasNumber', text: 'Al menos un número' },
    { key: 'hasSymbol', text: 'Al menos un símbolo' },
  ]

  requirements.forEach((req) => {
    const li = document.createElement('li')
    li.id = `req-${req.key}`
    li.textContent = req.text
    li.style.color = '#dc3545'
    li.style.marginBottom = '0.2rem'
    requirementsList.appendChild(li)
  })

  const validatingPasswordRealTime = () => {
    if (passwordField) {
      passwordField.parentNode.insertBefore(
        strengthContainer,
        passwordField.nextSibling
      )

      // Update the strength indicator as the user types
      passwordField.addEventListener('input', function () {
        const validation = validatePassword(this.value)
        const percentage = (validation.strength / 5) * 100

        // Update the progress bar
        strengthBar.style.width = `${percentage}%`

        if (validation.strength <= 2) {
          strengthBar.style.backgroundColor = '#dc3545' // Red
        } else if (validation.strength <= 4) {
          strengthBar.style.backgroundColor = '#ffc107' // Yellow
        } else {
          strengthBar.style.backgroundColor = '#28a745' // Green
        }

        // Update the status of each requirement
        for (const [key, value] of Object.entries(validation.requirements)) {
          const reqElement = document.getElementById(`req-${key}`)
          if (reqElement) {
            reqElement.style.color = value ? '#28a745' : '#dc3545'
            reqElement.style.textDecoration = value ? 'line-through' : 'none'
          }
        }
      })
    }
  }

  // Call the password validation immediately after defining it
  validatingPasswordRealTime()

  // Form validation on submit
  if (form) {
    form.addEventListener('submit', function (e) {
      let isValid = true
      const errors = []

      // Remove previous error message if it exists
      const existingError = document.getElementById('error-message')
      if (existingError) {
        existingError.remove()
      }

      if (emailField.value !== confirmEmailField.value) {
        errors.push('Los correos electrónicos no coinciden.')
        isValid = false
      }

      if (passwordField.value !== confirmPasswordField.value) {
        errors.push('Las contraseñas no coinciden.')
        isValid = false
      }

      const passwordValidation = validatePassword(passwordField.value)
      if (!passwordValidation.isValid) {
        errors.push(
          'La contraseña debe cumplir con todos los requisitos de seguridad.'
        )
        isValid = false
      }

      if (!isValid) {
        e.preventDefault()
        const errorDiv = document.createElement('div')
        errorDiv.id = 'error-message'
        errorDiv.style.backgroundColor = 'rgba(220, 53, 69, 0.1)'
        errorDiv.style.color = '#dc3545'
        errorDiv.style.padding = '1rem'
        errorDiv.style.borderRadius = '8px'
        errorDiv.style.marginBottom = '1rem'
        errorDiv.style.border = '1px solid rgba(220, 53, 69, 0.2)'
        errorDiv.innerHTML = errors.join('<br>')
        form.insertBefore(errorDiv, form.firstChild)
      }
    })
  }
})
