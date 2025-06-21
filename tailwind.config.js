/** @type {import('tailwindcss').Config} */
import preset from './vendor/filament/support/tailwind.config.preset'
export default {
    presets: [preset],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        'node_modules/preline/dist/*.js',
        "./node_modules/flowbite/**/*.js",
        '<path-to-vendor>/solution-forest/filament-tree/resources/**/*.blade.php',
    ],
    theme: {
        extend: {
            gridTemplateColumns: {
                'desktop': 'repeat(3, minmax(0, 1fr))',
                'tablet': 'repeat(2, minmax(0, 1fr))',
            },
            colors: {
                'furniture': {
                    light: '#ef4444',   // red-500
                    DEFAULT: '#dc2626', // red-600
                    dark: '#b91c1c',    // red-700
                },
                'gray': {
                    25: '#fafafa',      // custom ultra light gray
                    light: '#9ca3af',   // gray-400
                    DEFAULT: '#6b7280', // gray-500
                    dark: '#4b5563',    // gray-600
                },
                'red': {
                    25: '#fef7f7',      // custom ultra light red
                    50: '#fef2f2',      // red-50
                    100: '#fee2e2',     // red-100
                    200: '#fecaca',     // red-200
                    300: '#fca5a5',     // red-300
                    400: '#f87171',     // red-400
                    500: '#ef4444',     // red-500
                    600: '#dc2626',     // red-600 (primary)
                    700: '#b91c1c',     // red-700
                    800: '#991b1b',     // red-800
                    900: '#7f1d1d',     // red-900
                }
            },
            // Typography System - Chuẩn hóa font và cỡ chữ
            fontFamily: {
                'sans': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif'],
                'heading': ['Montserrat', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                'body': ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                'montserrat': ['Montserrat', 'sans-serif'],
                'open-sans': ['Open Sans', 'sans-serif']
            },
            // Typography Scale - Cỡ chữ chuẩn hóa
            fontSize: {
                // Display sizes - cho hero, banner lớn
                'display-2xl': ['4.5rem', { lineHeight: '1.1', letterSpacing: '-0.02em', fontWeight: '800' }], // 72px
                'display-xl': ['3.75rem', { lineHeight: '1.1', letterSpacing: '-0.02em', fontWeight: '800' }], // 60px
                'display-lg': ['3rem', { lineHeight: '1.2', letterSpacing: '-0.01em', fontWeight: '700' }], // 48px

                // Heading sizes - cho tiêu đề
                'heading-xl': ['2.25rem', { lineHeight: '1.3', letterSpacing: '-0.01em', fontWeight: '700' }], // 36px
                'heading-lg': ['1.875rem', { lineHeight: '1.3', letterSpacing: '-0.005em', fontWeight: '600' }], // 30px
                'heading-md': ['1.5rem', { lineHeight: '1.4', fontWeight: '600' }], // 24px
                'heading-sm': ['1.25rem', { lineHeight: '1.4', fontWeight: '600' }], // 20px
                'heading-xs': ['1.125rem', { lineHeight: '1.4', fontWeight: '600' }], // 18px

                // Body sizes - cho nội dung
                'body-xl': ['1.25rem', { lineHeight: '1.6', fontWeight: '400' }], // 20px
                'body-lg': ['1.125rem', { lineHeight: '1.6', fontWeight: '400' }], // 18px
                'body-md': ['1rem', { lineHeight: '1.6', fontWeight: '400' }], // 16px
                'body-sm': ['0.875rem', { lineHeight: '1.5', fontWeight: '400' }], // 14px
                'body-xs': ['0.75rem', { lineHeight: '1.5', fontWeight: '400' }], // 12px

                // Caption sizes - cho chú thích, meta
                'caption-lg': ['0.875rem', { lineHeight: '1.4', fontWeight: '500' }], // 14px
                'caption-md': ['0.75rem', { lineHeight: '1.4', fontWeight: '500' }], // 12px
                'caption-sm': ['0.6875rem', { lineHeight: '1.3', fontWeight: '500' }], // 11px
            },
            boxShadow: {
                '3xl': '0 35px 60px -12px rgba(0, 0, 0, 0.25)',
            },
            keyframes: {
                slideIn: {
                    'from': { transform: 'translateX(-100%) scale(0.8)', opacity: '0', filter: 'blur(10px)' },
                    'to': { transform: 'translateX(0) scale(1)', opacity: '1', filter: 'blur(0)' }
                },
                slideInLeft: {
                    'from': {
                        transform: 'translateX(-100%) rotateY(-20deg)',
                        opacity: '0',
                        filter: 'blur(8px)'
                    },
                    'to': {
                        transform: 'translateX(0) rotateY(0)',
                        opacity: '1',
                        filter: 'blur(0)'
                    }
                },
                slideInRight: {
                    'from': {
                        transform: 'translateX(100%) rotateY(20deg)',
                        opacity: '0',
                        filter: 'blur(8px)'
                    },
                    'to': {
                        transform: 'translateX(0) rotateY(0)',
                        opacity: '1',
                        filter: 'blur(0)'
                    }
                },
                mergeLeft: {
                    'from': { transform: 'translateX(0)' },
                    '50%': { transform: 'translateX(-10%) scale(0.95)' },
                    'to': { transform: 'translateX(25%) scale(1)' }
                },
                mergeLeftMobile: {
                    'from': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10%) scale(0.95)' },
                    'to': { transform: 'translateY(25%) scale(1)', opacity: '0.5' }
                },
                mergeRight: {
                    'from': { transform: 'translateX(0)' },
                    '50%': { transform: 'translateX(10%) scale(0.95)' },
                    'to': { transform: 'translateX(-25%) scale(1)' }
                },
                mergeRightMobile: {
                    'from': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10%) scale(0.95)' },
                    'to': { transform: 'translateY(25%) scale(1)', opacity: '1' }
                },
                bounceIn: {
                    '0%': {
                        transform: 'translate(-50%, 100%) scale(0.8)',
                        opacity: '0',
                        filter: 'blur(8px)'
                    },
                    '60%': {
                        transform: 'translate(-50%, -20%) scale(1.1)',
                        opacity: '0.8',
                        filter: 'blur(4px)'
                    },
                    '80%': {
                        transform: 'translate(-50%, 10%) scale(0.95)',
                        opacity: '0.9',
                        filter: 'blur(2px)'
                    },
                    '100%': {
                        transform: 'translate(-50%, 0) scale(1)',
                        opacity: '1',
                        filter: 'blur(0)'
                    }
                }
            },
            animation: {
                'logo-slide': 'slideIn 1.5s cubic-bezier(0.4, 0, 0.2, 1) forwards',
                'slide-left': 'slideInLeft 1.2s cubic-bezier(0.4, 0, 0.2, 1) 1.5s forwards',
                'slide-right': 'slideInRight 1.2s cubic-bezier(0.4, 0, 0.2, 1) 2s forwards',
                'merge-left': 'mergeLeft 1.5s cubic-bezier(0.4, 0, 0.2, 1) 3s forwards',
                'merge-right': 'mergeRight 1.5s cubic-bezier(0.4, 0, 0.2, 1) 3s forwards',
                'bounce-in': 'bounceIn 1s cubic-bezier(0.4, 0, 0.2, 1) 4s forwards'
            }
        },
    },
    plugins: [
        require('preline/plugin'),
        require('flowbite/plugin'),
        // Plugin tùy chỉnh cho Typography Components
        function({ addComponents, theme }) {
            addComponents({
                // Hero/Banner Typography
                '.hero-title': {
                    fontSize: theme('fontSize.display-lg[0]'),
                    lineHeight: theme('fontSize.display-lg[1].lineHeight'),
                    letterSpacing: theme('fontSize.display-lg[1].letterSpacing'),
                    fontWeight: theme('fontSize.display-lg[1].fontWeight'),
                    fontFamily: theme('fontFamily.heading'),
                    color: theme('colors.gray.900'),
                    '@screen md': {
                        fontSize: theme('fontSize.display-xl[0]'),
                        lineHeight: theme('fontSize.display-xl[1].lineHeight'),
                        letterSpacing: theme('fontSize.display-xl[1].letterSpacing'),
                        fontWeight: theme('fontSize.display-xl[1].fontWeight'),
                    },
                    '@screen lg': {
                        fontSize: theme('fontSize.display-2xl[0]'),
                        lineHeight: theme('fontSize.display-2xl[1].lineHeight'),
                        letterSpacing: theme('fontSize.display-2xl[1].letterSpacing'),
                        fontWeight: theme('fontSize.display-2xl[1].fontWeight'),
                    }
                },

                // Section Headings
                '.section-title': {
                    fontSize: theme('fontSize.heading-lg[0]'),
                    lineHeight: theme('fontSize.heading-lg[1].lineHeight'),
                    letterSpacing: theme('fontSize.heading-lg[1].letterSpacing'),
                    fontWeight: theme('fontSize.heading-lg[1].fontWeight'),
                    fontFamily: theme('fontFamily.heading'),
                    color: theme('colors.gray.900'),
                    '@screen md': {
                        fontSize: theme('fontSize.heading-xl[0]'),
                        lineHeight: theme('fontSize.heading-xl[1].lineHeight'),
                        letterSpacing: theme('fontSize.heading-xl[1].letterSpacing'),
                        fontWeight: theme('fontSize.heading-xl[1].fontWeight'),
                    }
                },

                // Card Titles
                '.card-title': {
                    fontSize: theme('fontSize.heading-sm[0]'),
                    lineHeight: theme('fontSize.heading-sm[1].lineHeight'),
                    fontWeight: theme('fontSize.heading-sm[1].fontWeight'),
                    fontFamily: theme('fontFamily.heading'),
                    color: theme('colors.gray.900'),
                    '@screen md': {
                        fontSize: theme('fontSize.heading-md[0]'),
                        lineHeight: theme('fontSize.heading-md[1].lineHeight'),
                        fontWeight: theme('fontSize.heading-md[1].fontWeight'),
                    }
                },

                // Subtitle/Description
                '.subtitle': {
                    fontSize: theme('fontSize.body-lg[0]'),
                    lineHeight: theme('fontSize.body-lg[1].lineHeight'),
                    fontWeight: theme('fontSize.body-lg[1].fontWeight'),
                    fontFamily: theme('fontFamily.body'),
                    color: theme('colors.gray.600'),
                    '@screen md': {
                        fontSize: theme('fontSize.body-xl[0]'),
                        lineHeight: theme('fontSize.body-xl[1].lineHeight'),
                        fontWeight: theme('fontSize.body-xl[1].fontWeight'),
                    }
                },

                // Body Text
                '.body-text': {
                    fontSize: theme('fontSize.body-md[0]'),
                    lineHeight: theme('fontSize.body-md[1].lineHeight'),
                    fontWeight: theme('fontSize.body-md[1].fontWeight'),
                    fontFamily: theme('fontFamily.body'),
                    color: theme('colors.gray.700'),
                },

                // Small Text/Caption
                '.caption-text': {
                    fontSize: theme('fontSize.caption-md[0]'),
                    lineHeight: theme('fontSize.caption-md[1].lineHeight'),
                    fontWeight: theme('fontSize.caption-md[1].fontWeight'),
                    fontFamily: theme('fontFamily.body'),
                    color: theme('colors.gray.500'),
                },

                // Button Typography
                '.btn-text': {
                    fontSize: theme('fontSize.body-md[0]'),
                    lineHeight: theme('fontSize.body-md[1].lineHeight'),
                    fontWeight: '600',
                    fontFamily: theme('fontFamily.body'),
                    letterSpacing: '0.025em',
                },

                '.btn-text-lg': {
                    fontSize: theme('fontSize.body-lg[0]'),
                    lineHeight: theme('fontSize.body-lg[1].lineHeight'),
                    fontWeight: '600',
                    fontFamily: theme('fontFamily.body'),
                    letterSpacing: '0.025em',
                },

                // Navigation Typography
                '.nav-text': {
                    fontSize: theme('fontSize.body-md[0]'),
                    lineHeight: theme('fontSize.body-md[1].lineHeight'),
                    fontWeight: '500',
                    fontFamily: theme('fontFamily.body'),
                    color: theme('colors.gray.700'),
                },

                // Meta/Badge Typography
                '.badge-text': {
                    fontSize: theme('fontSize.caption-lg[0]'),
                    lineHeight: theme('fontSize.caption-lg[1].lineHeight'),
                    fontWeight: theme('fontSize.caption-lg[1].fontWeight'),
                    fontFamily: theme('fontFamily.body'),
                    letterSpacing: '0.05em',
                    textTransform: 'uppercase',
                }
            })
        }
    ],
    darkMode: 'class',
}
