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
      },
      backgroundImage: {
        'staticMC': "url('staticMonteCarlo.gif')",
        'gifMC': "url('animMonteCarlo.gif')",
      }
    },
  },
  plugins: [
    require('taos/plugin'),
    require('tailwindcss-animated'),
    require('@tailwindcss/forms')
  ],
  safelist: [
    '!duration-[0ms]',
    '!delay-[0ms]',
    'html.js :where([class*="taos:"]:not(.taos-init))'
  ]
}

