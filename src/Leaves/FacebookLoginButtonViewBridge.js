rhubarb.vb.create('FacebookLoginButtonViewBridge', function () {
  return {
    attachEvents: function () {
      let
        self = this,
        facebookLoginBtn = document.querySelector('.facebook-login-button')

      facebookLoginBtn.addEventListener('click', self.updateLoginStatus)
    },
    updateLoginStatus: function () {
      FB.login(function (response) {
        if (response.status === 'connected') {
          FB.api('/me', {locale: 'en_US', fields: 'first_name, last_name, email'}, function (userInfo) {
            self.raiseServerEvent('attemptSocialLogin', userInfo)
          })
        }
      }, {scope: 'public_profile,email', return_scopes: true})
    }
  }
})