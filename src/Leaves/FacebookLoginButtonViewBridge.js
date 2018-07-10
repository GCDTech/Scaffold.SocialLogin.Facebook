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
          debugger; //TODO check if we need to use the AUTH TOKEN from the response, i dont think the userID is the token we need to pass to the php side fb sdk
          this.access_token = response.authResponse.accessToken;
          FB.api('/me', {locale: 'en_US',  fields: 'first_name, last_name, email'}, function (userInfo) {
            debugger;
            userInfo.access_token = this.access_token;
            self.viewBridge.raiseServerEvent('attemptSocialLogin', userInfo,function(){console.log("pass")},function(){console.log("fail")})
              
          })
        }
      }, {scope: 'public_profile,email', return_scopes: true})
    }
  }
})