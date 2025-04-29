<template>
    <nav
        class="flex items-center justify-between bg-gradient-to-l from-indigo-50 to-slate-50 shadow-md sticky top-0 px-6 py-3 z-40 border-b border-gray-100">
        <!-- Left Section -->
        <div class="flex items-center space-x-4">
            <a href="index.html" class="hidden lg:flex items-center">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow-md">
                    <i class="fas fa-hashtag text-white text-sm"></i>
                </div>
            </a>

            <button class="text-gray-500 hover:text-indigo-600 lg:hidden">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <form class="hidden md:flex">
                <div class="relative">
                    <input
                        class="w-64 bg-gray-100 rounded-full px-4 py-2 pl-10 focus:ring-2 focus:ring-indigo-200 focus:outline-none transition-all duration-200"
                        type="search" placeholder="Search...">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </form>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-6">
            <!-- Messages -->
            <div class="relative group">
                <button class="text-gray-500 hover:text-indigo-600 transition-colors relative">
                    <i class="fas fa-envelope text-xl"></i>
                    <span
                        class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-white text-xs flex items-center justify-center">3</span>
                </button>
                <div
                    class="hidden group-hover:block absolute right-0 mt-2 w-72 bg-white shadow-xl rounded-lg py-2 z-50 border border-gray-100">
                    <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                        <h4 class="font-medium text-gray-800">Messages</h4>
                        <span class="text-xs text-indigo-600">3 New</span>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        <!-- Message items would go here -->
                    </div>
                    <a href="#" class="block text-center py-2 text-sm text-indigo-600 hover:bg-gray-50">View
                        All</a>
                </div>
            </div>

            <!-- Notifications -->
            <div class="relative group">
                <button class="text-gray-500 hover:text-indigo-600 transition-colors relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span
                        class="absolute -top-1 -right-1 w-4 h-4 bg-yellow-500 rounded-full text-white text-xs flex items-center justify-center">5</span>
                </button>
                <div
                    class="hidden group-hover:block absolute right-0 mt-2 w-72 bg-white shadow-xl rounded-lg py-2 z-50 border border-gray-100">
                    <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                        <h4 class="font-medium text-gray-800">Notifications</h4>
                        <span class="text-xs text-indigo-600">5 New</span>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        <!-- Notification items would go here -->
                    </div>
                    <a href="#" class="block text-center py-2 text-sm text-indigo-600 hover:bg-gray-50">View
                        All</a>
                </div>
            </div>

            <!-- User Profile -->
            <div class="relative group">
                <button class="flex items-center space-x-2">
                    <div
                        class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center overflow-hidden shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 text-indigo-600">
                            <path fill-rule="evenodd"
                                d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="hidden lg:inline text-gray-700 font-medium">John Doe</span>
                    <i
                        class="fas fa-chevron-down hidden lg:inline text-xs text-gray-400 group-hover:text-indigo-600"></i>
                </button>
                <div
                    class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white shadow-xl rounded-lg py-1 z-50 border border-gray-100">
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">My
                        Profile</a>
                    <a href="#"
                        class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Settings</a>
                    <div class="border-t border-gray-100"></div>
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Log
                        Out</a>
                </div>
            </div>
        </div>
    </nav>
</template>
