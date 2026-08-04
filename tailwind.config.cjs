/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./index.html", "./src/**/*.{js,jsx,ts,tsx}"],
  theme: {
    extend: {
      colors: {
        kandura: {
          sand: "#D4B483",
          gold: "#C9A664",
          slate: "#0F1724",
        },
      },
      boxShadow: {
        card: "0 6px 18px rgba(15,17,36,0.08)",
      },
      borderRadius: {
        "xl-2": "1rem",
      },
    },
  },
  plugins: [],
};
