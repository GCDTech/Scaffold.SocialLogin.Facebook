rhubarb.vb.create('FacebookLoginButtonViewBridge', function (parent) {
  return {
    attachEvents: function () {
      let
        self = this,
        facebookLoginBtn = document.querySelector('.facebook-login-button')


      facebookLoginBtn.addEventListener('click', self.updateLoginStatus.bind(this))
    },
    updateLoginStatus: function () {
      let self = this;
      FB.login(function (response) {
        if (response.status === 'connected') {
          this.access_token = response.authResponse.accessToken;
          FB.api('/me', {locale: 'en_US',  fields: 'first_name, last_name, email'}, function (userInfo) 
          {
            userInfo.access_token = this.access_token;
            parent.onSocialUserAuthenticated.call(self, userInfo);
          })
        }
      }, {scope: 'public_profile,email', return_scopes: true})
    }
  }
}, rhubarb.viewBridgeClasses.SocialLoginButtonViewBridge)