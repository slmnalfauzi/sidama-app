// Dark/Light Mode Toggle
document.addEventListener("DOMContentLoaded", () => {
  const themeToggle = document.getElementById("themeToggle")
  const themeIcon = document.getElementById("themeIcon")
  const html = document.documentElement

  // Check saved theme or system preference
  const savedTheme = localStorage.getItem("theme")
  const systemDark = window.matchMedia("(prefers-color-scheme: dark)").matches
  const currentTheme = savedTheme || (systemDark ? "dark" : "light")

  // Apply theme
  html.setAttribute("data-theme", currentTheme)
  updateThemeIcon(currentTheme)

  // Toggle theme
  if (themeToggle) {
    themeToggle.addEventListener("click", () => {
      const currentTheme = html.getAttribute("data-theme")
      const newTheme = currentTheme === "dark" ? "light" : "dark"

      html.setAttribute("data-theme", newTheme)
      localStorage.setItem("theme", newTheme)
      updateThemeIcon(newTheme)

      // Animate transition
      document.body.style.transition = "background-color 0.3s ease"
    })
  }

  function updateThemeIcon(theme) {
    if (themeIcon) {
      themeIcon.className = theme === "dark" ? "fas fa-sun" : "fas fa-moon"
    }
  }

  // Initialize tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  const bootstrap = window.bootstrap // Declare the bootstrap variable
  tooltipTriggerList.map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl))

  // Show success/error messages with SweetAlert
  const urlParams = new URLSearchParams(window.location.search)
  const success = urlParams.get("success")
  const error = urlParams.get("error")
  const Swal = window.Swal // Declare the Swal variable

  if (success) {
    Swal.fire({
      icon: "success",
      title: "Berhasil!",
      text: success,
      timer: 3000,
      showConfirmButton: false,
    })
  }

  if (error) {
    Swal.fire({
      icon: "error",
      title: "Error!",
      text: error,
      timer: 3000,
      showConfirmButton: false,
    })
  }

  // Form validation
  const forms = document.querySelectorAll("form")
  forms.forEach((form) => {
    form.addEventListener("submit", (e) => {
      if (!form.checkValidity()) {
        e.preventDefault()
        e.stopPropagation()

        Swal.fire({
          icon: "warning",
          title: "Perhatian!",
          text: "Mohon lengkapi semua field yang wajib diisi",
        })
      }
      form.classList.add("was-validated")
    })
  })

  // Auto-hide alerts
  const alerts = document.querySelectorAll(".alert")
  alerts.forEach((alert) => {
    setTimeout(() => {
      alert.style.transition = "opacity 0.5s ease"
      alert.style.opacity = "0"
      setTimeout(() => alert.remove(), 500)
    }, 5000)
  })

  // Live search (optional - requires AJAX implementation)
  const searchInput = document.querySelector('input[name="search"]')
  if (searchInput) {
    let searchTimeout
    searchInput.addEventListener("input", function () {
      clearTimeout(searchTimeout)
      searchTimeout = setTimeout(() => {
        // Implement AJAX search here if needed
        console.log("Searching for:", this.value)
      }, 500)
    })
  }

  // Confirm before leaving page with unsaved changes
  let formChanged = false
  const formInputs = document.querySelectorAll("form input, form textarea, form select")
  formInputs.forEach((input) => {
    input.addEventListener("change", () => {
      formChanged = true
    })
  })

  window.addEventListener("beforeunload", (e) => {
    if (formChanged) {
      e.preventDefault()
      e.returnValue = ""
    }
  })

  // Reset form changed flag on submit
  forms.forEach((form) => {
    form.addEventListener("submit", () => {
      formChanged = false
    })
  })
})

// Format number with thousand separator
function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

// Validate NIM format
function validateNIM(nim) {
  const nimPattern = /^[0-9]{7,20}$/
  return nimPattern.test(nim)
}

// Validate email format
function validateEmail(email) {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailPattern.test(email)
}

// Calculate age from birth date
function calculateAge(birthDate) {
  const today = new Date()
  const birth = new Date(birthDate)
  let age = today.getFullYear() - birth.getFullYear()
  const monthDiff = today.getMonth() - birth.getMonth()

  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
    age--
  }

  return age
}

// Show loading overlay
function showLoading() {
  const Swal = window.Swal // Declare the Swal variable
  Swal.fire({
    title: "Memproses...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading()
    },
  })
}

// Hide loading overlay
function hideLoading() {
  const Swal = window.Swal // Declare the Swal variable
  Swal.close()
}
