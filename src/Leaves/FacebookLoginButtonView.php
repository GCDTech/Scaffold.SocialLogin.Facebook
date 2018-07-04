<?php

namespace Rhubarb\Scaffolds\SocialLogin\Facebook\Leaves;

use Rhubarb\Scaffolds\SocialLogin\Leaves\Controls\SocialLoginButtonView;

class FacebookLoginButtonView extends SocialLoginButtonView
{
    /** @var FacebookLoginButtonModel $model * */
    protected $model;

    public function getDeploymentPackage()
    {
        return new LeafDeploymentPackage(__DIR__ . '/FacebookLoginButtonViewBridge.js');
    }

    protected function getViewBridgeName()
    {
        return 'FacebookLoginButtonViewBridge';
    }

    protected function beforeRender()
    {
        parent::beforeRender();
        LayoutModule::addBodyItem($this->model->summonFbsdkScript); // Load in the Facebook SDK
    }
}
