/** @type {import('tailwindcss').Config} */
module.exports = {
  content: {
  relative: true,
  transform: (content) => content.replace(/taos:/g, ''),
  files:["./src/**/*.{html,js,php}"]
  },
  theme: {
    extend: {
      colors: {
        'deepblue': '#002346',
        'lightblue' : '#BDD4E7',
        'lyellow' : '#FFDC00',
        'lgrey' : '#8693AB'
      }
    },
  },
  plugins: [
    require('taos/plugin'),
    require('tailwindcss-animated')
  ],
  safelist: [
    '!duration-[0ms]',
    '!delay-[0ms]',
    'html.js :where([class*="taos:"]:not(.taos-init))'
  ]
}
