/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    extend: {
      colors: {
        'base-darkgrey': '#272727',
        'base-grey': '#303030',
        'base-asphalt': '#363636',
        'base-light-grey': '#D6D6D6',
        'base-grey2': '#D1D1D1',
        'base-gold': '#C0A062',
        'base-blue': '#4DAFE3',
        'base-light-blue': '#57C5FF',
        'base-red': '#DA251D',
        'base-light-red': '#E94139',
      },
    },
  },
  plugins: [
    function ({ addUtilities }) {
      addUtilities(
        {
          '.font-w100': { fontWeight: '100' },
          '.font-w200': { fontWeight: '200' },
          '.font-w300': { fontWeight: '300' },
          '.font-w400': { fontWeight: '400' },
          '.font-w500': { fontWeight: '500' },
          '.font-w600': { fontWeight: '600' },
          '.font-w700': { fontWeight: '700' },
          '.font-w800': { fontWeight: '800' },
          '.font-w900': { fontWeight: '900' },
        },
        ['responsive', 'hover'],
      )
    },
  ],
}
