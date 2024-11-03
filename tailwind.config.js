
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/views/components/*.blade.php',
    ],

    theme: {
        gridAutoColumns: {
            '2fr': 'minmax(0, 2fr)',
          },
        screens: {
            'sm': {'min': '350px', 'max': '639px'},
            md: '768px',
            lg: '1024px',
            xl: '1156px'
        },
        colors: {
            current: 'currentColor',
            transparent: 'transparent',

            black: '#000',
            white: '#fff',
            retro: {
                orange: '#FF6B35',
                yellow: '#F7C59F',
                green: '#1A936F',
                blue: '#114B5F',
                cream: '#EFEFD0',
              },
              primary: {
                50: '#e3f2fd',
                100: '#bbdefb',
                200: '#90caf9',
                300: '#64b5f6',
                400: '#42a5f5',
                500: '#2196f3',  // Primary color
                600: '#1e88e5',
                700: '#1976d2',
                800: '#1565c0',
                900: '#0d47a1',
            },
            secondary: {
                50: '#fce4ec',
                100: '#f8bbd0',
                200: '#f48fb1',
                300: '#f06292',
                400: '#ec407a',
                500: '#e91e63',  // Secondary color
                600: '#d81b60',
                700: '#c2185b',
                800: '#ad1457',
                900: '#880e4f',
            },
            surface: {
                50: '#ffffff',
                100: '#f8f9fa',
                200: '#f1f3f4',
                300: '#e8eaed',
                400: '#dadce0',
                500: '#9aa0a6',
                600: '#5f6368',
                700: '#3c4043',
                800: '#202124',
                900: '#000000',
            },
            error: {
                50: '#ffebee',
                100: '#ffcdd2',
                500: '#f44336',
                700: '#d32f2f',
            },
            success: {
                50: '#e8f5e9',
                100: '#c8e6c9',
                500: '#4caf50',
                700: '#388e3c',
            },
            warning: {
                50: '#fff3e0',
                100: '#ffe0b2',
                500: '#ff9800',
                700: '#f57c00',
            },
            info: {
                50: '#e1f5fe',
                100: '#b3e5fc',
                500: '#03a9f4',
                700: '#0288d1',
            },
            gray: {
                50: '#F1F5FB',
                100: '#F2F5F8',
                200: '#C2C9D2',
                300: '#A4AFBB',
                400: '#8594A5',
                500: '#67798E',
                600: '#526172',
                700: '#3E4955',
                800: '#293039',
                900: '#15181C',
            },

            lightGray: {
                50: '#F9FAFA',
                100: '#F3F4F6',
                200: '#E7E9ED',
                300: '#DCDFE3',
                400: '#D0D4D9',
                500: '#C4C9D0',
                600: '#9DA1A6',
                700: '#76797D',
                800: '#4E5054',
                900: '#27282A',
            },

            red: {
                50: '#FFE4E4',
                100: '#ffddde',
                200: '#ffc0c2',
                300: '#ff9497',
                400: '#ff575b',
                500: '#BE2F2F',
                600: '#f70006',
                700: '#d70005',
                800: '#762A2A',
                900: '#920a0d',
                950: '#500002',
            },

            blue: {
                50: '#F2F5FB',
                100: '#f9fafb',
                200: '#ABC6FB',
                300: '#81A9F9',
                400: '#578DF7',
                500: '#0c4460',
                600: '#245AC4',
                700: '#1B4393',
                800: '#03334A',
                900: '#091631',
            },

            green: {
                50: '#E8FEE2',
                100: '#D1F1E6',
                200: '#A2E4CE',
                300: '#74D6B5',
                400: '#45C99D',
                500: '#3E9627',
                600: '#12966A',
                700: '#0E704F',
                800: '#1D580E',
                900: '#05251A',
            },

            indigo: {
                50: '#EBEAFC',
                100: '#D7D5F8',
                200: '#AFABF1',
                300: '#8880EB',
                400: '#6056E4',
                500: '#382CDD',
                600: '#2D23B1',
                700: '#221A85',
                800: '#161258',
                900: '#0B092C',
            },

            purple: {
                50: '#F2EAFC',
                100: '#E6D4F8',
                200: '#CDA9F2',
                300: '#B37EEB',
                400: '#9A53E5',
                500: '#8128DE',
                600: '#6720B2',
                700: '#4D1885',
                800: '#341059',
                900: '#1A082C',
            },

            orange: {
                50: '#FEF2EA',
                100: '#FDE4D4',
                200: '#FBCAA9',
                300: '#FAAF7E',
                400: '#F89553',
                500: '#EB7100',
                600: '#C56220',
                700: '#944918',
                800: '#834D1C',
                900: '#311808',
            },

            teal: {
                50: '#effdfd',
                100: '#d2f7f9',
                200: '#aaf0f4',
                300: '#76e4ec',
                400: '#39d0dd',
                500: '#14b4c6',
                600: '#0892a2',
                700: '#097684',
                800: '#0b5e6b',
                900: '#0d4f5a',
            },

            yellow: {
                50: '#fefce8',
                100: '#fef9c3',
                200: '#fef08a',
                300: '#fde047',
                400: '#facc15',
                500: '#eab308',
                600: '#ca8a04',
                700: '#a16207',
                800: '#854d0e',
                900: '#713f12',
                950: '#422006',
            },
        },
        spacing: {
            px: '1px',
            '0': '0px',
            '0.5': '0.125rem',
            '1': '0.25rem',
            '1.5': '0.375rem',
            '2': '0.5rem',
            '2.5': '0.625rem',
            '3': '0.75rem',
            '3.5': '0.875rem',
            '4': '1rem',
            '5': '1.25rem',
            '6': '1.5rem',
            '7': '1.75rem',
            '8': '2rem',
            '9': '2.25rem',
            '10': '2.5rem',
            '11': '2.75rem',
            '12': '3rem',
            '14': '3.5rem',
            '16': '4rem',
            '20': '5rem',
            '24': '6rem',
            '28': '7rem',
            '32': '8rem',
            '36': '9rem',
            '40': '10rem',
            '44': '11rem',
            '48': '12rem',
            '52': '13rem',
            '56': '14rem',
            '60': '15rem',
            '64': '16rem',
            '72': '18rem',
            '80': '20rem',
            '96': '24rem',
            '112': '28rem',
            '128': '32rem',
            '144': '36rem',
        },
        fontFamily: {
            'display': 'Poppins, system-ui',
            'body': 'Poppins , system-ui',
            'poppins': 'Poppins , system-ui',
        },
        fontSize: {
            xs: ['0.75rem', { lineHeight: '1rem' }],
            sm: ['0.875rem', { lineHeight: '1.25rem' }],
            base: ['1rem', { lineHeight: '1.5rem' }],
            lg: ['1.125rem', { lineHeight: '1.75rem' }],
            xl: ['1.25rem', { lineHeight: '1.75rem' }],
            '2xl': ['1.5rem', { lineHeight: '2rem' }],
            '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
            '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
            '5xl': ['3rem', { lineHeight: '1.16' }],
            '6xl': ['3.75rem', { lineHeight: '1' }],
            '7xl': ['4.5rem', { lineHeight: '1' }],
            '8xl': ['6rem', { lineHeight: '1' }],
            '9xl': ['8rem', { lineHeight: '1' }],
        },
        boxShadow: {
            'elevation-1': '0 1px 2px 0 rgb(0 0 0 / 0.05)',
            'elevation-2': '0 2px 4px -1px rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)',
            'elevation-3': '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
            'elevation-4': '0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)',
            'elevation-5': '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)',
        },
        fontWeight: {
            thin: '100',
            extralight: '200',
            light: '300',
            normal: '400',
            medium: '500',
            semibold: '600',
            bold: '700',
            extrabold: '800',
            black: '900',
        },
        translate: ['group-hover'],
        height: theme => ({
            auto: 'auto',
            ...theme('spacing'),
            full: '100%',
            screen: '100vh',
        }),
        maxHeight: {
            full: '100%',
            screen: '100vh',
        },
        maxWidth: {
            none: 'none',
            xs: '20rem',
            sm: '24rem',
            md: '28rem',
            lg: '32rem',
            xl: '36rem',
            '2xl': '42rem',
            '3xl': '48rem',
            '4xl': '56rem',
            '5xl': '64rem',
            '6xl': '72rem',
            '7xl': '80rem',
            full: '100%',
            min: 'min-content',
            max: 'max-content',
            prose: '65ch',
        },
        minHeight: {
            '0': '0',
            full: '100%',
            screen: '100vh',
        },
        minWidth: {
            '0': '0',
            full: '100%',
        },
        placeholderColor: theme => theme('colors'),
        textColor: theme => ({
            ...theme('colors'),
            body: '#15181C',
        }),
    },
    corePlugins: {
        preflight: false,
    },

    plugins: [
      require('@tailwindcss/typography'),
      require('@tailwindcss/forms')({
        strategy: 'class',
    }),
      require('@tailwindcss/aspect-ratio'),
      require('@tailwindcss/container-queries'),  
      "prettier-plugin-tailwindcss",
    ],
};
