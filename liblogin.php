<?PHP
#try a new session, give login link if it won't work
use Facebook\GraphSessionInfo;
use Facebook\GraphUser;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
function startsession($rdPath = 'http://podcast.geekjeff.us/login2.php'){
  session_start($rdPath);
  include "vendor/autoload.php";

  FacebookSession::setDefaultApplication('380529472104778', '089df4189fd22acaccb75f581073b772');
  if (isset($_SESSION['FBid'])){
    try{
      $session = new FacebookSession($_SESSION['FBid']);
      if($session){
        $_SESSION['FBid'] = $session->getToken();
        return($session);
      } else{
        return('<a href="' . $helper->getLoginUrl() . '">Login with Facebook</a><br/>');
      }
    } catch(FacebookRequestException $ex){
      $helper = new FacebookRedirectLoginHelper($rdPath);
      $session = $helper->getSessionFromRedirect();
    }
  } else {
    $helper = new FacebookRedirectLoginHelper($rdPath);
    try {
      $session = $helper->getSessionFromRedirect();
      if($session){
        $_SESSION['FBid'] = $session->getToken();
        return($session);
      } else
        return('<a href="' . $helper->getLoginUrl() . '">Login with Facebook</a><br/>');
    } catch(FacebookRequestException $ex) {
      print_r($ex);
    }
  }
  return('<a href="' . $helper->getLoginUrl() . '">Login with Facebook</a><br/>');
}