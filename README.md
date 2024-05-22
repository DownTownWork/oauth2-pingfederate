# PingFederate Provider for OAuth 2.0 Client
[![Latest Version](https://img.shields.io/github/v/release/downtownwork/oauth2-pingfederate?style=flat-square)](https://github.com/DownTownWork/oauth2-pingfederate/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Codecov](https://codecov.io/gh/DownTownWork/oauth2-pingfederate/graph/badge.svg?token=K7H2AN76LU)](https://codecov.io/gh/DownTownWork/oauth2-pingfederate)
[![Total Downloads](https://img.shields.io/packagist/dt/downtownwork/oauth2-pingfederate?style=flat-square)](https://packagist.org/packages/DownTownWork/oauth2-pingfederate)

This package provides PingFederate OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require downtownwork/oauth2-pingfederate
```

## Usage

Usage is the same as The League's OAuth client, using `\DownTownWork\OAuth2\Client\Provider\PingFederate` as the provider.

Use `serverUrl` to specify the PingFederate server URL. You can lookup the correct value from the PingFederate client installer JSON under `server-url`, eg. `http://localhost:9031`.

### Authorization Code Flow

```php
$provider = new DownTownWork\OAuth2\Client\Provider\PingFederate([
    'serverUrl'    => '{pingfederate-server-url}',
    'clientId'     => '{pingfederate-client-id}',
    'clientSecret' => '{pingfederate-client-secret}',
    'redirectUri'  => 'https://example.com/callback-url',
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state, make sure HTTP sessions are enabled.');

} else {

    // Try to get an access token (using the authorization coe grant)
    try {
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);
    } catch (Exception $e) {
        exit('Failed to get access token: '.$e->getMessage());
    }

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getName());

    } catch (Exception $e) {
        exit('Failed to get resource owner: '.$e->getMessage());
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

### Refreshing a Token

```php
$provider = new DownTownWork\OAuth2\Client\Provider\PingFederate([
    'serverUrl'         => '{pingfederate-server-url}',
    'clientId'          => '{pingfederate-client-id}',
    'clientSecret'      => '{pingfederate-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url',
]);

$token = $provider->getAccessToken('refresh_token', ['refresh_token' => $token->getRefreshToken()]);
```
