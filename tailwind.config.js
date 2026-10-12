import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import containerQueries from '@tailwindcss/container-queries';

/*
 * Warna brand dibaca dari CSS variables di resources/css/app.css.
 * Nilai variable diganti per peran lewat data-role pada <html>:
 *   teacher = biru, student = hijau terang, admin = hijau gelap.
 * Nama token (primary, surface, on-surface, ...) tetap dipertahankan
 * supaya kelas di view yang sudah ada tetap berfungsi.
 */
const role = (name) => `rgb(var(${name}) / <alpha-value>)`;

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // Warna brand per peran
                primary: role('--c-primary'),
                'primary-container': role('--c-primary-container'),
                'on-primary': role('--c-on-primary'),
                'on-primary-container': role('--c-on-primary-container'),
                'primary-fixed': role('--c-primary-fixed'),
                'primary-fixed-dim': role('--c-primary-fixed-dim'),
                'on-primary-fixed': role('--c-on-primary-fixed'),
                'on-primary-fixed-variant': role('--c-on-primary-fixed'),
                'inverse-primary': role('--c-primary-fixed-dim'),
                'surface-tint': role('--c-primary'),
                dark: role('--c-dark'),
                paper: '#EEF1EA',
                accent: role('--c-accent'),
                'on-accent': role('--c-on-accent'),

                // Netral (sama untuk semua peran)
                background: '#F6F7F9',
                'on-background': '#0F172A',
                surface: '#FFFFFF',
                'surface-bright': '#FFFFFF',
                'surface-dim': '#E2E8F0',
                'surface-variant': '#EEF2F6',
                'surface-container-lowest': '#FFFFFF',
                'surface-container-low': '#F8FAFC',
                'surface-container': '#F1F5F9',
                'surface-container-high': '#E8EDF3',
                'surface-container-highest': '#DFE6EE',
                'on-surface': '#0F172A',
                'on-surface-variant': '#475569',
                'inverse-surface': '#0F172A',
                'inverse-on-surface': '#F8FAFC',
                outline: '#94A3B8',
                'outline-variant': '#E2E8F0',

                secondary: '#64748B',
                'on-secondary': '#FFFFFF',
                'secondary-container': '#F1F5F9',
                'on-secondary-container': '#334155',
                'secondary-fixed': '#E2E8F0',
                'secondary-fixed-dim': '#CBD5E1',
                'on-secondary-fixed': '#0F172A',
                'on-secondary-fixed-variant': '#334155',

                // Status
                tertiary: '#0F766E',
                'on-tertiary': '#FFFFFF',
                'tertiary-container': '#CCFBF1',
                'on-tertiary-container': '#115E59',
                'tertiary-fixed': '#CCFBF1',
                'tertiary-fixed-dim': '#5EEAD4',
                'on-tertiary-fixed': '#042F2E',
                'on-tertiary-fixed-variant': '#115E59',
                error: '#B91C1C',
                'on-error': '#FFFFFF',
                'error-container': '#FEE2E2',
                'on-error-container': '#991B1B',
                warning: '#B45309',
                'warning-container': '#FEF3C7',
                'on-warning-container': '#92400E',
            },

            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },

            // Type scale. Satu sumber untuk semua kelas text-<nama>.
            fontSize: {
                'display-lg': ['48px', { lineHeight: '56px', letterSpacing: '-0.02em', fontWeight: '700' }],
                'display-md': ['40px', { lineHeight: '48px', letterSpacing: '-0.02em', fontWeight: '700' }],
                'headline-lg': ['32px', { lineHeight: '40px', letterSpacing: '-0.015em', fontWeight: '700' }],
                'headline-lg-mobile': ['26px', { lineHeight: '32px', letterSpacing: '-0.01em', fontWeight: '700' }],
                'headline-md': ['24px', { lineHeight: '32px', letterSpacing: '-0.01em', fontWeight: '700' }],
                'headline-sm': ['20px', { lineHeight: '28px', fontWeight: '700' }],
                'title-lg': ['20px', { lineHeight: '28px', fontWeight: '600' }],
                'title-md': ['16px', { lineHeight: '24px', fontWeight: '600' }],
                'title-sm': ['14px', { lineHeight: '20px', fontWeight: '600' }],
                'body-lg': ['16px', { lineHeight: '24px', fontWeight: '400' }],
                'body-md': ['14px', { lineHeight: '22px', fontWeight: '400' }],
                'body-sm': ['13px', { lineHeight: '20px', fontWeight: '400' }],
                'body-xs': ['12px', { lineHeight: '18px', fontWeight: '400' }],
                'label-lg': ['14px', { lineHeight: '20px', fontWeight: '600' }],
                'label-md': ['13px', { lineHeight: '18px', fontWeight: '600' }],
                'label-sm': ['12px', { lineHeight: '16px', letterSpacing: '0.02em', fontWeight: '600' }],
                'label-xs': ['11px', { lineHeight: '14px', letterSpacing: '0.04em', fontWeight: '600' }],
            },

            // Radius lebih kecil dan konsisten (kartu 12-16px)
            borderRadius: {
                DEFAULT: '0.5rem',
                sm: '0.375rem',
                md: '0.625rem',
                lg: '0.75rem',
                xl: '1rem',
                '2xl': '1.25rem',
                '3xl': '1.5rem',
                full: '9999px',
            },

            // Shadow ringan. Kelas shadow-xl/2xl yang sudah ada ikut menjadi ringan.
            boxShadow: {
                sm: '0 1px 2px 0 rgb(15 23 42 / 0.04)',
                DEFAULT: '0 1px 3px 0 rgb(15 23 42 / 0.06), 0 1px 2px -1px rgb(15 23 42 / 0.04)',
                md: '0 2px 6px -1px rgb(15 23 42 / 0.07)',
                lg: '0 4px 12px -4px rgb(15 23 42 / 0.08)',
                xl: '0 6px 16px -6px rgb(15 23 42 / 0.10)',
                '2xl': '0 10px 24px -8px rgb(15 23 42 / 0.12)',
            },

            spacing: {
                gutter: '24px',
                base: '4px',
                xs: '4px',
                xl: '32px',
                sm: '8px',
                lg: '24px',
                'margin-mobile': '16px',
                'margin-desktop': '32px',
                'container-max': '1280px',
                '2xl': '48px',
                md: '16px',
            },
        },
    },

    plugins: [forms, containerQueries],
};
