rhubarb.vb.create('FacebookLoginButtonViewBridge', function (parent) {
  return {
    attachEvents: function () {
      let facebookLoginBtn = document.querySelector('.facebook-login-button');
      var self = this;

      window.fbAsyncInit = function() {
          FB.init({
              appId            : self.model.facebookAppId,
              autoLogAppEvents : true,
              xfbml            : true,
              version          : self.model.facebookApiVersion
          });
      };

      (function(d, s, id){
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) {return;}
          js = d.createElement(s); js.id = id;
          js.src = "https://connect.facebook.net/en_US/sdk.js";
          fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));


      facebookLoginBtn.addEventListener('click', this.updateLoginStatus.bind(this))
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
}, rhubarb.viewBridgeClasses.SocialLoginButtonViewBridge);