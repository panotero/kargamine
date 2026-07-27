import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    // Several pages build classes entirely in JS template strings (nav
    // menu items, remoteTable.js's pagination/row-hover colors, etc) -
    // without this the static scanner never sees them and they silently
    // don't compile even though the JS sets the class name correctly.
    "./resources/js/**/*.js",
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ["Figtree", ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [forms],
  safelist: [
    {
      pattern: /dark:.+/,
    },
  ],
};
