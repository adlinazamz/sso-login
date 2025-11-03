<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password"
                          name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me + Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700
                              text-indigo-600 shadow-sm focus:ring-indigo-500
                              dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-right text-gray-600 dark:text-gray-400 hover:text-gray-900
                          dark:hover:text-gray-100 rounded-md focus:outline-none
                          focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                          dark:focus:ring-offset-gray-800"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="w-full items-center justify-center mt-4 flex">
            <x-primary-button class="w-full">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Divider -->
        <div class="my-6 flex items-center gap-4">
            <hr class="w-full border-slate-300 dark:border-slate-700" />
            <p class="text-sm text-white text-center">or</p>
            <hr class="w-full border-slate-300 dark:border-slate-700" />
        </div>

        <!-- Social Login Buttons -->
        <div class="space-x-8 flex justify-center">
            <!-- Google -->
            <a href="{{ route('auth.provider.redirect', ['provider' => 'google']) }}"
               aria-label="Login with Google"
               class="group relative bg-white rounded-full p-3 cursor-pointer border-0 outline-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 512 512">
                    <path fill="#fbbd00" d="M120 256c0-25.367 6.989-49.13 19.131-69.477v-86.308H52.823C18.568 144.703 0 198.922 0 256s18.568 111.297 52.823 155.785h86.308v-86.308C126.989 305.13 120 281.367 120 256z"/>
                    <path fill="#0f9d58" d="m256 392-60 60 60 60c57.079 0 111.297-18.568 155.785-52.823v-86.216h-86.216C305.044 385.147 281.181 392 256 392z"/>
                    <path fill="#31aa52" d="m139.131 325.477-86.308 86.308a260.085 260.085 0 0 0 22.158 25.235C123.333 485.371 187.62 512 256 512V392c-49.624 0-93.117-26.72-116.869-66.523z"/>
                    <path fill="#3c79e6" d="M512 256a258.24 258.24 0 0 0-4.192-46.377l-2.251-12.299H256v120h121.452a135.385 135.385 0 0 1-51.884 55.638l86.216 86.216a260.085 260.085 0 0 0 25.235-22.158C485.371 388.667 512 324.38 512 256z"/>
                    <path fill="#cf2d48" d="m352.167 159.833 10.606 10.606 84.853-84.852-10.606-10.606C388.668 26.629 324.381 0 256 0l-60 60 60 60c36.326 0 70.479 14.146 96.167 39.833z"/>
                    <path fill="#eb4132" d="M256 120V0C187.62 0 123.333 26.629 74.98 74.98a259.849 259.849 0 0 0-22.158 25.235l86.308 86.308C162.883 146.72 206.376 120 256 120z"/>
                </svg>
                <span class="absolute bottom mb-2 hidden group-hover:block
                             bg-gray-700 text-white text-xs rounded py-1 px-2 shadow-lg">
                    Google
                </span>
            </a>

            <!-- Facebook -->
            <a href="{{ route('auth.provider.redirect', ['provider' => 'facebook']) }}"
               aria-label="Login with Facebook"
               class="group relative bg-white rounded-full p-3 cursor-pointer border-0 outline-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 512 512">
                    <path fill="#1877f2" d="M512 256c0 127.78-93.62 233.69-216 252.89V330h59.65L367 256h-71v-48.02c0-20.25 9.92-39.98 41.72-39.98H370v-63s-29.3-5-57.31-5c-58.47 0-96.69 35.44-96.69 99.6V256h-65v74h65v178.89C93.62 489.69 0 383.78 0 256 0 114.62 114.62 0 256 0s256 114.62 256 256z"/>
                    <path fill="#fff" d="M355.65 330 367 256h-71v-48.021c0-20.245 9.918-39.979 41.719-39.979H370v-63s-29.296-5-57.305-5C254.219 100 216 135.44 216 199.6V256h-65v74h65v178.889c13.034 2.045 26.392 3.111 40 3.111s26.966-1.066 40-3.111V330z"/>
                </svg>
                <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 hidden group-hover:block
                             bg-gray-700 text-white text-xs rounded py-1 px-2 shadow-lg">
                    Facebook
                </span>
            </a>

            <!-- Apple -->
            <a href="{{ route('auth.provider.redirect', ['provider' => 'apple']) }}"
               aria-label="Login with Apple"
               class="group relative bg-white rounded-full p-3 cursor-pointer border-0 outline-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 22.773 22.773">
                    <path d="M15.769 0h.162c.13 1.606-.483 2.806-1.228 3.675-.731.863-1.732 1.7-3.351 1.573-.108-1.583.506-2.694 1.25-3.561C13.292.879 14.557.16 15.769 0zm4.901 16.716v.045c-.455 1.378-1.104 2.559-1.896 3.655-.723.995-1.609 2.334-3.191 2.334-1.367 0-2.275-.879-3.676-.903-1.482-.024-2.297.735-3.652.926h-.462c-.995-.144-1.798-.932-2.383-1.642-1.725-2.098-3.058-4.808-3.306-8.276v-1.019c.105-2.482 1.311-4.5 2.914-5.478.846-.52 2.009-.963 3.304-.765.555.086 1.122.276 1.619.464.471.181 1.06.502 1.618.485.378-.011.754-.208 1.135-.347 1.116-.403 2.21-.865 3.652-.648 1.733.262 2.963 1.032 3.723 2.22-1.466.933-2.625 2.339-2.427 4.74.176 2.181 1.444 3.457 3.028 4.209z"/>
                </svg>
                <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 hidden group-hover:block
                             bg-gray-700 text-white text-xs rounded py-1 px-2 shadow-lg">
                    Apple
                </span>
            </a>

            <!-- X -->
                <a href="{{ route('auth.provider.redirect', ['provider' => 'x']) }}"
                    aria-label="Login with X"
                    class="group relative bg-white rounded-full p-2 cursor-pointer border-0 outline-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 48 48">
                        <path fill="#212121" fill-rule="evenodd" d="M38,42H10c-2.209,0-4-1.791-4-4V10c0-2.209,1.791-4,4-4h28	c2.209,0,4,1.791,4,4v28C42,40.209,40.209,42,38,42z" clip-rule="evenodd"></path>
                        <path fill="#fff" d="M34.257,34h-6.437L13.829,14h6.437L34.257,34z M28.587,32.304h2.563L19.499,15.696h-2.563 L28.587,32.304z"></path>
                        <polygon fill="#fff" points="15.866,34 23.069,25.656 22.127,24.407 13.823,34"></polygon>
                        <polygon fill="#fff" points="24.45,21.721 25.355,23.01 33.136,14 31.136,14"></polygon>
                    </svg>
                    <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 hidden group-hover:block
                                bg-gray-700 text-white text-xs rounded py-1 px-2 shadow-lg">
                        X
                    </span>                
                </a>
        </div>
    </form>

    <!-- Other Providers Modal -->
