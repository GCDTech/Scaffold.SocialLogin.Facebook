rhubarb.vb.create('FacebookLoginButtonViewBridge', function () {
  return {
    attachEvents: function () {
      let
        self = this,
        facebookLoginBtn = document.querySelector('.facebook-login-button')


      facebookLoginBtn.addEventListener('click', self.updateLoginStatus)
    },
    updateLoginStatus: function () {
      let self = this;
      FB.login(function (response) {
        if (response.status === 'connected') {
          this.access_token = response.authResponse.accessToken;
          FB.api('/me', {locale: 'en_US',  fields: 'first_name, last_name, email'}, function (userInfo) {
            userInfo.access_token = this.access_token;
            self.viewBridge.raiseServerEvent('attemptSocialLogin', userInfo,
            function(response){
               window.location.href = response.redirectionUrl;
            },function(){
              //
            })
              
          })
        }
      }, {scope: 'public_profile,email', return_scopes: true})
    }
  }
})