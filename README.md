# Third-Party OAuth Providers — Setup & Integration

> **Purpose:** This document explains, step-by-step, how to obtain and configure OAuth client credentials for common third-party providers (Google, Facebook, X, Apple). It also shows how to wire them into a Laravel backend (Socialite) and how to use them from a Flutter client. Screenshots and a `cacert.pem` guidance section are included.

---

## Table of contents

1. [Overview](#overview)
1. [Prerequisites](#prerequisites)
1. [Common concepts](#common-concepts)
1. [Provider setup instructions (per provider)](#provider-setup)
   * [Google](#google)
   * [Facebook / Meta](#facebook--meta)
   * [X](#x-twitter)
   * [WIP: Apple Sign in with Apple](#sign-in-with-apple)
   * [WIP: Microsoft Entra ID](#microsoft-entra-id)
1. [Testing tips](#testing-tips)
1. [Troubleshoot](#troubleshoot)
   * [cURL Error 60](#curl-error-60)
   * [Meta Ineligible Submission Warning](#facebook-currently-ineligible-for-submission-warning)
1. [Appendix — sample `.env` variables & code snippets](#appendix)

---

## Overview

This README documents the exact steps QA/Dev/DevOps or other engineers should follow to create OAuth client credentials on 3rd party provider consoles and plug them into our Laravel backend (using Socialite) to authenticate users via third-party providers. 

---

## Prerequisites

* Access to the provider developer console (Google Cloud, Meta for Developers, GitHub Settings, GitLab, Slack, Bitbucket, LinkedIn, Apple).
* A Laravel app with `laravel/socialite` installed.
* A Flutter app capable of receiving a redirect (deep link / custom scheme or universal link).
* A registered redirect URI that points to your backend endpoint: `https://<YOUR_DOMAIN>/auth/{provider}/callback`
* (Optional) A `cacert.pem` bundle if your environment needs an explicit CA bundle for Guzzle.

---

## Common concepts

* **Client ID / Client Secret**: credentials issued by the provider.
* **Redirect URI**: URL provider will redirect to after user authorizes.
* **Scopes**: what data you request (`email`, `profile`, etc.).
* **State**: anti-CSRF token for OAuth flows.

---

## Provider setup

Below are practical step-by-step instructions. Screenshots relevant to the step can be reviewed in readme_references folder.

### Google

This section walks through creating Google OAuth credentials for your Laravel Socialite integration.  
Follow the steps carefully — each includes screenshots for references.

---

#### Quick Summary 

1. Create a Google Cloud project (for managing OAuth credentials)
1. Configure the OAuth consent screen (set app name, audience, and contact info)
1. Create OAuth Client ID (choose Web App, set redirect URIs)
1. Copy Client ID & Secret → add to `.env` for Laravel Socialite
1. If you hit **cURL error 60**, see [Troubleshoot — cURL Error 60](#curl-error-60)

---

#### Walkthrough

##### Step 1: Log in to Google Cloud Console

1. Go to [Google Cloud Console](https://console.cloud.google.com/).
   - Sign in with your Google account.
   - You’ll land on the Google Cloud dashboard.
1. After signing in, confirm that you can see the project dropdown on the top bar.  
   ![Google Cloud Dashboard](readme_references/google/step_1/01-google_cloud_dashboard.png)  
   *Main Google Cloud dashboard view.*

---

##### Step 2: Create a New Project

1. Click the project dropdown on the top bar.  
   ![New Project Dropdown](readme_references/google/step_2/01-project_dropdown.png)  
   *Open project dropdown on top bar.*
1. Select **New Project**.  
   ![New Project Button](readme_references/google/step_2/02-new_project.png)  
   *Create new project.*
1. Enter project details (e.g., `user-auth-sso`).  
   *If there is an organization associated with the project, change according to the associated organization folder.*  
   ![New Project Detail Form](readme_references/google/step_2/03-project_detail_form.png)  
   *Create new project.*
1. Click **Create**.  
   ![New Project Confirmation](readme_references/google/step_2/04-project_created.png)  
   *Project created.*

> 💡 *Note: It may take a few seconds for the new project to be created.*

---

##### Step 3: Configure OAuth Consent Screen

1. Open the newly created project.  
   ![Open Project](readme_references/google/step_3/01-select_created_project.png)  
   *Open the user-auth-sso.*
1. In the left sidebar, go to **APIs & Services → OAuth consent screen**.  
   ![OAuth Consent](readme_references/google/step_3/02-navigate_Oauth_consent.png)  
   *Navigate to OAuth consent in the sidebar.*
1. You'll be directed to the OAuth Overview. Click **Get Started**.  
   ![OAuth Overview](readme_references/google/step_3/03-oauth_overview_config.png)  
   *OAuth consent overview.*
1. Fill out the project configuration:  
   - App name (recommended: same as project name)  
   - User support email (developer associated with the project)  
     ![OAuth App Information](readme_references/google/step_3/04-app_information.png)  
     *App information form.*  
   - Audience: Choose based on app purpose:  
     **Internal** (within organization) or **External** (any Google account)  
     ![OAuth Audience](readme_references/google/step_3/04-audience_type.png)  
     *Select audience type based on app usage.*  
   - Contact Information: developer email for notifications.
1. Finish setup, agree to the Google API Services: User Data Policy, and click **Save and Continue**.  
   Then click **Create**.  
   ![Consent Screen Setup](readme_references/google/step_3/05-agree_google_api_service.png)  
   *Filling in OAuth consent screen details.*
1. Once created, you'll be directed to the OAuth Overview.  
   ![Successful Consent Screen Setup](readme_references/google/step_3/06-successful_OAuth_consent.png)

---

##### Step 4: Create OAuth Client ID

1. Click **Create OAuth client**.  
   ![Create OAuth Client](readme_references/google/step_4/01-create_client_OAuth.png)
1. Choose the application type — in this case, **Web Application**.  
   ![OAuth Client Application](readme_references/google/step_4/02-choosing_application_type.png)
1. Fill out:
   - Name (e.g. `user-auth-sso`)
   - Authorized JavaScript origins (optional)
   - Authorized redirect URIs:
     ```
     http://localhost:8000/auth/google/callback
     https://localhost:8000/auth/google/callback
     http://yourdomain.com/auth/google/callback
     https://yourdomain.com/auth/google/callback
     ```
   **Note:** Google OAuth doesn’t accept `.test` domains — only real TLDs are allowed.
1. Click **Create**.

> The creation process for the OAuth client may take anywhere from a few minutes to a few hours.

---

##### Step 5: Copy Your Client Credentials

1. A dialog will appear with your **Client ID** and **Client Secret**.  
   *Sensitive values (Client ID and Client Secret) have been redacted for security.*
   - 🔴 **Client ID** — shown in the red box.  
     ![Client ID](readme_references/google/step_5/01-generated_client_access_id.png)
   - 🟢 **Client Secret** — shown in the green box. Only visible once.  
     ![Client Secret](readme_references/google/step_5/01-generated_client_secret_key.png)
   > *Make sure both credentials are stored securely before clicking "OK".*
1. Copy both and store them safely.
1. Add them to your `.env` file:
   ```env
   GOOGLE_CLIENT_ID=your-client-id
   GOOGLE_CLIENT_SECRET=your-client-secret
   GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback
   ```

>⚠️ If you encounter **cURL error 60: SSL certificate problem**, your system may be missing a trusted CA bundle.  
>See [Troubleshoot — cURL Error 60](#curl-error-60) for guidance on resolving this with `cacert.pem`.


[↑ Back to top](#table-of-contents) • [↑ Back to provider](#google)

---

### Facebook (Meta)

This section walks through creating Facebook (Meta) OAuth credentials for your Laravel Socialite integration.  
Follow the steps carefully — each includes screenshots for references.

>This setup is intended for testing purposes only. If you're **publishing the app**, please follow the **App customization and requirements checklist in the Dashboard** of your newly created application.

---

#### Quick Summary 

1. Create a Facebook App via [Facebook for Developers](https://developers.facebook.com/)
2. Add the **Facebook Login** product and select the appropriate use case
3. Customize the use case and add required permissions (e.g., `email`)
4. Test the login flow using Graph API Explorer (optional)
5. Copy App ID & Secret → add to `.env`
6. If you hit **cURL error 60**, see [Troubleshoot — cURL Error 60](#troubleshoot-curl-error-60)

---
#### Walkthrough

##### Step 1: Log in to Meta Business
1. Go to [Meta Developers](https://developers.facebook.com).
   - Sign in with *Facebook*
   ![facebook Developers Login](readme_references/facebook/step_1/01-developers_facebook.png)
   *Facebook Developers login page.*

>*If logged in through Facebook, code will be sent to your facebook account email*

##### Step 2: Create a New App

1. Go to [Facebook for Developers → My Apps](https://developers.facebook.com/apps/)

2. Click **Create App**

3. Fill in:
   **App details**
   - App Name (e.g. `user-auth-sso`)
   - Contact Email
   **Use case**
   - Select the respective Use case for the API (since this repo is for sso authentication, select **Authenticate and request data from users with Facebook login**)
   ![App Use Case](readme_references/facebook/step_2/03-Meta_use_case.png)  
   *Fill in app details.*
   **Business**
   - Business Account (optional)  
   **Requirements**
   This are for reference on the steps required for the use case application.
   -Click **Next**

4. Make sure the overview meet with what the application is about
   ![App Setup Overview](readme_references/facebook/step_2/04-Meta_setup_overview.png)  
   *Check the application setup.*

5. Click **Go to Dashboard**
>This will take some time to create the new application
   ![App dashboard](readme_references/facebook/step_2/05-New_app_dashboard.png)  
   *Application dashboard.*
---
> **Optional:** Steps 3 and 4 are only needed if you're customizing the use case or testing the API manually.

##### Step 3: Setting up the application (Optional)

1. Following the app customization and requirement, navigate to **Customize the Authenticate and request data from users with Facebook Login use case**
![Navigate Use Case](readme_references/facebook/step_3/01-use_case_config.png)  
   *Navigate to Use Case config.*

2. Customize the use case according to the app login flow. *Since the repo pass email on login authenticate, add **email**.* 
   ![Custom Use Case](readme_references/facebook/step_3/02-custom_use_case.png)  
   *Customize use case: Adding email*

3. Navigate back to the **Dashboard** and the **Customize the Authenticate and request data from users with Facebook Login use case** are now green.

---
>**Optional:** Steps 3 and 4 are only needed if you're customizing the use case or testing the API manually.
##### Step 4: Application API testing (optional)

1. Just like in Step 3.1, navigate to **dashboard** and click **Review and complete testing requirement**
![App API Test](readme_references/facebook/step_4/01-app_api_testing.png)
The dropdown below show the current active use case enabled for the Facebook API.

2. Click **Open Graph API explorer** to test the application API.
   - Change the setup according to the need:
      - Meta App: Select the project  (eg: user-auth-sso)
      - User or Page: Select **Get User Access Token** since we are testing if it's able to read and get the corresponding data related to the user.
      -Add a permission: Select **email** since we're attempting to check if its able to read the email associated to the facebook account
   - Click **Generate Access Token**
   >*On generate token, Facebook modal popup will ask for permission to log and request access to **Name and profile picture** and  **Email address**. Select **Continue** to proceed with token generation*
   - In the form field, add **email** field
   ```
   me?fields=id,name, email
   ```
   - Click **Submit**

3. On successful API call, it will show:
![Successful API test](readme_references/facebook/step_4/03-successful_api_test.png)
*Successful API test*
---

##### Step 5: Get App Credentials

1. Go to **Settings → Basic**
![Navigate Settings Basic ](readme_references/facebook/step_5/01-navigate_app_settings_basic.png)
*Navigate to App Settings*
2. Copy your **App ID** and **App Secret**
*Sensitive values (Client ID and Client Secret) have been redacted for security.*

   - 🔴 **Client ID** (highlighted in red box)
   - 🟢 **Client Secret** (highlighted in green box)

   ![App Credentials](readme_references/facebook/step_5/02-client_id_secret.png)  
   *Client ID and Client Secret key.*

3. Add them to your `.env` file:

   ```env
   FACEBOOK_CLIENT_ID=your-app-id
   FACEBOOK_CLIENT_SECRET=your-app-secret
   FACEBOOK_REDIRECT_URI=${APP_URL}/auth/facebook/callback
   ```

> ⚠️ If you see a warning about **Currently Ineligible for Submission**, this is expected for internal/test apps.  
> See [Troubleshoot — Meta Ineligible Submission Warning](#facebook-currently-ineligible-for-submission-warning) for guidance on safely ignoring this warning.

> ⚠️ If you encounter **cURL error 60: SSL certificate problem**, your system may be missing a trusted CA bundle.  
> See [Troubleshoot — cURL Error 60](#curl-error-60) for guidance on resolving this with `cacert.pem`.


[↑ Back to top](#table-of-contents) • [↑ Back to provider](#facebook--meta)


---

### X (Twitter)

This section walks through creating X (Twitter) OAuth credentials for your Laravel Socialite integration.  
Follow the steps carefully — each includes screenshots for reference.

> This setup is intended for testing purposes only. If you're publishing the app, ensure your X Developer App is fully reviewed and approved for production access.
>Please note that on the free tier, X only allows a single OAuth app quota per account.
---

#### Quick Summary

1. Create a X Developer App via [Twitter Developer Portal](https://developer.x.com/en)
2. Set up project and app details (name, use case, description)
3. Enable 3-legged OAuth and set callback URL
4. Add required permissions (e.g., `email`) and app metadata (website, privacy policy)
5. Copy API Key & Secret → add to `.env`
6. If you hit **cURL error 60**, see [Troubleshoot — cURL Error 60](#troubleshoot-curl-error-60)

---

#### Walkthrough

##### Step 1: Create a X Developer App

1. Go to [X Developer Portal](https://developer.x.com/en)
![X Developer Portal](readme_references/x/step_1/01-twitter_dashboard.png)
*X developer portal*

2. Log in with your X (Twitter) account and select  **Developer Portal**. You'll land on the Developer portal dashboard.
3. Click **+ Create Project**
![X Create project](readme_references/x/step_1/02-x_developer_dashboard.png)
*X project fillable*
4. Setup the new project 
![X Project Setup](readme_references/x/step_1/03-new_project_setup.png)
*X project setup*

Fill in:
**Describe new Project**
   - Project Name (e.g. `User Twitter SSO`)
   - Fill in the use case based on the need of the app creation from the dropdown
   - Project Description
**Name your App**
   - App Name (e.g. `user-auth-sso`)

4. Click **Next** and  immediately directed to the **Keys & Tokens**

5. Store the **App ID** and **App Secret Key**

> The value is shown only once. Please store it securely before clicking **App Setting**. Else, the Secret Key will needed to be regenerated.
---

##### Step 2: Enable 3-legged OAuth and Set Callback URL

   1. In your app settings, go to **User authentication settings**
   ![X Project Setup](readme_references/x/step_2/01-app_setting.png)
   *X project setup*
   2. Enable **OAuth 1.0a** and setup based on the application need:
      - App permission: Read 
      `Since this is for SSO authentication, **enable email permission** as it's required by this login flow.`
      - App Type
      
   3. Set App info:
      - **Callback URI / Redirect URL**:  
      ```
      https://yourdomain.com/auth/twitter/callback
      http://yourdomain.com/auth/twitter/callback
      https://yourdomain.test/auth/twitter/callback
      http://yourdomain.test/auth/twitter/callback
      https://localhost/auth/twitter/callback
      http://localhost/auth/twitter/callback
      ```
      - **Website URL**: Your app or company site (can be placeholder for testing)
      *As we enabled the email permission, a valid Website URL is required. Use a real or placeholder domain (e.g. https://example.com)  localhost URLs are not accepted.*
      - **Terms of Service** (can use placeholder example.com)
      - **Privacy Policy** (can use placeholder example.com)

   4. Save the authenticated setting for the app

---

##### Step 3: Get API Key and Secret

1. Add the **Client ID** and **Client Secret** to your `.env` file:
   ```env
   TWITTER_CLIENT_ID=your-api-key
   TWITTER_CLIENT_SECRET=your-api-secret
   TWITTER_REDIRECT_URI=${APP_URL}/auth/twitter/callback
   ```

*If forgot the **Client ID** and **Client Secret**, skip to step 3.2*

2. If the **Client ID** and **Client Secret** isn't saved before:
   - Go to **Keys and Tokens** tab
   ![X App Key Token](readme_references/x/step_3/01-app_keys_tokens.png)
   *X app key token*
   - Scroll down to **Oauth 2.0 Client ID and Client Secret**
   - Copy the **Client ID** and regenerate **Client Secret**
   - Store **Client ID** and new **Client Secret**
   - Redo step 3.1 

> Regenerating **Client Secret** will invalidate the old **Client Secret**. Note: Update the **previous Client Secret** to the **newly generated Client Secret**
---

> ⚠️ If you encounter **cURL error 60: SSL certificate problem**, your system may be missing a trusted CA bundle.  
> See [Troubleshoot — cURL Error 60](#curl-error-60)for guidance on resolving this with `cacert.pem`.

[↑ Back to top](#table-of-contents) • [↑ Back to provider](#x-twitter)


---

### Sign in with Apple

This section walks through creating **Sign in with Apple** OAuth credentials for your Laravel Socialite integration.  
Follow the steps carefully — each includes screenshots for reference.

> This setup is intended for testing purposes only. If you're publishing the app, ensure your Apple Developer account is active and your app is properly configured for production.

---

#### Quick Summary

1. Create an App ID and Service ID via [Apple Developer Portal](https://developer.apple.com/account/)
2. Enable Sign in with Apple and configure redirect URI
3. Generate and download a private key
4. Copy credentials → add to `.env`
5. Configure Laravel Socialite and event listener
6. If you hit **cURL error 60**, see [Troubleshoot — cURL Error 60](#troubleshoot-curl-error-60)

---

#### Walkthrough

##### Step 1: Log in to Apple Developer Portal

1. Go to [Apple Developer Portal](https://developer.apple.com/account/)
2. Sign in with your Apple ID
3. Navigate to **Certificates, Identifiers & Profiles**

---

##### Step 2: Create App ID and Service ID

1. Under **Identifiers**, click **+** to create a new App ID
2. Choose **App IDs → App**
3. Fill in:
   - Name (e.g. `user-auth-sso`)
   - Bundle ID (reverse domain format, e.g. `com.example.userauth`)
4. Enable **Sign in with Apple** capability

5. Then, create a **Service ID**:
   - Name (e.g. `user-auth-sso-service`)
   - Identifier (e.g. `com.example.userauth.service`)
   - Enable **Sign in with Apple**
   - Set the **Redirect URI**:
     ```
     https://yourdomain.com/auth/apple/callback
     ```

---

##### Step 3: Generate Private Key

1. Go to **Keys** → click **+**
2. Name the key (e.g. `Apple SSO Key`)
3. Enable **Sign in with Apple**
4. Select the previously created App ID
5. Click **Continue** and **Download** the `.p8` private key file

> ⚠️ This file is only downloadable once. Store it securely.

---

##### Step 4: Add Credentials to `.env`

```env
APPLE_CLIENT_ID=your-service-id
APPLE_TEAM_ID=your-team-id
APPLE_KEY_ID=your-key-id
APPLE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----"
APPLE_REDIRECT_URI=${APP_URL}/auth/apple/callback
```

---

##### Step 5: Configure Laravel Socialite

1. In `config/services.php`:

```php
'apple' => [
    'client_id' => env('APPLE_CLIENT_ID'),
    'team_id' => env('APPLE_TEAM_ID'),
    'key_id' => env('APPLE_KEY_ID'),
    'private_key' => env('APPLE_PRIVATE_KEY'),
    'redirect' => env('APPLE_REDIRECT_URI'),
],
```

2. If using [SocialiteProviders/Apple](https://socialiteproviders.com/apple/), register the provider in `EventServiceProvider.php`:

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        \SocialiteProviders\Apple\AppleExtendSocialite::class.'@handle',
    ],
];
```

---

> ⚠️ If you encounter **cURL error 60: SSL certificate problem**, your system may be missing a trusted CA bundle.  
> See [Troubleshoot — cURL Error 60](#curl-error-60)for guidance on resolving this with `cacert.pem`.

[↑ Back to top](#table-of-contents) • [↑ Back to provider](#sign-in-with-apple)

---
### Microsoft Entra ID

This section walks through creating **Microsoft Entra ID** OAuth credentials for your Laravel Socialite integration.  
Follow the steps carefully — each includes screenshots for reference.

> ⚠️ This setup is intended for testing purposes only. Ensure you have a Microsoft Azure Developer account enabled.

---

#### Prerequisites

- A Microsoft Azure account  
- Access to the correct tenant  
- Laravel Socialite installed and configured  

---

#### Quick Summary

1. Register application via Microsoft Entra ID in the [Azure portal](https://portal.azure.com/)  
2. Enable Microsoft identity platform and configure redirect URI  
3. Generate and download a client secret  
4. Copy credentials → add to `.env`  
5. Configure Laravel Socialite and event listener  
6. If you hit **cURL error 60**, see [Troubleshoot — cURL Error 60](#troubleshoot-curl-error-60)  

---

#### Walkthrough

##### Step 1: Log in to Microsoft Entra ID

1. Go to [Azure Portal](https://portal.azure.com/)  
2. Sign in with your Microsoft account  
3. Navigate to **Microsoft Entra ID**  

---

##### Step 2: Register Application

1. In the left sidebar, select **Overview**  
2. Click **Add → App registration**  
3. Fill in:
   - **Name** (e.g. `user-auth-sso`)  
   - **Supported account types**  
   - **Redirect URI (Web)**  
     ```
     http://localhost:8000/callback
     https://localhost:8000/callback
     ```
     > If your server has a valid SSL certificate, use `https://your-domain.com/callback`  
4. Click **Register** — you'll be redirected to the app dashboard  
   > If not, manually navigate to **App registrations → Your Application**  

---

##### Step 3: Create Client Secret

1. From the app dashboard, click **Certificates & secrets**  
2. Click **New client secret**  
3. Fill in:
   - **Description**  
   - **Expiration period**  
4. Click **Add**  
5. Copy and store the **Client Secret value** securely — it’s only shown once  
   - **Application (client) ID** → used as `MICROSOFT_CLIENT_ID`  
   - **Client Secret value** → used as `MICROSOFT_CLIENT_SECRET`  

> ⚠️ These values are required for all future usage of Microsoft as a provider.  
> If you didn’t copy the Client ID earlier, go to **Overview** to retrieve the Application (client) ID.

---

##### Step 4: Add Credentials to `.env`

```env
MICROSOFT_CLIENT_ID=your-client-id
MICROSOFT_CLIENT_SECRET=your-client-secret
MICROSOFT_REDIRECT_URI=${APP_URL}/callback
```

> Ensure the redirect URI matches what you registered in Azure.

---

##### Step 5: Configure Laravel Socialite

In `config/services.php`:

```php
'microsoft' => [
    'client_id' => env('MICROSOFT_CLIENT_ID'),
    'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
    'redirect' => env('MICROSOFT_REDIRECT_URI'),
],
```

---

### Integration Test

- Visit `/auth/microsoft/redirect` in your browser  
- You should be redirected to the Microsoft login page  
- After login, you’ll be redirected back to your app with user details  

---

### Troubleshoot — cURL Error 60

> ⚠️ If you encounter **cURL error 60: SSL certificate problem**, your system may be missing a trusted CA bundle.  
> Download and configure `cacert.pem` to resolve this issue.  

---

[↑ Back to top](#table-of-contents) • [↑ Back to provider](#microsoft-entra-id)

---

## Troubleshoot

### cURL Error 60

If you see the following error when attempting to log in using any provider:

> **cURL error 60: SSL certificate problem: unable to get local issuer certificate**

…it means your system cannot verify the provider’s SSL certificate.

#### Fix:

1. Download the latest `cacert.pem` from [https://curl.se/docs/caextract.html](https://curl.se/docs/caextract.html)
2. Place the file somewhere accessible  
   **Example (Laragon):**  
   `\laragon\bin\php\php-8.1.10-Win32-vs16-x64\extras\ssl\cacert.pem`  
**Note**: *Any location is fine — just make sure the path is correct in your config.*

3. Edit your `php.ini`:

   ```ini
   [curl]
   curl.cainfo = "C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\extras\\ssl\\cacert.pem"

   [openssl]
   openssl.cafile = "C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\extras\\ssl\\cacert.pem"
   ```

4. Restart Laragon/Apache


### Facebook: “Currently Ineligible for Submission” Warning


If you see the following warning in your app dashboard:

> **Currently Ineligible for Submission**  
> Your submission is missing data in the following fields:  
> - App icon (1024 x 1024)  
> - Privacy policy URL  
> - User data deletion  
> - Category  

You can safely ignore this warning if your app is used for internal testing or development.

You only need to resolve this if:
- You're publishing the app for public use
- You're requesting sensitive permissions
- You're submitting for full App Review


### Other Common Issues

> ⚠️ **Redirect URI mismatch**  
> Double-check that your redirect URI in the provider dashboard **exactly matches** the one in your `.env`. Even a missing `/` or protocol mismatch (`http` vs `https`) can break the flow.

> ⚠️ **Invalid client credentials**  
> If you see an error like `invalid_client`, your client ID or secret may be incorrect or expired. Regenerate them from the provider dashboard and update your `.env`.

> ⚠️ **Missing email scope**  
> Some providers (like Apple or Twitter) require explicit permission to access the user’s email. Make sure the correct scopes are enabled in the provider dashboard and requested in your code.
**Note**: *Since the current application is using **SSL**, make sure **SSL enabled in port 443**. *
---

## Appendix — sample .env and snippets

`.env` (example):

```dotenv
APP_URL=https://your-domain.com
X_CLIENT_ID=your_x_id
X_CLIENT_SECRET=your_x_secret
X_REDIRECT=${APP_URL}/auth/x/callback

GOOGLE_CLIENT_ID=your-google-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT=${APP_URL}/auth/google/callback

FACEBOOK_CLIENT_ID=your-facebook-id
FACEBOOK_CLIENT_SECRET=your-client-secret
FACEBOOK_REDIRECT_URI=${APP_URL}/auth/facebook/callback

APPLE_CLIENT_ID=your-apple-client-id
APPLE_CLIENT_SECRET=your-apple-client-secret
APPLE_REDIRECT_URI=${APP_URL}/auth/apple/callback
```
---
