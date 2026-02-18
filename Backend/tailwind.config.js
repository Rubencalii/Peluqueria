/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "primary": "#8311d4",
        "primary-hover": "#6b0eb0",
        "primary-light": "#f3e8ff",
        "brand-blue": "#135bec",
        "brand-blue-hover": "#0f4bc4",
        "background-light": "#f6f6f8",
        "background-dark": "#101622",
        "surface-light": "#ffffff",
        "surface-dark": "#1a2233",
        "border-light": "#e2e8f0",
        "border-dark": "#2d3748",
        "slate-custom": "#64748b",
      },
      fontFamily: {
        "display": ["Manrope", "sans-serif"],
        "sans": ["Manrope", "sans-serif"]
      },
      borderRadius: {
        "DEFAULT": "0.25rem",
        "lg": "0.5rem",
        "xl": "0.75rem",
        "2xl": "1rem",
        "full": "9999px"
      },
      animation: {
        'fade-in-up': 'fadeInUp 0.5s ease-out forwards',
      },
      keyframes: {
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(10px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/container-queries'),
  ],
}
