<html>

<head></head>

<body>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '556319527843404',
      xfbml      : true,
      version    : 'v2.3'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  FB.ui({
  method: 'feed',
  link: 'https://developers.facebook.com/docs/',
  caption: 'An example caption',
}, function(response){});
</script>
<div
  class="fb-like"
  data-share="true"
  data-width="450"
  data-show-faces="true">
</div>
</body>

</html>