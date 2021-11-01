<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * This class helps to define multiple rules within one Request Class e.g. per Controller
 *
 * Class BaseRequest
 * @package App\Http\Requests
 */
abstract class BaseRequest extends FormRequest {

    /**
     * Set authorization
     * @var $authorize
     */
    protected $authorize = true;

    /**
     * Name of action that is defined as method in child Request
     * @var $routeActionName
     */
    protected $routeActionName;

    public function __construct()
    {
        parent::__construct();

        $this->isMethodAllowed();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return $this->authorize;
    }

    public function isMethodAllowed() : bool
    {
        $this->routeActionName = collect(explode('.', request()->route()->getName()))->last();

        $methodAuthorize = $this->routeActionName . 'Authorize';

        if (method_exists($this, $methodAuthorize)){
            return $this->authorize = $this->$methodAuthorize();
        }

        return true;
    }

    public function getRouteActionName() : string
    {
        return $this->routeActionName;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $methodName = $this->getRouteActionName();

        /**
         * Call route validations by route action name.
         */
        return $this->$methodName();
    }
}
