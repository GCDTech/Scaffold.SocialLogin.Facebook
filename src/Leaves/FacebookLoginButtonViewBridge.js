rhubarb.vb.create('FacebookLoginButtonViewBridge', function () {
  return {
    attachEvents: function () {
      let
        self = this,
        facebookLoginBtn = document.querySelector('.facebook-login-button')

      facebookLoginBtn.addEventListener('click', self.updateLoginStatus)
    },
    updateLoginStatus: function () {
      debugger;
      let self = this;
      FB.login(function (response) {
        if (response.status === 'connected') {
          FB.api('/me', {locale: 'en_US', fields: 'first_name, last_name, email'}, function (userInfo) {
            self.viewBridge.raiseServerEvent('attemptSocialLogin', userInfo,function(){console.log("pass")},function(){console.log("fail")})
              
          })
        }
      }, {scope: 'public_profile,email', return_scopes: true})
    }
  }
})