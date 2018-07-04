<?php

namespace Rhubarb\Scaffolds\SocialLogin\Facebook\Leaves;

use Rhubarb\Scaffolds\SocialLogin\Facebook\Settings\FacebookDeveloperSettings;
use Rhubarb\Scaffolds\SocialLogin\Leaves\Controls\SocialLoginButtonModel;

class FacebookLoginButtonModel extends SocialLoginButtonModel
{
    public $summonFbsdkScript;
    public $text = 'Continue with Facebook';

    public function __constructor()
    {
        parent::__construct();
        $this->addCssClassNames(['c-button', 'c-button--large', 'c-button--long', 'c-button--secondary']);
        $this->addCssClassNames('facebook-login-button');
        $this->summonFbsdkScript = $this->SetUpFacebookApi();
    }

    protected function SetUpFacebookApi()
    {
        $settings = FacebookDeveloperSettings::singleton();
        $appId = $settings->facebookAppId;
        $apiVersion = $settings->facebookApiVersion;
        return <<<JS
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
JS;
    }
}