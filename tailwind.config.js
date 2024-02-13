const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/vue-tailwind-datepicker/**/*.js",
  ],
  theme: {
    extend: {
      opacity: ['disabled'],
      cursor: ['disabled'],
      colors: {
        'dark-purple': '#081A51',
        'light-white': 'rgba(255,255,255,0.10)',
        'vtd-primary': colors.sky, // Light mode Datepicker color
        'vtd-secondary': colors.gray, // Dark mode Datepicker color
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

