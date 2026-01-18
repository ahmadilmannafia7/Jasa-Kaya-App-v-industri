import defaultTheme from "tailwindcss/defaultTheme"
import forms from "@tailwindcss/forms"

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.{js,ts,jsx,tsx}",
    "./resources/css/**/*.css",
    "*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ["Figtree", ...defaultTheme.fontFamily.sans],
      },
      colors: {
        forest: {
          50: "#e8f5ee",
          100: "#d1ebdc",
          200: "#a3d7b9",
          300: "#75c396",
          400: "#47af73",
          500: "#198652",
          600: "#198652",
          700: "#146c42",
          800: "#0f5132",
          900: "#0a3721",
        },
      },
    },
  },
  plugins: [forms],
}
