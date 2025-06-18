/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        // Couleurs officielles Menara Prefa
        'menara-red': 'rgb(192, 15, 26)',     // Rouge principal
        'menara-dark': '#2b2f32',             // Gris foncé
        'menara-gray': '#495057',             // Gris moyen
        'menara-blue': '#052c65',             // Bleu foncé
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}