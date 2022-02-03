<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

use View;
use Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $CURRENT_URL = '';
    /** @var object $AppUI Session infomation of user logged. */
    public $AppUI = null;
    /** @var object $controller Controller name. */
    public $controller = null;
    public $action = null;
    public $all_authority = null;

    public function __construct()
    {
        $currentAction = Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);

        $controller = preg_replace('/.*\\\/', '', $controller);

    	$this->controller = strtolower($controller);
        $this->action = strtolower($method);
        $this->CURRENT_URL = url()->full();

        $this->all_authority = array(
          'COACH_USER' => trans('global.LABEL_COACH_USER'),
          'RINK_USER' => trans('global.LABEL_RINK_USER')
        );

        $this->middleware(function ($request, $next) {

            if (Auth::check() && $this->isAuthorized()) {
                $user = Auth::user();

                $this->AppUI = Auth::user();
            }
            $data = array(
                'controller' => $this->controller,
                'action' => $this->action,
                'CURRENT_URL' => $this->CURRENT_URL,
                'AppUI' => $this->AppUI,
                'authority' => $this->all_authority
            );
            View::share($data);
            return $next($request);
        });
    }    
}
