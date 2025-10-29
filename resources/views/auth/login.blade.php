<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        
            @if (Route::has('password.request'))
                <a class="text-sm text-right text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="w-full items-center justify-center mt-4 flex">
            <x-primary-button class="w-full">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    <div class="my-6 flex items-center gap-4">
            <hr class="w-full border-slate-300 dark:text-slate-900" />
            <p class="text-sm text-slate-900 dark:text-slate-300 text-center">or</p>
            <hr class="w-full border-slate-300" />
          </div>
<!-- Social Login Buttons -->
<!--Google-->
          <div class="space-x-6 flex justify-center">
            <a href="{{ route('auth.provider.redirect', ['provider' => 'google']) }}" class="group relative border-0 outline-0 cursor-pointer bg-white rounded-full p-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 512 512">
                <path fill="#fbbd00" d="M120 256c0-25.367 6.989-49.13 19.131-69.477v-86.308H52.823C18.568 144.703 0 198.922 0 256s18.568 111.297 52.823 155.785h86.308v-86.308C126.989 305.13 120 281.367 120 256z" data-original="#fbbd00" />
                <path fill="#0f9d58" d="m256 392-60 60 60 60c57.079 0 111.297-18.568 155.785-52.823v-86.216h-86.216C305.044 385.147 281.181 392 256 392z" data-original="#0f9d58" />
                <path fill="#31aa52" d="m139.131 325.477-86.308 86.308a260.085 260.085 0 0 0 22.158 25.235C123.333 485.371 187.62 512 256 512V392c-49.624 0-93.117-26.72-116.869-66.523z" data-original="#31aa52" />
                <path fill="#3c79e6" d="M512 256a258.24 258.24 0 0 0-4.192-46.377l-2.251-12.299H256v120h121.452a135.385 135.385 0 0 1-51.884 55.638l86.216 86.216a260.085 260.085 0 0 0 25.235-22.158C485.371 388.667 512 324.38 512 256z" data-original="#3c79e6" />
                <path fill="#cf2d48" d="m352.167 159.833 10.606 10.606 84.853-84.852-10.606-10.606C388.668 26.629 324.381 0 256 0l-60 60 60 60c36.326 0 70.479 14.146 96.167 39.833z" data-original="#cf2d48" />
                <path fill="#eb4132" d="M256 120V0C187.62 0 123.333 26.629 74.98 74.98a259.849 259.849 0 0 0-22.158 25.235l86.308 86.308C162.883 146.72 206.376 120 256 120z" data-original="#eb4132" />
              </svg>
              <span class = "absolute bottom mb-2 hidden group-hover:block bg-gray-600 text-white text-xs rounded py-1 px-2">Google</span>
            </a>

<!--Facebook-->
            <a href="{{ route('auth.provider.redirect', ['provider' => 'facebook']) }}" class="group relative border-0 outline-0 cursor-pointer bg-white rounded-full p-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 512 512">
                <path fill="#1877f2" d="M512 256c0 127.78-93.62 233.69-216 252.89V330h59.65L367 256h-71v-48.02c0-20.25 9.92-39.98 41.72-39.98H370v-63s-29.3-5-57.31-5c-58.47 0-96.69 35.44-96.69 99.6V256h-65v74h65v178.89C93.62 489.69 0 383.78 0 256 0 114.62 114.62 0 256 0s256 114.62 256 256z" data-original="#1877f2" />
                <path fill="#fff" d="M355.65 330 367 256h-71v-48.021c0-20.245 9.918-39.979 41.719-39.979H370v-63s-29.296-5-57.305-5C254.219 100 216 135.44 216 199.6V256h-65v74h65v178.889c13.034 2.045 26.392 3.111 40 3.111s26.966-1.066 40-3.111V330z" data-original="#ffffff" />
              </svg>
              <span class = "absolute bottom mb-2 hidden group-hover:block bg-gray-600 text-white text-xs rounded py-1 px-2">Facebook</span>
            </a>
<!--Apple-->
            <a href="{{ route('auth.provider.redirect', ['provider' => 'apple']) }}" class="group relative border-0 outline-0 cursor-pointer bg-white rounded-full p-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 22.773 22.773">
                <path d="M15.769 0h.162c.13 1.606-.483 2.806-1.228 3.675-.731.863-1.732 1.7-3.351 1.573-.108-1.583.506-2.694 1.25-3.561C13.292.879 14.557.16 15.769 0zm4.901 16.716v.045c-.455 1.378-1.104 2.559-1.896 3.655-.723.995-1.609 2.334-3.191 2.334-1.367 0-2.275-.879-3.676-.903-1.482-.024-2.297.735-3.652.926h-.462c-.995-.144-1.798-.932-2.383-1.642-1.725-2.098-3.058-4.808-3.306-8.276v-1.019c.105-2.482 1.311-4.5 2.914-5.478.846-.52 2.009-.963 3.304-.765.555.086 1.122.276 1.619.464.471.181 1.06.502 1.618.485.378-.011.754-.208 1.135-.347 1.116-.403 2.21-.865 3.652-.648 1.733.262 2.963 1.032 3.723 2.22-1.466.933-2.625 2.339-2.427 4.74.176 2.181 1.444 3.457 3.028 4.209z" data-original="#000000"></path>
              </svg>
              <span class = "absolute bottom mb-2 hidden group-hover:block bg-gray-600 text-white text-xs rounded py-1 px-2">Apple</span>
            </a>
