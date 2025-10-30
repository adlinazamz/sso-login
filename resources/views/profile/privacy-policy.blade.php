<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Privacy Policy') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ open: null, confirmDelete: false }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                
                <!-- Header -->
                <section class="space-y-2 border-b border-gray-200 dark:border-gray-700 pb-4">
                    <p><strong>Effective Date:</strong> 30 October 2025</p>
                    <p><strong>App Name:</strong> SSO-Login</p>
                    <p><strong>Developer Contact:</strong> <a href="mailto:adlinazamzuri@gmail.com" class="text-blue-500 hover:underline">adlinazamzuri@gmail.com</a></p>
                    <p>
                        This policy explains how <strong>SSO-Login</strong> handles user data during authentication 
                        via third-party providers (Google, Facebook, GitHub, Slack, etc.). 
                        This app is intended primarily for internal testing and development purposes.
                    </p>
                </section>

                <!-- Collapsible Cards -->
                <div class="mt-6 space-y-3">
                    @foreach ([
                        'Information We Collect' => '
                            <ul class="list-disc ml-6 space-y-1">
                                <li>Basic profile information (name, email, profile picture)</li>
                                <li>Authentication tokens necessary for login</li>
                            </ul>
                            <p class="mt-2">We collect only data required for authentication.</p>',
                        'How We Use Your Information' => '
                            <ul class="list-disc ml-6 space-y-1">
                                <li>Authenticate your identity securely via OAuth</li>
                                <li>Create or link your account in our system</li>
                                <li>Display your basic profile information in the dashboard</li>
                            </ul>
                            <p class="mt-2">We <strong>never</strong> use your information for marketing or advertising.</p>',
                        'Data Sharing' => '
                            <p>We do not share your personal data with any third party except as necessary for authentication with the chosen provider. We never sell or rent your data.</p>',
                        'Data Storage & Security' => '
                            <p>Your data is stored securely with encryption and access control. 
                            While we apply industry-standard measures, no online system is 100% immune from security risks.</p>',
                        'Data Retention & Deletion' => '
                            <p>Data is retained only as long as necessary to provide authentication services. 
                            You may delete your account at any time through your profile settings or by contacting us directly.</p>',
                        'Cookies & Tracking' => '
                            <p>We do not use cookies or tracking technologies. Authentication relies exclusively on secure OAuth tokens.</p>',
                        'Provider Compliance' => '
    <p>All integrated providers comply with international privacy and data protection standards.</p>
    <ul class="mt-4 space-y-2 ml-6 list-disc">
        @foreach([
            "Facebook" => "https://www.facebook.com/about/privacy/",
            "Google" => "https://policies.google.com/privacy",
            "GitHub" => "https://docs.github.com/en/site-policy/privacy-policies/github-privacy-statement",
            "Slack" => "https://slack.com/privacy-policy",
            "LinkedIn" => "https://www.linkedin.com/legal/privacy-policy",
            "GitLab" => "https://about.gitlab.com/privacy/",
            "X (Twitter)" => "https://help.twitter.com/en/rules-and-policies/twitter-privacy-policy",
            "Bitbucket" => "https://www.atlassian.com/legal/privacy-policy",
            "Apple" => "https://www.apple.com/legal/privacy/en-ww/"
        ] as $provider => $url)
            <li>
                <strong>{{ $provider }}:</strong>
                <a href="{{ $url }}" class="text-blue-500 underline hover:text-blue-600 break-words">{{ $url }}</a>
            </li>
        @endforeach
    </ul>
    <p class="mt-3">Only essential permissions are requested — no extra data is accessed or stored.</p>',
                        'Policy Updates' => '
                            <p>This policy may be updated from time to time. All updates will appear within the app, and the effective date will reflect the latest revision.</p>'
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

                <!-- Data Deletion Modal Trigger -->
                <div class="pt-8 text-center">
                    <button @click="confirmDelete = true"
                        class="px-4 py-2 text-sm font-semibold text-red-500 border border-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 transition">
                        Request Account & Data Deletion
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="confirmDelete" x-cloak
             class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50">
            <div @click.away="confirmDelete = false"
                 class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xl max-w-md w-full text-center space-y-4 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Request Data Deletion</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    To permanently delete your account and data, go to 
                    <a href="{{ route('profile.edit') }}" class="text-blue-500 underline hover:text-blue-600">Profile Settings</a> 
                    and use the built-in delete option, or contact 
                    <a href="mailto:adlinazamzuri@gmail.com" class="text-blue-500 underline hover:text-blue-600">adlinazamzuri@gmail.com</a>.
                </p>
                <button @click="confirmDelete = false"
                        class="mt-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
