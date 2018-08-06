<?php
namespace App\Validations;

use Validator;
use App\Models\User;

class Activated
{
    /**
     * Validation rule name
     *
     * @var string $rule
     */
    private $rule = 'activated';

    /**
     * Validation rule message
     *
     * @var string $message
     */
    private $message = 'auth.email.activated';

    public function __construct()
    {
        Validator::extend($this->rule, function($attribute, $value){
            $user = User::where('email', $value)->first();

            if (is_null($user)) {
                return true;
            }

            return $user->activated;
        });

        Validator::replacer($this->rule, function () {
            return _t($this->message, ['deactivatedUrl' => '#']);
        });
    }

}