<div x-data="{ showModal: false }" class="text-center mt-4 relative z-10">
    <!-- Trigger -->
    <button @click="showModal = true"
            class="underline text-sm hover:text-indigo-400 transition font-medium text-white">
        {{ __('Other ways to Login') }}
    </button>

    <!-- Modal Overlay -->
    <div x-show="showModal"
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

        <!-- Modal Card -->
        <div x-show="showModal"
             x-transition.scale
             class="bg-white/10 backdrop-blur-xl border border-white/20 text-white w-[22rem] rounded-2xl shadow-2xl p-6 relative">

            <!-- Title -->
            <h2 class="text-2xl font-semibold text-center mb-8">
                {{ __('Sign in with') }}
            </h2>

            <!-- Providers -->
            <div class="flex flex-col space-y-4">
                <!-- GitLab -->
                <a href="{{ route('auth.provider.redirect', ['provider' => 'gitlab']) }}"
                   class="flex items-center gap-3 px-4 py-3 bg-[#E24329] hover:bg-[#d13c23] rounded-xl transition font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" viewBox="0 0 64 64">
                        <path fill="#e53935" d="M24 43L16 20 32 20z"/>
                        <path fill="#ff7043" d="M24 43L42 20 32 20z"/>
                        <path fill="#e53935" d="M37 5L42 20 32 20z"/>
                        <path fill="#ffa726" d="M24 43L42 20 45 28z"/>
                        <path fill="#ff7043" d="M24 43L6 20 16 20z"/>
                        <path fill="#e53935" d="M11 5L6 20 16 20z"/>
                        <path fill="#ffa726" d="M24 43L6 20 3 28z"/>
                    </svg>
                    <span>Continue with GitLab</span>
                </a>

                <!-- GitHub -->
                <a href="{{ route('auth.provider.redirect', ['provider' => 'github']) }}"
                   class="flex items-center gap-3 px-4 py-3 bg-black hover:bg-gray-800 rounded-xl transition font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <rect width="24" height="24" rx="4" fill="#ffa726"/>
                        <path d="M15,3C8.373,3,3,8.373,3,15c0,5.623,3.872,10.328,9.092,11.63C12.036,26.468,12,26.28,12,26.047v-2.051 
                            c-0.487,0-1.303,0-1.508,0c-0.821,0-1.551-0.353-1.905-1.009c-0.393-0.729-0.461-1.844-1.435-2.526 
                            c-0.289-0.227-0.069-0.486,0.264-0.451c0.615,0.174,1.125,0.596,1.605,1.222c0.478,0.627,0.703,0.769,1.596,0.769 
                            c0.433,0,1.081-0.025,1.691-0.121c0.328-0.833,0.895-1.6,1.588-1.962c-3.996-0.411-5.903-2.399-5.903-5.098 
                            c0-1.162,0.495-2.286,1.336-3.233C9.053,10.647,8.706,8.73,9.435,8c1.798,0,2.885,1.166,3.146,1.481 
                            C13.477,9.174,14.461,9,15.495,9c1.036,0,2.024,0.174,2.922,0.483C18.675,9.17,19.763,8,21.565,8c0.732,0.731,0.381,2.656,0.102,3.594 
                            c0.836,0.945,1.328,2.066,1.328,3.226c0,2.697-1.904,4.684-5.894,5.097C18.199,20.49,19,22.1,19,23.313v2.734 
                            c0,0.104-0.023,0.179-0.035,0.268C23.641,24.676,27,20.236,27,15C27,8.373,21.627,3,15,3z"/>
                    </svg>
                    <span>Continue with GitHub</span>
                </a>

                <!-- LinkedIn -->
                <a href="{{ route('auth.provider.redirect', ['provider' => 'linkedin']) }}"
                   class="flex items-center gap-3 px-4 py-3 bg-[#0A66C2] hover:bg-[#004182] rounded-xl transition font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" viewBox="0 0 48 48">
                        <path fill="#FFF" d="M12 19H17V36H12zM14.485 17h-.028C12.965 17 12 15.888 12 14.499 12 13.08 12.995 12 14.514 12c1.521 0 2.458 1.08 2.486 2.499C17 15.887 16.035 17 14.485 17zM36 36h-5v-9.099c0-2.198-1.225-3.698-3.192-3.698-1.501 0-2.313 1.012-2.709 1.992V36h-5V19h5v2.53C25.303 20.396 26.431 19 29.408 19 32.873 19 36 21.356 36 26.328V36z"/>
                    </svg>
                    <span>Continue with LinkedIn</span>
                </a>

                <!-- Slack -->
                <a href="{{ route('auth.provider.redirect', ['provider' => 'slack']) }}"
                   class="flex items-center gap-3 px-4 py-3 bg-[#4A154B] hover:bg-[#611f63] rounded-xl transition font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" viewBox="0 0 48 48">
                        <path fill="#33d375" d="M33,8c0-2.209-1.791-4-4-4s-4,1.791-4,4c0,1.254,0,9.741,0,11c0,2.209,1.791,4,4,4s4-1.791,4-4	C33,17.741,33,9.254,33,8z"></path>
                        <path fill="#33d375" d="M43,19c0,2.209-1.791,4-4,4c-1.195,0-4,0-4,0s0-2.986,0-4c0-2.209,1.791-4,4-4S43,16.791,43,19z"></path>
                        <path fill="#40c4ff" d="M8,14c-2.209,0-4,1.791-4,4s1.791,4,4,4c1.254,0,9.741,0,11,0c2.209,0,4-1.791,4-4s-1.791-4-4-4	C17.741,14,9.254,14,8,14z"></path>
                        <path fill="#40c4ff" d="M19,4c2.209,0,4,1.791,4,4c0,1.195,0,4,0,4s-2.986,0-4,0c-2.209,0-4-1.791-4-4S16.791,4,19,4z"></path>
                        <path fill="#e91e63" d="M14,39.006C14,41.212,15.791,43,18,43s4-1.788,4-3.994c0-1.252,0-9.727,0-10.984	c0-2.206-1.791-3.994-4-3.994s-4,1.788-4,3.994C14,29.279,14,37.754,14,39.006z"></path>
                        <path fill="#e91e63" d="M4,28.022c0-2.206,1.791-3.994,4-3.994c1.195,0,4,0,4,0s0,2.981,0,3.994c0,2.206-1.791,3.994-4,3.994	S4,30.228,4,28.022z"></path>
                        <path fill="#ffc107" d="M39,33c2.209,0,4-1.791,4-4s-1.791-4-4-4c-1.254,0-9.741,0-11,0c-2.209,0-4,1.791-4,4s1.791,4,4,4	C29.258,33,37.746,33,39,33z"></path>
                        <path fill="#ffc107" d="M28,43c-2.209,0-4-1.791-4-4c0-1.195,0-4,0-4s2.986,0,4,0c2.209,0,4,1.791,4,4S30.209,43,28,43z"></path>
                    </svg>
                    <span>Continue with Slack</span>
                </a>
                

                <!-- Bitbucket -->
                <a href="{{ route('auth.provider.redirect', ['provider' => 'bitbucket']) }}"
                   class="flex items-center gap-3 px-4 py-3 bg-[#205081] hover:bg-[#1b436e] rounded-xl transition font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" viewBox="0 0 64 64">
                        <path fill="#2684FF" d="M5 6a1 1 0 00-1 1l8 50a2 2 0 002 2h36a2 2 0 002-2l8-50a1 1 0 00-1-1H5zm32 39H27l-3-17h16l-3 17z"/>
                    </svg>
                    <span>Continue with Bitbucket</span>
                </a>
            </div>

            <!-- Close -->
            <div class="mt-10 text-center">
                <button @click="showModal = false"
                        class="px-5 py-2 bg-white/20 hover:bg-white/30 rounded-xl transition font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


</x-guest-layout>
