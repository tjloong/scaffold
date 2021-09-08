const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    mode: 'jit',
    
    darkMode: false, // or 'media' or 'class'
    
    purge: {
        content: [
            './node_modules/@jiannius/ui/src/**/*.vue',
            './node_modules/@jiannius/ui/src/**/*.js',
            './resources/**/*.blade.php',
            './resources/**/*.js',
            './resources/**/*.vue',
        ],
    },
    
    theme: {
        extend: {
            colors: {
                theme: {
                    light: colors.blue[100],
                    DEFAULT: colors.blue[500],
                    dark: colors.blue[800],
                },
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
