/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: ['./src/**/*.{html,js,php}'],
  theme: {
    extend: {
      colors: {
        accent: '#1a4d2e',
        primary: '#339a46',
        'primary-light': '#4c9a5f',
        'background-light': '#fcfdfc',
        'background-dark': '#0a140d',
      },
      fontFamily: {
        display: ['Work Sans', 'sans-serif'],
        serif: ['Lora', 'serif'],
      },
      borderRadius: {
        DEFAULT: '0.25rem',
        lg: '0.5rem',
        xl: '0.75rem',
        full: '9999px',
      },
      keyframes: {
        'bounce-subtle': {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-8px)' },
        },
      },
      animation: {
        'bounce-subtle': 'bounce-subtle 2s ease-in-out infinite',
      },
    },
  },
  plugins: [require('@tailwindcss/forms'), require('@tailwindcss/container-queries')],
};
