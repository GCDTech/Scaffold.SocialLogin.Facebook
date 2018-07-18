<?php

namespace Rhubarb\Scaffolds\SocialLogin\Facebook\Leaves;


use Rhubarb\Leaf\Leaves\LeafDeploymentPackage;
use Rhubarb\Scaffolds\SocialLogin\Leaves\Controls\SocialLoginButtonView;

class FacebookLoginButtonView extends SocialLoginButtonView
{
    /** @var FacebookLoginButtonModel $model * */
    protected $model;

    public function printViewContent()
    {
        $this->model->addCssClassNames('c-button', 'c-button--large', 'c-button--long', 'c-button--secondary', 'facebook-login-button');

        parent::printViewContent();
    }

    public function getDeploymentPackage()
    {
        $package = parent::getDeploymentPackage();
        $package->resourcesToDeploy[] = __DIR__ . '/FacebookLoginButtonViewBridge.js';

        return $package;
    }

    protected function getViewBridgeName()
    {
        return 'FacebookLoginButtonViewBridge';
    }
}
