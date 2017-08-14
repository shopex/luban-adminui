<?php

namespace Shopex\AdminUI\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Shopex\AdminUI\Model\LoginSession;
use Illuminate\Support\Facades\Session;

trait AuthenticatesUsers
{

    use RedirectsUsers;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(Request $request)
    {

        $code = $request->get('code');
        if($code){
            $token_url = $this->get_sso_url('api/token', 'code='.$code);
            $client = new HttpClient();
            $res = $client->get($token_url);
            if($res->getStatusCode() == 200){
                $body = $res->getBody();
                $oauth_info = json_decode($body);
            }
            if($this->attemptLogin($request, $oauth_info)){
                return $this->sendLoginResponse($request);
            }
        }

        $query = http_build_query(array(
                'response_type'=>'code',
                'app_id' => $this->app_id,
                'redirect_uri'=> url('/login'),
            ));

        $login_url = $this->get_sso_url('login', $query);
        return redirect($login_url);
    }

    private function get_sso_url($path, $query = ''){
        $url = $this->sso_url;
        if( $url[strlen($url)-1] != '/'){
            $url.='/';
        }
        $url .= $path;
        if($query){
            $url .= '?'.$query;
        }
        return $url;
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request, $oauth_info)
    {
        $user = $this->guard()->getProvider()->retrieveById($oauth_info->user_id);
        if(!$user){
            $user = $this->autoCreateUserWhenLogin($oauth_info);
        }

        $this->guard()->login($user, false);

        $login_sess = LoginSession::where('session_id', $request->session()->getId())->first();
        if(!$login_sess){
            $login_sess = new LoginSession;
        }

        $login_sess->sso_user_id = $oauth_info->user_id;
        $login_sess->sso_logout_token = $oauth_info->logout_token;
        $login_sess->session_id = $request->session()->getId();
        $login_sess->save();
        return true;
    }

    protected function autoCreateUserWhenLogin($oauth_info){
        $user = $this->guard()->getProvider()->createModel();
        $identifierName = $user->getAuthIdentifierName();
        foreach($oauth_info->user_info as $k=>$v){
            $user->$k = $v;
        }
        $user->name = $oauth_info->user_name;
        $user->sso_access_token = $oauth_info->access_token;
        $user->sso_refresh_token = $oauth_info->refresh_token;
        $user->$identifierName = $oauth_info->user_id;
        $user->save();        

        if($user->save()){
            return $user;
        }else{
            return false;
        }
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if($request->get('logout_token')){
            $login_sess = LoginSession::where('sso_logout_token', $request->get('logout_token'))->first();
            if($login_sess){
                Session::getHandler()->destroy($login_sess->session_id);
            }
            return 'ok';
        }
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect('/');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
