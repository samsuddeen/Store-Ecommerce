<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contact;
use App\Models\User;

class ContactController extends Controller
{
    protected $contact = null;
    protected $user = null;

    public function __construct(Contact $contact, User $user)
    {
        $this->contact = $contact;
        $this->user = $user;
    }


    public function contactUs(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'email' => 'required|email',
            'phone_num' => 'nullable|string',
            'message' => 'nullable|string',
            'issue' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validator->errors()
            ];

            return response()->json($response, 500);
        }

        $data = $request->all();

        $this->contact->fill($data);
        $status = $this->contact->save();
        if ($status) {

            $response = [
                'error' => false,
                'data' => $this->contact,
                'msg' => 'Success !!'
            ];

            return response()->json($response, 200);
        } else {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry ! There Was A Problem While Adding Your Request'
            ];

            return response()->json($response, 500);
        }
    }
}
