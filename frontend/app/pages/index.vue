<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 pt-32 pb-16 px-6 relative overflow-hidden">
    <!-- Background Decoration -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-300 dark:bg-indigo-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-300 dark:bg-purple-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse" style="animation-delay: 2s;"></div>
      <div class="absolute top-40 left-1/2 w-60 h-60 bg-pink-300 dark:bg-pink-800 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-xl opacity-70 animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
      <!-- Header -->
      <div class="text-center mb-20">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mb-6 shadow-lg">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
          </svg>
        </div>
        <h1 class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-gray-900 via-indigo-900 to-purple-900 dark:from-white dark:via-indigo-200 dark:to-purple-200 bg-clip-text text-transparent mb-6">
          Welcome to Serve
        </h1>
        <p class="mt-4 text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
          Your trusted platform for seamless service management and user administration. 
          <span class="text-indigo-600 dark:text-indigo-400 font-semibold">Experience the future</span> of digital services.
        </p>
      </div>

      <div class="grid lg:grid-cols-2 gap-12">
        <!-- Main Content -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-10 rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/20 hover:shadow-3xl transition-all duration-300">
          <div class="flex items-center mb-8">
            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">About Our Platform</h2>
          </div>
          
          <div class="space-y-6 text-gray-700 dark:text-gray-300">
            <p class="text-lg leading-relaxed">
              Welcome to <span class="font-semibold text-indigo-600 dark:text-indigo-400">Serve</span>, a comprehensive platform designed to streamline your service management needs. 
              Our system provides powerful tools for user administration, authentication, and service coordination.
            </p>
            <p class="text-lg leading-relaxed">
              Built with modern technologies including <span class="font-semibold text-red-500">Laravel</span> for the backend and <span class="font-semibold text-green-500">Nuxt.js</span> for the frontend, 
              we ensure a robust, scalable, and user-friendly experience.
            </p>
            
            <div class="space-y-6">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                <span class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">✓</span>
                Key Features
              </h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200 dark:border-green-800">
                  <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                  </div>
                  <span class="font-medium text-gray-800 dark:text-gray-200">User Authentication</span>
                </div>
                <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                  <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                  </div>
                  <span class="font-medium text-gray-800 dark:text-gray-200">Admin Dashboard</span>
                </div>
                <div class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl border border-purple-200 dark:border-purple-800">
                  <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                  </div>
                  <span class="font-medium text-gray-800 dark:text-gray-200">Secure API</span>
                </div>
                <div class="flex items-center p-4 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-xl border border-orange-200 dark:border-orange-800">
                  <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                  </div>
                  <span class="font-medium text-gray-800 dark:text-gray-200">Responsive Design</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions & Stats -->
        <div class="flex flex-col justify-between space-y-8">
          <!-- Quick Actions -->
          <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-10 rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/20 hover:shadow-3xl transition-all duration-300">
            <div class="flex items-center mb-8">
              <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
              <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Quick Actions</h2>
            </div>
            
            <div class="space-y-4">
              <button class="group w-full py-4 px-6 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 text-left flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-4 group-hover:bg-white/30 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                  </svg>
                </div>
                <div>
                  <div class="font-bold">Login to Your Account</div>
                  <div class="text-sm opacity-90">Access your dashboard</div>
                </div>
              </button>
              
              <button class="group w-full py-4 px-6 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 text-left flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-4 group-hover:bg-white/30 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                  </svg>
                </div>
                <div>
                  <div class="font-bold">Create New Account</div>
                  <div class="text-sm opacity-90">Join our platform</div>
                </div>
              </button>
              
              <button class="group w-full py-4 px-6 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 text-left flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-4 group-hover:bg-white/30 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                  </svg>
                </div>
                <div>
                  <div class="font-bold">Contact Support</div>
                  <div class="text-sm opacity-90">Get help & assistance</div>
                </div>
              </button>
            </div>
          </div>

          <!-- Stats Card -->
          <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-10 rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/20 hover:shadow-3xl transition-all duration-300">
            <div class="flex items-center mb-8">
              <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
              </div>
              <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Platform Stats</h2>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
              <div class="p-6 bg-gradient-to-br from-indigo-50 to-blue-100 dark:from-indigo-900/30 dark:to-blue-900/30 rounded-2xl border border-indigo-200 dark:border-indigo-800 text-center group hover:scale-105 transition-transform duration-300">
                <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-2 group-hover:scale-110 transition-transform duration-300">500+</div>
                <div class="text-sm font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wide">Active Users</div>
              </div>
              <div class="p-6 bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-2xl border border-green-200 dark:border-green-800 text-center group hover:scale-105 transition-transform duration-300">
                <div class="text-4xl font-bold text-green-600 dark:text-green-400 mb-2 group-hover:scale-110 transition-transform duration-300">99.9%</div>
                <div class="text-sm font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wide">Uptime</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
