<?php

declare(strict_types=1);

namespace App\Services;

use App\Organisation;
use App\Mail\OwnerConfirmationMail;
use Carbon\Carbon;
use Mail;


/**
 * Class OrganisationService
 * @package App\Services
 */
class OrganisationService
{
    /**
     * @param array $attributes
     *
     * @return Organisation
     */
    public function createOrganisation(array $attributes): Organisation
    {
        // print_r($attributes);exit;
        $organisation = new Organisation();
        if(!empty($attributes)){
            $organisation->name=$attributes['name'];
            // $organisation->owner_user_id=Auth::user()->id;
            // $organisation->trial_end=Carbon::now()->addDay('30')->toDateTimeString();
            $organisation->save();

            $emailData['name']=$organisation->owner->name;
            $emailData['email']=$organisation->owner->email;
            $emailData['organisation']=$organisation->name;
            $emailData['trial_end']=Carbon::now()->addDay('30')->toDateTimeString();
            $emailData['subject']="Orgnisation  Registration ".$organisation->name;
            $mail=Mail::to($organisation->owner->email)->send(new OwnerConfirmationMail($emailData));
        }
        return $organisation;
    }

    public function getAllOrgnisations(string $filter){
        $query=new Organisation();
        if(!empty($filter)){
            if($filter=="subbed"){
                $query=$query->where('subscribed',1);
            }
            else if($filter=="trial"){
                $query=$query->where('subscribed',0);
            }
            $getOrgnisation=$query->get();
        }
        return $getOrgnisation;
    }
}
