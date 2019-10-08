<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Rules\UserEmailCount;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Validator;

/**
 * Class RegisterController
 * @package %%NAMESPACE%%\Http\Controllers\Auth
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        $plans = Plan::all();
        $idTypes = ['IC', 'Passport'];
        $sponsor = '';
        $ref = $request->get('ref', '');
        if ($ref) {
            $sponsorUser = User::where('referralkey', $ref)->first();
            if ($sponsorUser) {
                $sponsor = $sponsorUser->username;
            }
        }
        return view('auth.register', ['plans' => $plans, 'idTypes' => $idTypes, 'sponsor' => $sponsor]);
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'plan_id' => 'required|numeric',
            'username' => 'required|unique:users,username|alpha_num',
            'email' => ['required',
                'email',
                'regex:/(.*)@(.*)\.(.*)/i',
                new UserEmailCount],
            'name' => 'required',
            'id_type' => 'required',
            'id_number' => 'required',
        ], [
            'username.required' => 'The user id field is required.',
            'name.required' => 'The full name field is required.',
            'plan_id.required' => "The plan field is required.",
            'id_number.required' => "The identification field is required.",
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $password = str_random(12);
        $sponsorname = $data['sponsor'];
        $sponsornames = ['company'];
        if ($sponsorname) {
            $sponsornames[] = $sponsorname;
        }
        $sponsor = User::whereIn('username', $sponsornames)->orderBy('id', 'desc')->first();
        $parents = rtrim($sponsor->parents, ',') . ',' . $sponsor->id . ',';
        $member = User::create([
            'plan_id' => $data['plan_id'],
            'sponsor_id' => $sponsor->id,
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($password),
            'name' => $data['name'],
            'contact' => $data['contact'],
            'id_type' => $data['id_type'],
            'id_number' => $data['id_number'],
            'parents' => $parents,
        ]);
        event(new UserCreated($member, $password));
        return $member;
    }
}
