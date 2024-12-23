/** @type {import('tailwindcss').Config} */
const plugin = require('tailwindcss/plugin');

module.exports = {
  content: ["./index.html", "./src/**/*.{html,js,jsx,ts,tsx}"],
  theme: {
    extend: {
      colors: {
        custom1: '#177B49',
        custom2: '#0D4422',
        custom3: '#2F2A1F',
        custom4: '#973131',
        custom5: '#9EB5A9',
        custom6: '#4C968B',
        custom7:'#BBDFD9',
        custom8:'#F5EBC3',
        custom9: '#C1E1D6',
        custom10 : '#81C780',
        custom11 : '#135B35',
        custom12 : '#8b0000',
        border: '#492F22',
        bar:'#34343B',
        'bg-form': '#B1D5CE',
        form: '#5D6D77',
      },
      fontFamily: {
        irish: ['Irish Grover', 'sans-serif'],
        inter: ['Inter', 'serif'],
        playfair: ['Playfair Display', 'serif'],
        poppins: ['Poppins', 'sans-serif'],
      },
      padding: {
        p1: '3.75rem',
      },
      backgroundImage: {
        'custom-radial': 'radial-gradient(circle at center, #C1E3D7 22%, #E0F1EB 61%, #FFFFFF 100%)',
        'custom-green-button': 'linear-gradient(90deg, #81C780 0%, #135B35 100%)',
        'custom-gradient-button': 'linear-gradient(90deg, #14856D 0%, #067A32 50%, #16572B 87%, #1B4B29 100%)',
        'custom-gradient2-button' : 'linear-gradient(to bottom,#81C780, #135B35)',
        'custom-gradient3-button' : 'linear-gradient(to bottom, #ff4d4d, #d20000, #8b0000)'
      },
      borderRadius: {
        1: '2rem',
      },
      borderWidth: {
        bw1: '1px',
      },
    },
  },
  plugins: [
    plugin(function ({ addBase, addComponents, addUtilities }) {
      addComponents({
        ".container": {
          "@apply max-w-[77.5rem] mx-auto px-5 md:px-10 lg:px-p1 xl:max-w-[87.5rem]": {},
        },
        ".h1": {
          "@apply font-semibold text-[2.5rem] leading-[3.25rem] md:text-[2.75rem] md:leading-[3.75rem] lg:text-[3.25rem] lg:leading-[4.0625rem] xl:text-[3.75rem] xl:leading-[4.5rem]": {},
        },
        ".h2": {
          "@apply text-[1.75rem] leading-[2.5rem] md:text-[2rem] md:leading-[2.5rem] lg:text-[2.5rem] lg:leading-[3.5rem] xl:text-[3rem] xl:leading-tight": {},
        },
        ".h3": {
          "@apply text-[1.5rem] leading-[2rem] md:text-[2rem] md:leading-[2.5rem] lg:text-[2.5rem] lg:leading-[3rem] xl:text-[3rem] xl:leading-[3.5rem]": {},
        },
        ".h4": {
          "@apply text-[1.25rem] leading-[1.75rem] md:text-[1.75rem] md:leading-[2.25rem] lg:text-[2rem] lg:leading-[2.5rem] xl:text-[2.5rem] xl:leading-[3rem]": {},
        },
        ".h5": {
          "@apply text-xl leading-[1.5rem] md:text-[1.5rem] md:leading-[2rem] lg:text-[2rem] lg:leading-[2.5rem] xl:text-[2.25rem] xl:leading-[3rem]": {},
        },
        ".h6": {
          "@apply text-lg leading-[0.75rem] font-bold md:text-[0.75rem] md:leading-[1.25rem] lg:text-[1rem] lg:leading-[1.5rem] xl:text-[1.25rem] xl:leading-[1.75rem] text-custom2": {},
        },
        ".body-1": {
          "@apply text-[0.875rem] leading-[1.5rem] md:text-[1rem] md:leading-[1.75rem] lg:text-[1.25rem] lg:leading-8": {},
        },
        ".body-2": {
          "@apply font-light text-[0.875rem] leading-6 md:text-base": {},
        },
        ".button": {
          "@apply w-48 sm:w-64 py-3 mb-4 text-lg rounded-xl font-semibold tracking-wider": {},
        },
        ".logo": {
          "@apply m-8 text-2xl text-custom1": {},
        },
        ".text-sm": {
          "@apply sm:text-base md:text-lg lg:text-xl xl:text-2xl": {},
        },
        ".text-md": {
          "@apply sm:text-lg md:text-xl lg:text-2xl xl:text-3xl": {},
        },
        ".text-lg": {
          "@apply sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl": {},
        },
        ".text-xl": {
          "@apply sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl": {},
        },
        ".text-2xl": {
          "@apply sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl": {},
        },
        ".text-3xl": {
          "@apply sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl": {},
        },
        ".footer": {
          "@apply text-sm w-full leading-6 lg:text-base lg:leading-7 bg-custom2 bottom-0 text-center text-white mt-20": {},
        },
        ".bg-shadow": {
          "@apply p-4 bg-white bg-opacity-30 rounded-1 shadow-lg border border-black w-full max-w-md md:max-w-lg lg:max-w-xl": {},
        },
        ".bg-shadow2": {
          "@apply p-4 bg-white bg-opacity-0 rounded-1 shadow-lg border border-black py-8 w-full max-w-md md:max-w-lg lg:max-w-xl": {},
        },
        ".button-lock": {
          "@apply bg-gray-400 text-white py-2 px-4 rounded-full font-bold shadow-xl w-full": {},
        },
        ".button-custom" : {
          "@apply bg-custom1 text-white py-2 px-4 rounded-full font-bold shadow-xl hover:opacity-80 w-full":{},
        },
        ".button-custom2" : {
          "@apply bg-custom-gradient2-button w-40 text-white py-2 px-4 rounded-full font-bold shadow-xl text-center items-center justify-center flex text-sm border-b-4 hover:scale-90 transition duration-300":{},
        },
        ".button-custom2-red" : {
          "@apply bg-custom-gradient3-button w-40 text-white py-2 px-4 rounded-full font-bold shadow-xl text-center items-center justify-center flex text-sm border-b-4 hover:scale-90 transition duration-300":{},
        },
        ".button-option" : {
          "@apply bg-custom6 w-max py-2 px-4 rounded-lg font-bold text-center items-center justify-center flex hover:opacity-80":{},
        },
        ".box-custom1" : {
          "@apply max-w-5xl bg-white bg-opacity-30 rounded-3xl shadow-lg font-inter border-[1.5px] border-bar":{},
        },
        ".level" : {
          "@apply w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 flex items-center justify-center rounded-full border-[3px] sm:border-[5px] md:border-[6px] border-custom6 bg-custom8 text-custom2 font-bold font-irish text-2xl sm:text-3xl md:text-4xl hover:opacity-80 cursor-pointer":{},
        },
        ".level-lock" : {
          "@apply w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 flex items-center justify-center rounded-full border-[3px] sm:border-[5px] md:border-[6px] border-gray-400 bg-gray-200 text-gray-600 font-bold font-irish text-2xl sm:text-3xl md:text-4xl hover:opacity-80 cursor-pointer":{},
        },
        ".word-custom" : {
          "@apply text-4xl sm:text-xl md:text-2xl lg:text-2xl xl:text-3xl font-poppins font-bold tracking-widest uppercase":{},
        },
        ".container-custom" : {
          "@apply text-lg font-poppins font-medium flex flex-col justify-center items-center w-full sm:max-w-xl md:max-w-2xl lg:max-w-3xl xl:max-w-4xl rounded-lg mx-auto px-5 sm:px-3 md:px-10 lg:px-p1 xl:px-20":{},
        },
        ".content-custom" :{
          "@apply text-lg font-poppins font-medium flex flex-col justify-center items-center w-2/3 rounded-lg mx-5 my-20 px-5 py-5 md:px-10 lg:px-p1 border border-custom2 bg-white bg-opacity-30 rounded-3xl shadow-lg":{},
        },
        ".refresh-word" : {
          "@apply bg-zinc-700 text-sm sm:text-base md:text-lg lg:text-lg xl:text-lg text-white py-2 px-4 rounded-full font-bold shadow-xl hover:opacity-80 w-full":{},
        },
        ".check-word" : {
          "@apply bg-custom1 text-sm sm:text-base md:text-lg lg:text-lg xl:text-lg text-white py-2 px-4 rounded-full font-bold shadow-xl hover:opacity-80 w-full":{},
        },              
      });
      addUtilities({
        '.text-shadow-custom': { 
          'text-shadow': '1px 2px 3px rgba(1, 0.5, 0, 0.3)',
        },
        '.text-gradient': {
          'background': 'linear-gradient(to right, #14856D 0%, #067A32 50%, #16572B 87%, #1B4B29 100%)',
          '-webkit-background-clip': 'text',
          '-webkit-text-fill-color': 'transparent',
          'display': 'inline-block',
          'text-shadow': '1px 2px 3px rgba(0, 0, 0, 0.3)',
        },
      });
    }),
  ],
};
