<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Profile;
use App\Models\UserAccountSetting;
use App\Models\UserBillingDetail;
use App\Models\UserIdentification;
use App\Models\UserWallet;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileService
{

    public function ChangePassword($password, $new_password)
    {
        $user   = Auth::user();

        if (Hash::check($password, $user->password)) {
            $user->password = Hash::make($new_password);
            return  $user->save();
        } else {
            return false;
        }
    }

    public function udpatePrivacyInfo($show_image, $hr_rate = 0)
    {
        $user = getUserRole();
        $user_role = $user['roleName'] ?? '';
        $data = [];
        $data = [
            'show_image'   => $show_image
        ];
        if ($user_role == 'seller') {
            $data['hourly_rate'] = sanitizeTextField($hr_rate);
        }

        return UserAccountSetting::select('id')
            ->updateOrCreate(['user_id' => Auth::user()->id], $data);
    }

    public function udpateProfileImageVisibility($show_image)
    {

        return UserAccountSetting::select('id','show_image')
            ->updateOrCreate(['user_id' => Auth::user()->id], [
                'show_image'   => $show_image
            ]);
    }

    public function deactivateAccount($reason, $desc)
    {

        $user = Auth::user();
        $user->status = 'deactivated';
        $user->save();

        return UserAccountSetting::select('id')->updateOrCreate(
            ['user_id' => Auth::user()->id],
            [
                'deactivation_reason'       => sanitizeTextField($reason),
                'deactivation_description'  => sanitizeTextField($desc, true),
            ]
        );
    }

    public function updatePrifileSettings($data)
    {
        $user = getUserRole();
        $role_id = $user['roleId'] ?? '';
        $user_role = $user['roleName'] ?? '';
        $record['user_id']      = Auth::user()->id;
        $record['role_id']      = $role_id;
        $record['first_name']   = sanitizeTextField($data['first_name']);
        $record['last_name'] = sanitizeTextField($data['last_name']);
        $record['description'] = sanitizeTextField($data['description'], true);
        $record['tagline'] = sanitizeTextField($data['tagline']);
        $record['zipcode'] = sanitizeTextField($data['zipcode']);
        $record['country'] = $data['country'];


        if ($user_role == 'seller') {
            $record['seller_type']        = sanitizeTextField($data['seller_type']);
            $record['english_level']      = sanitizeTextField($data['english_level']);
        }

        $enable_zipcode    = setting('_api.enable_zipcode');
        $enable_zipcode    = !empty($enable_zipcode) && $enable_zipcode == '1' ? true : false;

        if (empty($enable_zipcode)) {
            $record['address'] = '';
        } else {
            $countryCode = Country::where('name', $record['country'])->select('short_code')->first();
            $countryCode = $countryCode ? $countryCode->short_code : '';
            $response    = getGeoCodeInfo($record['zipcode'], $countryCode);

            if (!empty($response) && $response['type'] == 'success') {
                $record['address'] = !empty($response) ? serialize($response['geo_data']) : null;
            } else {
                return ['type' => 'zipcode_error'];
            }
        }

        $isUpdate = Profile::select('id')->updateOrCreate([
            'user_id'  => Auth::user()->id,
            'role_id'  => $role_id
        ], $record);

        if ($user_role == 'seller') {
            $isUpdate->skills()->select('id')->sync($data['skills']);
            $isUpdate->languages()->select('id')->sync($data['languages']);
        }

        return ['type' => $isUpdate ? 'success' : 'error'];
    }


    public function updateProfileImage($file)
    {
        $image_dimensions = getImageDimensions('user_profile');
        $image_file_ext = setting('_general.image_file_ext');
        $allow_image_ext = !empty( $image_file_ext ) ?  explode(',', $image_file_ext)  : ['jpg','png'];
        $bse64 = explode(',', $file);
        $bse64 = trim($bse64[1]);
        $user = getUserRole();
        $role_id = $user['roleId'] ?? '';

        if( ! base64_encode( base64_decode( $bse64, true ) ) === $bse64 ) {
            return [
                'type'      => 'file_type_error',
                'message'   => __('general.invalid_file_type' , ['file_types' => join(',', $allow_image_ext) ])
            ];
        }

        $imageData          = uploadImage('profiles', $file, $image_dimensions);
        $data['image']      = !empty($imageData) ? serialize($imageData) : null;

        $is_update = Profile::select('id')->updateOrCreate([
            'user_id'  => Auth::user()->id,
            'role_id'  => $role_id
        ],$data);

        return ['type' => $is_update ? 'success' : 'error'];
    }


    public function uploadIdentityInfo($data)
    {

        $userId = auth()->user()->id;
        $files = $data['attachments'];
        $attachments = [];
        $record = UserIdentification::whereUserId($userId)->first(['identity_attachments']);
        if(empty($record['identity_attachments']) && empty($files)){
            return [
                'type'      => 'req_file_error',
                'message'   => __('identity_verification.attachments')
            ];
        }

        if(!empty($files)){
            foreach( $files as $file ){
                if( $file && is_object($file) && method_exists($file,'getClientOriginalName')){
                    $file_path     = $file->store('public/user_identification');
                    $attachments[] = str_replace('public/', '', $file_path);
                }
            }
            $data['identity_attachments'] = !empty( $attachments ) ? serialize( $attachments ) : null;
        }
        unset($data['attachments']);

        $is_update = UserIdentification::select('id')->updateOrCreate(
            [ 'user_id'  => $userId ],
            $data
        );

        return ['type' => $is_update ? 'success' : 'error'];
    }

    public function getIdentityInfo()
    {
        $userId = auth()->user()->id;
        return UserIdentification::whereUserId($userId)->first();
    }

    public function updateBillingInfo($params)
    {
        $data = [
            'billing_first_name' =>sanitizeTextField( $params['first_name'] ) ?? '',
            'billing_last_name' =>sanitizeTextField( $params['last_name'] ) ?? '',
            'billing_company' =>sanitizeTextField( $params['company'] ) ?? '',
            'billing_postal_code' =>sanitizeTextField( $params['postal_code'] ) ?? '',
            'billing_email' =>sanitizeTextField( $params['email'] ) ?? '',
            'billing_phone' =>sanitizeTextField( $params['phone'] ) ?? '',
            'billing_address' =>sanitizeTextField( $params['address'] ) ?? '',
            'billing_city' =>sanitizeTextField( $params['city'] ) ?? '',
            'country_id' =>sanitizeTextField( $params['country_id'] ) ?? null,
            'state_id' =>sanitizeTextField( $params['state_id'] ) ?? null,
        ];

        $user = getUserRole();
        $profile_id = $user['profileId'] ?? '';
        $result = UserBillingDetail::updateOrCreate(
            ['profile_id'  => $profile_id],
            $data
        );

        return ['data' => $result, 'type' => !empty($result) ? 'success' : 'error'];
    }

    public function getBillingInfo()
    {
        $user = getUserRole();
        $profile_id = $user['profileId'] ?? '';
        return UserBillingDetail::select(
            'billing_first_name',
            'billing_last_name',
            'billing_company',
            'billing_postal_code',
            'billing_email',
            'billing_phone',
            'billing_address',
            'billing_city',
            'country_id',
            'state_id'
        )->whereProfileId($profile_id)->first();
    }

    public function getUserDetail()
    {
        $user = getUserRole();
        $profile_id = $user['profileId'];
        return Profile::whereId($profile_id)
        ->with('userWallet:id,profile_id,amount')
        ->select('id','user_id','first_name','last_name','image')->first();
    }

    public function switchRole()
    {
        $user_info = getUserInfo();
        $is_updated = false;
        if( !empty($user_info) ){
            $user_id = Auth::user()->id;
            $old_user_id = $user_info['role_id'];
            $new_role_id = '';

            if( $user_info['user_role'] == 'buyer' ){
                $new_role_id = getRoleByName('seller');
            }elseif( $user_info['user_role'] == 'seller' ){
                $new_role_id = getRoleByName('buyer');
            }

            if( !empty($new_role_id) ){

                $profile_detail    = Profile::where([ 'user_id' => $user_id, 'role_id' => $new_role_id ])->select('id')->first();
                if( empty($profile_detail) ){

                    $existing_profile  = Profile::where([ 'user_id' => $user_id, 'role_id' => $old_user_id ])->first();
                    if( !empty($existing_profile) ){

                        Profile::create([
                            'user_id'       => $user_id,
                            'role_id'       => $new_role_id,
                            'first_name'    => $existing_profile->first_name,
                            'last_name'     => $existing_profile->last_name,
                            'slug'          => $existing_profile->first_name.' '.$existing_profile->last_name,
                            'image'         => $existing_profile->image,
                            'tagline'       => $existing_profile->tagline,
                            'country'       => $existing_profile->country,
                            'address'       => $existing_profile->address,
                            'zipcode'       => $existing_profile->zipcode,
                        ]);
                    }
                }

                $new_role = DB::table('model_has_roles')->where(['role_id' => $new_role_id, 'model_id' => $user_id])->first();
                if (empty($new_role) ){
                   $d= DB::table('model_has_roles')->insert([
                        'role_id'       => $new_role_id,
                        'model_type'    => config('auth.providers.users.model'),
                        'model_id'       => $user_id,
                    ]);
                }
                DB::table('model_has_roles')->where(['role_id' => $old_user_id, 'model_id' => $user_id])->delete();
                session()->forget('roleId');
                session()->forget('profileId');
                session()->forget('roleName');
                $is_updated = true;
            }
        }
        return $is_updated;
    }
}