<!--Github-->
            <a href="{{ route('auth.provider.redirect', ['provider' => 'github']) }}" class="group relative border-0 outline-0 cursor-pointer bg-white rounded-full p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 30 30">
                    <path d="M15,3C8.373,3,3,8.373,3,15c0,5.623,3.872,10.328,9.092,11.63C12.036,26.468,12,26.28,12,26.047v-2.051 c-0.487,0-1.303,0-1.508,0c-0.821,0-1.551-0.353-1.905-1.009c-0.393-0.729-0.461-1.844-1.435-2.526 c-0.289-0.227-0.069-0.486,0.264-0.451c0.615,0.174,1.125,0.596,1.605,1.222c0.478,0.627,0.703,0.769,1.596,0.769 c0.433,0,1.081-0.025,1.691-0.121c0.328-0.833,0.895-1.6,1.588-1.962c-3.996-0.411-5.903-2.399-5.903-5.098 c0-1.162,0.495-2.286,1.336-3.233C9.053,10.647,8.706,8.73,9.435,8c1.798,0,2.885,1.166,3.146,1.481C13.477,9.174,14.461,9,15.495,9 c1.036,0,2.024,0.174,2.922,0.483C18.675,9.17,19.763,8,21.565,8c0.732,0.731,0.381,2.656,0.102,3.594 c0.836,0.945,1.328,2.066,1.328,3.226c0,2.697-1.904,4.684-5.894,5.097C18.199,20.49,19,22.1,19,23.313v2.734 c0,0.104-0.023,0.179-0.035,0.268C23.641,24.676,27,20.236,27,15C27,8.373,21.627,3,15,3z"></path>
                </svg>
              <span class = "absolute bottom mb-2 hidden group-hover:block bg-gray-600 text-white text-xs rounded py-1 px-2">Github</span>
            </a>
          </div>
        </form>
<!--Other Providers Modal-->       
        <div x-data="{ showModal: false }"class="text-center">
        <button @click="showModal = true" class="underline center text-sm text-slate-300 hover:text-indigo-800">
            {{ __('Other ways to Login') }}
        </button>
        <div x-show = "showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
          <div class="bg-white dark:bg-slate-900 rounded-lg p-6 w-96">
            <h2 class="text-slate-800 dark:text-slate-300 font-semibold mb-4">Sign up using</h2>
            <div class = "space-y-3">
<!--GitLab-->
            <a href="{{ route('auth.provider.redirect', ['provider' => 'gitlab']) }}" class="w-full flex items-center justify-center gap-4 py-2.5 px-6 text-[15px] font-medium tracking-wide text-slate-900 dark:text-slate-300 border border-slate-300 rounded-md bg-slate-50 dark:bg-slate-900 hover:dark:bg-slate-800 hover:bg-slate-100 focus:outline-none cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg" width="25px" viewBox="0 0 64 64">
                    <path fill="#e53935" d="M24 43L16 20 32 20z"></path>
                    <path fill="#ff7043" d="M24 43L42 20 32 20z"></path>
                    <path fill="#e53935" d="M37 5L42 20 32 20z"></path>
                    <path fill="#ffa726" d="M24 43L42 20 45 28z"></path>
                    <path fill="#ff7043" d="M24 43L6 20 16 20z"></path>
                    <path fill="#e53935" d="M11 5L6 20 16 20z"></path>
                    <path fill="#ffa726" d="M24 43L6 20 3 28z"></path>
                </svg>
              Login with GitLab
            </a>
<!--X-->
            <a href="{{ route('auth.provider.redirect', ['provider' => 'x']) }}" class="w-full flex items-center justify-center gap-4 py-2.5 px-6 text-[15px] font-medium tracking-wide text-slate-900 dark:text-slate-300 border border-slate-300 rounded-md bg-slate-50 dark:bg-slate-900 hover:dark:bg-slate-800 hover:bg-slate-100 focus:outline-none cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" class="inline-block align-middle">
                    <rect width="24" height="24" rx="4" fill="#000"/>
                    <path fill="#fff" d="M16.5 7.5l-4.5 4.5 4.5 4.5h-2l-3.5-3.5-3.5 3.5h-2l4.5-4.5-4.5-4.5h2l3.5 3.5 3.5-3.5h2z"/>
                </svg>
              Login with X
            </a>
