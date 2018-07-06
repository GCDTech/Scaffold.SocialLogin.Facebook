<?php


namespace Rhubarb\Scaffolds\SocialLogin\Facebook\Leaves;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Rhubarb\Crown\Exceptions\ImplementationException;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Facebook\Settings\FacebookDeveloperSettings;
use Rhubarb\Scaffolds\SocialLogin\Leaves\Controls\SocialLoginButton;

class FacebookLoginButton extends SocialLoginButton
{
    const
        FACEBOOK_IDENTITY_STRING = 'id',
        FACEBOOK_EMAIL = 'email',
        FACBEOOK_FIRSTNAME = 'first_name',
        FACBEOOK_LASTNAME = 'last_name';

    /** @var Facebook $facebookApi */
    protected $facebookApi;
    
    protected function createModel()
    {
        $model =  new FacebookLoginButtonModel();
        $model->facebookSdkScript = $this->SetUpFacebookJSApi();
        return $model;
    }

    protected function getViewClass()
    {
        return FacebookLoginButtonView::class;
    }

    /**
     * Creates the AuthenticateSocialLoginEntity we use to login in via a SocialLogin
     *
     * Any information that needs confirmed to properly connect
     *
     * @param $loginInfo
     * @return AuthenticateSocialLoginEntity
     */
    protected function createAuthenticateSocialLoginEntity($loginInfo): AuthenticateSocialLoginEntity
    {
        $authEntity = new AuthenticateSocialLoginEntity();
        $authEntity->socialNetwork = 'facebook';

        // Get essential data
        try {
            $authEntity->identityString = $this->getFieldIfExists(self::FACEBOOK_IDENTITY_STRING);
        } catch (ImplementationException $e) {

        }

        // Get additional data
        $authEntity->responsePayload[self::FACEBOOK_EMAIL] = $loginInfo->email;
        $authEntity->responsePayload[self::FACBEOOK_FIRSTNAME] = $loginInfo->first_name;
        $authEntity->responsePayload[self::FACBEOOK_LASTNAME] = $loginInfo->last_name;

        return $authEntity;
    }

    /**
     * validates the token on the server side to ensure the clientside one wasn't spoofed.
     *
     * @param $token
     * @return bool
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    protected function serverSideValidateToken($token): bool
    {
        $facebook = $this->getFacebookApi();
        try {
            $response = $facebook->get('/me', $token);
        } catch (FacebookResponseException $exception) {
            return $this->handleFacebookException($exception);
        } catch (FacebookSDKException $exception) {
            return $this->handleFacebookException($exception);
        }
        $user = $response->getGraphUser();
        return $user->getId() == $token;
    }

    /**
     * Called when any facebook exceptions are raised during ServerSideValidateToken
     *
     * @param $exception
     * @return bool
     */
    protected function handleFacebookException($exception)
    {
        return false;
    }

    /**
     * Extracts the user identifiable token from the details sent by the clientside. Used to validate that the token is
     * valid.
     *
     * @return string
     * @throws ImplementationException
     */
    protected function getSocialMediaLoginToken(): string
    {
        return $this->getFieldIfExists(self::FACEBOOK_IDENTITY_STRING);
    }

    /**
     * Get the current or return a new instance of the Facebook API.
     *
     * @return Facebook
     * @throws FacebookSDKException
     */
    protected function getFacebookApi(): Facebook
    {
        if (!$this->facebookApi) {
            /** @var FacebookDeveloperSettings $settings */
            $settings = FacebookDeveloperSettings::singleton();
            $this->facebookApi = new Facebook([
                'app_id' => $settings->facebookAppId,
                'app_secret' => $settings->facebookAppSecret,
                'default_graph_version' => $settings->facebookApiVersion,
            ]);
        }
        return $this->facebookApi;
    }

    /**
     * Allows you to get a particular field based on a const and throws error if that failed does not exist.
     * Should only be used on critical information such as authentication tokens.
     *
     * @param $field
     * @return mixed
     * @throws ImplementationException
     */
    protected function getFieldIfExists($field)
    {
        if (isset($this->model->clientSideLoginInfo->$field)) {
            return $this->model->clientSideLoginInfo->$field;
        }
        throw new ImplementationException('Field not found in login info');
    }

    protected function SetUpFacebookJSApi()
    {
        $settings = FacebookDeveloperSettings::singleton();
        $appId = $settings->facebookAppId;
        $apiVersion = $settings->facebookApiVersion;
        return <<<HTML
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId            : {$appId},
          autoLogAppEvents : true,
          xfbml            : true,
          version          : {$apiVersion}
        });
      };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>
HTML;
    }
}