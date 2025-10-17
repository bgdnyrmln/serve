import type { Config } from 'tailwindcss'

export default <Partial<Config>>{
  darkMode: 'class',
  content: [
    './app/**/*.{vue,js,ts}',
    './components/**/*.{vue,js,ts}',
    './layouts/**/*.{vue,js,ts}',
    './pages/**/*.{vue,js,ts}',
    './plugins/**/*.{js,ts}',
    './nuxt.config.{js,ts}'
  ],
  safelist: [
    // Ensure backdrop blur classes are always included
    'backdrop-blur-sm',
    'bg-white/80',
    'dark:bg-gray-800/80',
    'border-white/20',
    'dark:border-gray-700/20',
    'rounded-3xl',
    'shadow-xl',
    // Status colors
    'border-l-4',
    'border-yellow-400',
    'border-green-400',
    'border-red-400',
    'border-blue-400',
    // Background gradients
    'bg-gradient-to-br',
    'from-gray-50',
    'via-blue-50',
    'to-indigo-100',
    'dark:from-gray-900',
    'dark:via-gray-800',
    'dark:to-indigo-900'
  ],
  theme: {
    extend: {}
  },
  plugins: []
}


