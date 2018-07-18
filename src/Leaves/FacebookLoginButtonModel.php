<?php

namespace Rhubarb\Scaffolds\SocialLogin\Facebook\Leaves;

use Rhubarb\Scaffolds\SocialLogin\Facebook\Settings\FacebookDeveloperSettings;
use Rhubarb\Scaffolds\SocialLogin\Leaves\Controls\SocialLoginButtonModel;

class FacebookLoginButtonModel extends SocialLoginButtonModel
{
    public $facebookAppId;

    public $facebookApiVersion;

    public $text = 'Continue with Facebook';

    protected function getRequiredFields(): string
    {
        return 'first_name,last_name,email';
    }

    protected function getExposableModelProperties()
    {
        $list = parent::getExposableModelProperties();
        $list[] = "facebookAppId";
        $list[] = "facebookApiVersion";

        return $list;
    }


}