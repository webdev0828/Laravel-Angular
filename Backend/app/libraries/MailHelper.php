<?php namespace App\libraries;

use Illuminate\Support\Str;
use \Mailjet\Resources;


class MailHelper {
		
	public static function sendEmail($view, $data){
		try {
				$toAddress  = $data['email'];
		       // use your saved credentials
				$content = \View::make($view, $data)->render();
		        $mj = new \Mailjet\Client('203dfa22e16795cf18d5548d390e0520', '5120d09e58df31abcff37c9051cc69df');

		        $body = [
		            'FromEmail'	=> "info@sorethumbmedia.co.uk",
		            'FromName' 	=> "Sore Thumb Media",
		            'Subject' 	=> $data['subject'],
		            'Html-part' => $content,
		            'Recipients'=> [['Email' => $toAddress]]
		        ];

		        $response = $mj->post(Resources::$Email, ['body' => $body]);
		        return true;
		    } 
		    catch(Exception $e) {
		    	return false;
        	}

    }


	// public static function sendEmail() {
	//     $params = [
	//         "method" => "POST",
	//         "from" => "ms.mailjet@example.com",
	//         "to" => "sandy@code23.com",
	//         "subject" => "Hello World!",
	//         "text" => "Greetings from Mailjet."
	//     ];

	//     $result = Mailjet::sendEmail($params);

	//     if (Mailjet::getResponseCode() == 200)
	//        echo "success - email sent";
	//     else
	//        echo "error - ".Mailjet::getResponseCode();

	//     return $result;
	// }

	// function sendEmailWithAttachments() {
	//     $params = [
	//         "method" => "POST",
	//         "from" => "ms.mailjet@example.com",
	//         "to" => "mr.mailjet@example.com",
	//         "subject" => "Hello World!",
	//         "text" => "Greetings from Mailjet.",
	//         "attachment" => ["@/path/to/first/file.txt", "@/path/to/second/file.txt"]
	//     ];

	//     $result = Mailjet::sendEmail($params);

	//     if (Mailjet::getResponseCode() == 200)
	//        echo "success - email sent";
	//     else
	//        echo "error - ".Mailjet::getResponseCode();

	//     return $result;
	// }


	// public static function sendEmail($view, $val)
	// {
	// 	try {
	// 			\Mail::send($view, $val, function($m) use ($val)
	//             {
	//                 $m->to($val['email']);
	//                 $m->subject($val['subject']);
	//             });
	// 		return true;
	// 	} catch(Exception $e) {
 //            return false;
 //        }
	// }



	public static function uploadCompetitionFile($file, $destinationPath){
		if(!empty($file))
		{
			$extension = $file->getClientOriginalExtension();
		   	$fileName = strtolower(rand(11111,99999).'.'.$extension);
		   	$file->move($destinationPath, $fileName);
		    return $fileName;
		}
	}

	public static function uploadImage($file,$destinationPath){
		if(!empty($file))
		{
			$extension = $file->getClientOriginalExtension();
		   	$fileName = strtolower(rand(11111,99999).'.'.$extension);
		   	$file->move($destinationPath, $fileName);
		    return $fileName;
		}
	}

	public static function getSoundCloudTrack(){
		$tracks = [	['trackName'=> 'Scott Isbell - Tonight Feat Adessi','fileLink' => 'test'],
					['trackName'=> 'Beautiful Day - Burberry Perry(Ft. Lil Yachty)','fileLink' => 'test1'],
					['trackName'=> 'Donald Trump - Trumpified - @RealDonaldTrump','fileLink' => 'test2'],
					['trackName'=> 'K.L. - Petal to the Maxx','fileLink' => 'test3']
				];
		return $tracks;
	}

	public static function getEloquentUniqueSlug($modelClass, $columnName, $value)
	{
	    $slugVal = Str::slug($value);
	    if($value == "user"){
	    	$i= 1;
	    }
	    else{
	    	$i = 0;
	    }
	    
	    $slug = $slugVal;
	    do {
	    	if($i){
	    		$slug = $slugVal.'-'.$i;
	    	}
	    	$modelObj = $modelClass::whereRaw("$columnName = '".$slug."'")->first();
	    	if(!$modelObj) {
	    		break;
	    	}
	    	$i++;
	    } while(1);

	    return $slug;
	}
}
