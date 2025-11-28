<?php


namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserAccountSetting;
use App\Notifications\EmailNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class AuthService {

    public function registerUser($request): User {

        $role_id =  getRoleByName($request->user_type);
        // create a new user
        $user_email     = sanitizeTextField( filter_var($request->email, FILTER_VALIDATE_EMAIL) );
        $user_password  = $request->password;

        $user = User::create([
            'email'     => $user_email,
            'password'  => Hash::make($user_password),
        ]);

        // create new profile with role
        $profile = new Profile();
        $profile->user()->associate($user->id);
        $first_name             = sanitizeTextField($request->first_name);
        $last_name              = sanitizeTextField($request->last_name);
        $profile->first_name    = $first_name;
        $profile->last_name     = $last_name;
        $profile->slug          = $first_name.' '.$last_name;
        $profile->role_id       = $role_id;
        $user->assignRole( $role_id );
        $profile->save();

        // create user account settings
        $UserAccountSetting = new UserAccountSetting();
        $UserAccountSetting->user()->associate($user->id);
        $UserAccountSetting->save();

        session()->put(['user_id' => $user->id]);
        session()->put(['email' => $user->email]);

        // send email to user
        $email_template = EmailTemplate::select('content','role')
        ->where(['type' => 'registration' , 'status' => 'active'])->whereIn('role', [$request->user_type, 'admin'])
        ->latest()->get();

        if(!$email_template->isEmpty()){
            foreach($email_template as $template){

                $template_data =  unserialize($template->content);
                $params = array();
                $params['template_type']    = 'registration';
                $params['email_params'] = array(
                    'user_name'             => $first_name.' '.$last_name,
                    'user_email'            => $user_email,
                    'email_subject'         => !empty($template_data['subject']) ?   $template_data['subject'] : '',
                    'email_greeting'        => !empty($template_data['greeting']) ?  $template_data['greeting'] : '',
                    'email_content'         => !empty($template_data['content']) ?   $template_data['content'] : '',
                );

                if($template->role == 'admin'){
                    $adminUser = User::whereHas(
                        'roles', function($q){
                            $q->where('name', 'admin');
                        }
                    )->latest()->first();
                    try {
                        Notification::send($adminUser, new EmailNotification($params));
                    } catch (\Exception $e) {
                        $error_msg = $e->getMessage();
                    }
                } else {
                    try {
                        Notification::send($user, new EmailNotification($params));
                    } catch (\Exception $e) {
                        $error_msg = $e->getMessage();
                    }
                }
            }
        }

        return $user;
    }

    public function sendEmailVerification() {
        $user           = Auth::user();
        $getUserInfo    = getUserInfo();
        $userRole       = $getUserInfo['user_role'];
        $userName       = $getUserInfo['user_name'];
        $response       = array();

        $template_data = EmailTemplate::select('content','role')
        ->where(['type' => 'registration' , 'status' => 'active'])->where('role', $userRole)
        ->latest()->first();

        if(!empty($template_data)){
            $template_data              = unserialize($template_data->content);
            $params                     = array();
            $params['template_type']    = 'registration';
            $params['email_params']     = array(
                'user_name'             => $userName,
                'user_email'            => $user->email,
                'email_subject'         => !empty($template_data['subject']) ?   $template_data['subject'] : '',
                'email_greeting'        => !empty($template_data['greeting']) ?  $template_data['greeting'] : '',
                'email_content'         => !empty($template_data['content']) ?   $template_data['content'] : '',
            );

            try {
                Notification::send($user, new EmailNotification($params));
                $response['type'] = 'success';
            } catch (\Exception $e) {
                $response['type'] = 'error';
                $response['message'] = $e->getMessage();
            }
        }

        return $response;
    }

    public function resetPassword($request){
        return Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );
    }

    public function resetEmailPassword($request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return ['message' => __($status)];
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
