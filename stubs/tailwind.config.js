const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    // mode: 'jit',
    
    darkMode: false, // or 'media' or 'class'
    
    purge: {
        content: [
            './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
            './storage/framework/views/*.php',
            './resources/**/*.blade.php',
            './resources/**/*.js',
            './resources/**/*.vue',
        ],
    },
    
    theme: {
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            black: colors.black,
            white: colors.white,
            red: colors.red,
            blue: colors.blue,
            orange: colors.orange,
            yellow: colors.yellow,
            green: colors.green,
            gray: colors.blueGray,
            teal: {
                50: '#eaf9fb',
                100: '#cdf2f6',
                200: '#a2e7ed',
                300: '#78dbe5',
                400: '#4dd0dd',
                500: '#22a6b3',
                600: '#1a7e88',
                700: '#14646c',
                800: '#0c3c41',
                900: '#072224',
            },
        },
        extend: {
            keyframes: {
                'fade-in-up': {
                    '0%': {
                        opacity: '0',
                        transform: 'translateY(0)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(-10px)',
                    },
                },
            },
            animation: {
                'fade-in-up': 'fade-in-up 0.2s ease-out',
            },
            borderColor: theme => ({
                DEFAULT: theme('colors.gray.200', 'currentColor'),
            }),
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: theme => ({
                outline: '0 0 0 2px ' + theme('colors.blueGray.500'),
            }),
            fill: theme => theme('colors'),
        },
    },

    variants: {
        extend: {
            fill: ['focus', 'group-hover'],
        },
    },
    
    plugins: [
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography'),
    ],
}
