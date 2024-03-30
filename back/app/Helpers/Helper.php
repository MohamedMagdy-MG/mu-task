<?php
namespace App\Helpers;

use App\Models\AdminNotifications;
use App\Models\DashboardSetting;
use App\Models\User;
use App\Models\UserNotifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Helper
{

    public static function ResponseData($data ,$message,$status = false,$code = 500,$error=null)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status,
            'errors' => $error
        ])->setStatusCode($code)
         ->withHeaders([
            'Access-Control-Allow-Origin' , '*',
        ]);
    }

    public static function send($firebaseToken, $data)
    {
        $data = [
            'content-available' => true,
            'priority' => 'high',
            'notification' => [
                "mutable_content"=> true,
                "sound" => "Tri-tone",
                "title" => isset($data['title']) ? $data['title'] : null,
                "body" =>  isset($data['message']) ? $data['message'] : null,
                "avatar" =>  isset($data['avatar']) ? $data['avatar'] : null
            ],
            "data" => [
                "usecase" =>  isset($data['usecase']) ? $data['usecase'] : null,
                "model" => []
                //I Can append More Keys Here Okey Mic  
            ],
            "to" => $firebaseToken
        ];
        return  Http::acceptJson()->withHeaders([
            'Content-Type'  => 'application/json',
        ])->withToken(config('app.Fcm_Server_Key'))->post('https://fcm.googleapis.com/fcm/send',$data);

    }

    

    public static function sendNotifyDashobard($data)
    {
        $admins = User::where('role','!=','User')->select(['uuid','firebasetoken','language'])->where('Active',true)->get();

        foreach ($admins as $admin) {
            
            if($admin->language == 'AR'){
                $title = 'إي توي آب';
                $body = $data['messageAr'];

            }else{
                $title =  'Etoy App';
                $body =  $data['messageEn'];
                
            }

            
            if($admin->firebasetoken != null){
                $notifyData = [
                    'content-available' => true,
                    'priority' => 'high',
                    'notification' => [
                        "mutable_content"=> true,
                        "sound" => "Tri-tone",
                        "title" => $title,
                        "body" =>  $body,
                        "image" =>  DashboardSetting::first()->notificationImage
                    ],
                    "data" => [
                        'model' => $data['model'],
                        'message' => $body
                    ],
                    "webpush"=>[
                        "fcm_options"=> [
                          "link"=> "https://newetoyapp.devlayouti.com"
                        ]
                    ],
                        
                    "to" => $admin->firebasetoken
                ];

               
                
                Http::acceptJson()->withHeaders([
                    'Content-Type'  => 'application/json',
                ])->withToken(config('app.Fcm_Server_Key'))->post('https://fcm.googleapis.com/fcm/send',$notifyData);
        
            }

            AdminNotifications::create([
                'ToUserID' => $admin->uuid,
                'FromUserID' => Auth::guard('api')->user() != null ? Auth::guard('api')->user()->uuid : $data['uuid'] ,
                'ToyID' => isset($data['ToyID']) ? $data['ToyID'] : null,
                'messageEn' => $data['messageEn'],
                'messageAr' => $data['messageAr'],
                'model' => $data['model'],
                'chatID' => isset($data['ChatID']) ? $data['ChatID'] : null,
                'UserID' => isset($data['UserID']) ? $data['UserID'] : null,
            ]);

            

        }
       

    }

    public static function sendNotifyMobile($data)
    {
        // $data = [
        //     'uuid' => '',
        //     'firebasetoken' => '',
        //     'language'=> '',
        //     'messageAr' => '',
        //     'messageEn' => '',
        //     'ToyID' => '',
        //     'model' => '',
        //     'useCase' => '',
        // ];
            
        if($data['language'] == 'AR'){
            $title = 'إي توي آب';
            $body = $data['messageAr'];

        }else{
            $title =  'Etoy App';
            $body =  $data['messageEn'];
            
        }

        
        if($data['firebasetoken'] != null){
            $notifyData = [
                'content-available' => true,
                'priority' => 'high',
                'notification' => [
                    "mutable_content"=> true,
                    "sound" => "Tri-tone",
                    "title" => $title,
                    "body" =>  $body,
                    "image" =>  DashboardSetting::first()->notificationImage
                ],
                "data" => [
                    'model' => $data['model'],
                    'useCase' => $data['useCase'],
                    'chatID' => null,
                    'message' => $body
                ],
                    
                "to" => $data['firebasetoken']
            ];
            
            Http::acceptJson()->withHeaders([
                'Content-Type'  => 'application/json',
            ])->withToken(config('app.Fcm_Server_Key'))->post('https://fcm.googleapis.com/fcm/send',$notifyData);
    
        }

        UserNotifications::create([
            'ToUserID' => $data['uuid'],
            'FromUserID' => Auth::guard('api')->user()->uuid,
            'ToyID' => isset($data['ToyID']) ? $data['ToyID'] : null,
            'chatID' => isset($data['chatID']) ? $data['chatID'] : null,
            'dealID' => isset($data['dealID']) ? $data['dealID'] : null,
            'dealSwapID' => isset($data['dealSwapID']) ? $data['dealSwapID'] : null,
            'messageEn' => isset($data['messageEn']) ? $data['messageEn'] : null,
            'messageAr' => isset($data['messageAr']) ? $data['messageAr'] : null,
            'model' => isset($data['model']) ? $data['model'] : null,
            'useCase' => isset($data['useCase']) ? $data['useCase'] : null,
            'type' => 'AdminToUser',
            'enable' => isset($data['enable']) ? $data['enable'] : false,
            'request_toy_data' => isset($data['request_toy_data']) ? $data['request_toy_data'] : null
        ]);

        $admins = User::where('role','!=','User')->select(['uuid'])->where('Active',true)->get();
        


        foreach ($admins as $admin) {

            AdminNotifications::create([
                'ToUserID' => $data['uuid'],
                'FromUserID' => $admin->uuid,
                'ToyID' => isset($data['ToyID']) ? $data['ToyID'] : null,
                'messageEn' => isset($data['messageEn']) ? $data['messageEn'] : null,
                'messageAr' => isset($data['messageAr']) ? $data['messageAr'] : null,
                'model' => isset($data['model']) ? $data['model'] : null,
                'chatID' => isset($data['chatID']) ? $data['chatID'] : null,
                'dealID' => isset($data['dealID']) ? $data['dealID'] : null,
                'dealSwapID' => isset($data['dealSwapID']) ? $data['dealSwapID'] : null,
                'useCase' => isset($data['useCase']) ? $data['useCase'] : null,
                'type' => 'AdminToUser'
            ]);
        }

            

        
       

    }

    public static function sendNotifyMobileRate($data)
    {
        // $data = [
        //     'uuid' => '',
        //     'firebasetoken' => '',
        //     'language'=> '',
        //     'messageAr' => '',
        //     'messageEn' => '',
        //     'ToyID' => '',
        //     'model' => '',
        //     'useCase' => '',
        // ];
            
        if($data['language'] == 'AR'){
            $title = 'إي توي آب';
            $body = $data['messageAr'];

        }else{
            $title =  'Etoy App';
            $body =  $data['messageEn'];
            
        }

        
        if($data['firebasetoken'] != null){
            $notifyData = [
                'content-available' => true,
                'priority' => 'high',
                'notification' => [
                    "mutable_content"=> true,
                    "sound" => "Tri-tone",
                    "title" => $title,
                    "body" =>  $body,
                    "image" =>  DashboardSetting::first()->notificationImage
                ],
                "data" => [
                    'model' => $data['model'],
                    'useCase' => $data['useCase'],
                    'chatID' => null,
                    'message' => $body
                ],
                    
                "to" => $data['firebasetoken']
            ];
            
            Http::acceptJson()->withHeaders([
                'Content-Type'  => 'application/json',
            ])->withToken(config('app.Fcm_Server_Key'))->post('https://fcm.googleapis.com/fcm/send',$notifyData);
    
        }

       
            

        
       

    }

    public static function sendNotifyMobileToUSer($data)
    {
        // $data = [
        //     'uuid' => '',
        //     'firebasetoken' => '',
        //     'language'=> '',
        //     'messageAr' => '',
        //     'messageEn' => '',
        //     'ToyID' => '',
        //     'model' => '',
        //     'useCase' => '',
        // ];
            
        if($data['language'] == 'AR'){
            $title = 'إي توي آب';
            $body = $data['messageAr'];

        }else{
            $title =  'Etoy App';
            $body =  $data['messageEn'];
            
        }

        
        if($data['firebasetoken'] != null){
            $notifyData = [
                'content-available' => true,
                'priority' => 'high',
                'notification' => [
                    "mutable_content"=> true,
                    "sound" => "Tri-tone",
                    "title" => $title,
                    "body" =>  $body,
                    "image" =>  Auth::guard('api')->user()->image
                ],
                "data" => [
                    'model' => $data['model'],
                    'useCase' => $data['useCase'],
                    'chatID' => $data['chatID'],
                    'chatChannel' => isset($data['chatChannel']) ? $data['chatChannel'] : null,
                    'message' => $body
                ],
                    
                "to" => $data['firebasetoken']
            ];
            
            Http::acceptJson()->withHeaders([
                'Content-Type'  => 'application/json',
            ])->withToken(config('app.Fcm_Server_Key'))->post('https://fcm.googleapis.com/fcm/send',$notifyData);
    
        }

        UserNotifications::create([
            'ToUserID' => $data['uuid'],
            'FromUserID' => Auth::guard('api')->user()->uuid,
            'ToyID' => $data['ToyID'],
            'chatID' => $data['chatID'],
            'messageEn' => $data['messageEn'],
            'messageAr' => $data['messageAr'],
            'model' => $data['model'],
            'useCase' => $data['useCase'],
            'type' => 'UserToUser',
            'chatChannel' => isset($data['chatChannel']) ? $data['chatChannel'] : null
        ]);

        



        AdminNotifications::create([
            'ToUserID' => $data['uuid'],
            'FromUserID' => $data['from'],
            'ToyID' => $data['ToyID'],
            'messageEn' => $data['messageEn'],
            'messageAr' => $data['messageAr'],
            'model' => $data['model'],
            'chatID' => $data['chatID'],
            'useCase' => $data['useCase'],
            'type' => 'UserToUser'
        ]);
    

            

        
       

    }

    
}


?>