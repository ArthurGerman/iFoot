module.exports = {
  content: [
    "./index.php",
    "./src/**/*.php"
  ],
  theme: {
    extend: {
      backgroundImage:{
        hero: "url('/static/img_background.jpeg')"
      },
      fontFamily:{
        'outfit':["Outfit", 'sans-serif'],
      }
    },
  },
  plugins: [],
}