<!--LinkedIn-->
            <a href="{{ route('auth.provider.redirect', ['provider' => 'linkedin']) }}" class="w-full flex items-center justify-center gap-4 py-2.5 px-6 text-[15px] font-medium tracking-wide text-slate-900 dark:text-slate-300 border border-slate-300 rounded-md bg-slate-50 dark:bg-slate-900 hover:dark:bg-slate-800 hover:bg-slate-100 focus:outline-none cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" viewBox="0 0 48 48">
                    <path fill="#0288D1" d="M42,37c0,2.762-2.238,5-5,5H11c-2.761,0-5-2.238-5-5V11c0-2.762,2.239-5,5-5h26c2.762,0,5,2.238,5,5V37z"></path>
                    <path fill="#FFF" d="M12 19H17V36H12zM14.485 17h-.028C12.965 17 12 15.888 12 14.499 12 13.08 12.995 12 14.514 12c1.521 0 2.458 1.08 2.486 2.499C17 15.887 16.035 17 14.485 17zM36 36h-5v-9.099c0-2.198-1.225-3.698-3.192-3.698-1.501 0-2.313 1.012-2.707 1.99C24.957 25.543 25 26.511 25 27v9h-5V19h5v2.616C25.721 20.5 26.85 19 29.738 19c3.578 0 6.261 2.25 6.261 7.274L36 36 36 36z"></path>
                </svg>
              Login with LinkedIn
            </a>
<!--BitBucket-->
            <a href="{{ route('auth.provider.redirect', ['provider' => 'bitbucket']) }}" class="w-full flex items-center justify-center gap-4 py-2.5 px-6 text-[15px] font-medium tracking-wide text-slate-900 dark:text-slate-300 border border-slate-300 rounded-md bg-slate-50 dark:bg-slate-900 hover:dark:bg-slate-800 hover:bg-slate-100 focus:outline-none cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" viewBox="0 0 24 24" fill="none">
                    <path fill="#2684FF" d="M3.06 3.06a.75.75 0 0 1 .53-.22h16.82a.75.75 0 0 1 .74.86l-2.1 13.5a.75.75 0 0 1-.74.64H6.49a.75.75 0 0 1-.74-.64L3.06 3.84a.75.75 0 0 1 .22-.78z"/>
                    <path fill="#0052CC" d="M15.5 13.5h-7l-.5-3h8z"/>
                </svg>
              Login with BitBucket
            </a>
<!--Slack-->
            <a href="{{ route('auth.provider.redirect', ['provider' => 'slack']) }}" class="w-full flex items-center justify-center gap-4 py-2.5 px-6 text-[15px] font-medium tracking-wide text-slate-900 dark:text-slate-300 border border-slate-300 rounded-md bg-slate-50 dark:bg-slate-900 hover:dark:bg-slate-800 hover:bg-slate-100 focus:outline-none cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" viewBox="0 0 48 48">
                    <path fill="#33d375" d="M33,8c0-2.209-1.791-4-4-4s-4,1.791-4,4c0,1.254,0,9.741,0,11c0,2.209,1.791,4,4,4s4-1.791,4-4	C33,17.741,33,9.254,33,8z"></path>
                    <path fill="#33d375" d="M43,19c0,2.209-1.791,4-4,4c-1.195,0-4,0-4,0s0-2.986,0-4c0-2.209,1.791-4,4-4S43,16.791,43,19z"></path>
                    <path fill="#40c4ff" d="M8,14c-2.209,0-4,1.791-4,4s1.791,4,4,4c1.254,0,9.741,0,11,0c2.209,0,4-1.791,4-4s-1.791-4-4-4	C17.741,14,9.254,14,8,14z"></path>
                    <path fill="#40c4ff" d="M19,4c2.209,0,4,1.791,4,4c0,1.195,0,4,0,4s-2.986,0-4,0c-2.209,0-4-1.791-4-4S16.791,4,19,4z"></path>
                    <path fill="#e91e63" d="M14,39.006C14,41.212,15.791,43,18,43s4-1.788,4-3.994c0-1.252,0-9.727,0-10.984	c0-2.206-1.791-3.994-4-3.994s-4,1.788-4,3.994C14,29.279,14,37.754,14,39.006z"></path>
                    <path fill="#e91e63" d="M4,28.022c0-2.206,1.791-3.994,4-3.994c1.195,0,4,0,4,0s0,2.981,0,3.994c0,2.206-1.791,3.994-4,3.994	S4,30.228,4,28.022z"></path>
                    <path fill="#ffc107" d="M39,33c2.209,0,4-1.791,4-4s-1.791-4-4-4c-1.254,0-9.741,0-11,0c-2.209,0-4,1.791-4,4s1.791,4,4,4	C29.258,33,37.746,33,39,33z"></path>
                    <path fill="#ffc107" d="M28,43c-2.209,0-4-1.791-4-4c0-1.195,0-4,0-4s2.986,0,4,0c2.209,0,4,1.791,4,4S30.209,43,28,43z"></path>
                </svg>
              Login with Slack
            </a>
            </div>
            <button @click = "showModal = false" class="w-full mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
              Close
            </button>
            </div>
          </div>    
        </div>
      </div>
    </div>
</x-guest-layout>
