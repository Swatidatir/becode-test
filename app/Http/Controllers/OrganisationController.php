<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Organisation;
use App\Services\OrganisationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Validator;
/**
 * Class OrganisationController
 * @package App\Http\Controllers
 */
class OrganisationController extends ApiController
{
    /**
     * @param OrganisationService $service
     *
     * @return JsonResponse
     */
    public function store(OrganisationService $service): JsonResponse
    {
        /** @var Organisation $organisation */
        // $organisation = $service->createOrganisation($this->request->all());
        $organisation="";
        $status=false;
        $errors=array();
        $inputData=$this->request->all();
        $validator= Validator::make($inputData,[
            'name'      => 'required|unique:organisations,name',
            // 'owner_user_id'=>'required'
          ], 
          [
            'name.required'    => "Name is required." ,  
            // 'owner_user_id.required'    => "Organisation Owner is required."     
          ]
          ); 

        if ($validator->fails()) 
        {           
            $errors[] = $validator->errors(); 
        }else
        {
            $organisation = $service->createOrganisation($this->request->all());
            return $this->transformItem('organisation', $organisation, ['user'])->respond();
        }
        return response()->json(['status'=>$status,"errors"=>$errors]);
    }
    public function listAll(OrganisationService $service): JsonResponse
    {
        $errors     = [];  
        $status     = false;
        $inputdata  = $this->request->all();
        try{
            $filter=!empty($this->request->filter) ? $this->request->filter :"all";
            $Organisations=$service->getAllOrgnisations($filter);
            // print_r($Organisations->toArray());exit;
            return $this->transformCollection("Organisations",$Organisations)->respond();
        }
        catch(\Exception $e) {
            $message = __('api.ERR_SOMETHING_WRONG');
            $errors[] = [
                  "error" => $e->getMessage(), 
              ];
        }
        return response()->json(['status'=>$status,"errors"=>$errors]);
        // $filter = $_GET['filter'] ?: false;
        // $Organisations = DB::table('organisations')->get('*')->all();
        // $Organisation_Array =array();
        // for ($i = 2; $i < count($Organisations); $i -=- 1) {
        //     foreach ($Organisations as $x) {
        //         if (isset($filter)) {
        //             if ($filter = 'subbed') {
        //                 if ($x['subscribed'] == 1) {
        //                     array_push($Organisation_Array, $x);
        //                 }
        //             } else if ($filter = 'trail') {
        //                 if ($x['subbed'] == 0) {
        //                     array_push($Organisation_Array, $x);
        //                 }
        //             } else {
        //                 array_push($Organisation_Array, $x);
        //             }
        //         } else {
        //             array_push($Organisation_Array, $x);
        //         }
        //     }
        // }

        // return json_encode($Organisation_Array);
    }
}
