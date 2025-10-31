<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Terms of Service') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ open: null, confirmDelete: false }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                
                <!-- Header -->
                <section class="space-y-2 border-b border-gray-200 dark:border-gray-700 pb-4">
                    <p><strong>Effective Date:</strong> 31 October 2025</p>
                    <p><strong>App Name:</strong> SSO-Login</p>
                    <p><strong>Developer Contact:</strong> <a href="mailto:adlinazamzuri@gmail.com" class="text-blue-500 hover:underline">adlinazamzuri@gmail.com</a></p>
                    <p>This application is a <strong>non-commercial demo</strong> built for testing third-party login flows using OAuth providers such as X (formerly Twitter), GitHub, GitLab, Google, and others. 
                        By using this app, you agree to the following terms:
                    </p>
                </section>

                <!-- Collapsible Cards -->
                <div class="mt-6 space-y-3">
                    @foreach ([
                        'Purpose of Use' => '
                            <p>SSO-Login is intended solely for development and testing purposes. 
                                It is not a production-grade service and does not offer commercial features or guarantees.</p>',
                        'User Authentication' => '
                            <p>When you log in using a third-party provider, we only collect the minimum information necessary to:</p>
                            <ul class="list-disc ml-6 space-y-1">
                                <li>Authenticate your identity</li>
                                <li>Match or create a user record in our test database</li>
                            </ul>
                            <p class="mt-2">Email is required to ensure unique user identification and prevent duplicate accounts.</p>',
                        'Data Usage' => '
                            <p>We do not use your data for advertising, analytics, or resale. All data is handled securely and only used for authentication and display within the app interface.</p>',
                        'Data Retention' => '
                            <p>User data is retained only for the duration of testing. You may request deletion of your data by contacting the developer.</p>',
                        'No warranty' => '
                            <p>This app is provided “as is” without warranty of any kind. We do not guarantee uptime, data integrity, or feature completeness.</p>',
                        'Changes of Terms' => '
                            <p>These terms may be updated as the app evolves. Any changes will be posted on this page with an updated effective date</p>',
                        
                    ] as $title => $content)
                        <div x-data="{ expanded: false }" class="border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900/40 hover:shadow transition-all">
                            <button @click="expanded = !expanded"
                                class="w-full flex justify-between items-center px-5 py-4 font-medium text-left text-gray-800 dark:text-gray-100">
                                <span>{{ $title }}</span>
                                <svg :class="{ 'rotate-180': expanded }"
                                    class="w-5 h-5 text-gray-500 transform transition-transform duration-200"
                                    fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="expanded" x-collapse class="px-5 pb-4 text-sm leading-relaxed">
                                {!! $content !!}
                            </div>
                        </div>
                    @endforeach
                </div>

                
            </div>
        </div>
    </div>
</x-app-layout>
