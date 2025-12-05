// eslint.config.cjs
const vue = require('eslint-plugin-vue');
const tsPlugin = require('@typescript-eslint/eslint-plugin');
const tsParser = require('@typescript-eslint/parser');
const prettier = require('eslint-plugin-prettier');
const importPlugin = require('eslint-plugin-import');
module.exports = [
    {
        files: ['resources/js/**/*.{js,ts,vue}'],
        ignores: ['node_modules', 'vendor', 'public'],

        languageOptions: {
            parser: require('vue-eslint-parser'),
            parserOptions: {
                parser: tsParser,
                ecmaVersion: 'latest',
                sourceType: 'module',
                extraFileExtensions: ['.vue'],
            },
        },

        plugins: {
            vue,
            '@typescript-eslint': tsPlugin,
            import: importPlugin,
            prettier,
        },

        rules: {
            // Prettier
            'prettier/prettier': 'warn',

            // TS rules
            '@typescript-eslint/no-explicit-any': 'off',

            // Vue rules
            'vue/multi-word-component-names': 'off',

            // You removed eslint-plugin-tailwindcss, so no tailwind rules here
        },
    },
];
