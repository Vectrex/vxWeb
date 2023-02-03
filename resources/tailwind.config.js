const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './src/**/*.{vue,js,html}'
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['IBM Plex Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'vxvue': {
                    DEFAULT: 'var(--vxvue-color)',
                    '50': 'var(--vxvue-color-50)',
                    '100': 'var(--vxvue-color-100)',
                    '200': 'var(--vxvue-color-200)',
                    '300': 'var(--vxvue-color-300)',
                    '400': 'var(--vxvue-color-400)',
                    '500': 'var(--vxvue-color-500)',
                    '600': 'var(--vxvue-color-600)',
                    '700': 'var(--vxvue-color-700)',
                    '800': 'var(--vxvue-color-800)',
                    '900': 'var(--vxvue-color-900)',
                },
            }
        }
    },
    plugins: [
        require('@tailwindcss/forms')({strategy: 'class'})
    ],
}
