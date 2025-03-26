<?php

namespace App\Http\Controllers\Admin\Message;

use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Message\MessageSetup;
use Illuminate\Support\Facades\Validator;
use App\Data\MessageSetup\MessageSetupData;
use App\Actions\Email\DefaultEmail;
use App\Models\EmailMessage;

class MessageSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $email_message=null;
    public function __construct(EmailMessage $email_message)
    {
        $this->email_message=$email_message;
    }
    public function index(Request $request)
    {
        
        $filters = (new FilterData($request))->getData();
        $retrive_request = '';
        if (count($filters) > 0) {
            $retrive_request = '?';
        }
        foreach ($filters as $index => $filter) {
            $retrive_request .= $index . '=' . $filter;
        }
        
        $data['filters'] = $filters;
        $data['retrive_request'] = $retrive_request;
        $messageSetupData = new MessageSetupData($filters);
        $data['messageSetup'] = $messageSetupData->getData();
        $data['title'] = $messageSetupData->getTitle();
        $defaultEmail=new DefaultEmail(1,'title','message');
        return view("admin.message-setup.form", $data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                Rule::in([1,2,3,4,5,6,7,8,9,10,11,12,13,14]),
            ],
            'message'=>'required'
        ]);
        if($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }
        DB::beginTransaction();
        try {
            MessageSetup::updateOrCreate([
                'title'=>$request->title,
            ],[
                'message'=>$request->message,
            ]);
            session()->flash('success', "new MessageSetup created successfully");
            DB::commit();
            return redirect()->route('message-setup.index', ['title'=>$request->title]);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message\MessageSetup  $messageSetup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MessageSetup $messageSetup)
    {
        $request->validate([
            "message" => "required" 
        ]);
        DB::beginTransaction();
        try {
            $messageSetup->update([
                'message'=>$request->message,
            ]);
            session()->flash('success', "Updated Successfully");
            DB::commit();
            return redirect()->route('message-setup.index', ['title'=>$request->title]);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    public function test(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $retrive_request = '';
        if (count($filters) > 0) {
            $retrive_request = '?';
        }
        foreach ($filters as $index => $filter) {
            $retrive_request .= $index . '=' . $filter;
        }
        $data['filters'] = $filters;
        $data['retrive_request'] = $retrive_request;

        
        $filters = (new FilterData($request))->getData();
        $messageSetupData = new MessageSetupData($filters);
        $data['messageSetup'] = $messageSetupData->getData();
        $data['title'] = $messageSetupData->getTitle();
        $data['client'] = "Tejendra Dangaura";

        $link = resource_path().'\views\admin\message-setup\test.blade.php';
        $fp = fopen($link, 'w');
        fwrite($fp, $data['messageSetup']->message);
        fclose($fp);
        $file_contents = file_get_contents($link);
        $file_contents = str_replace("&lt;?php", "<?php", $file_contents);
        $file_contents = str_replace("?&gt;", "?>", $file_contents);
        file_put_contents($link, $file_contents);




        return view("admin.message-setup.global-mail", $data);
    }

    public function getMessage()
    {
        $data=EmailMessage::first();
        return view('admin.emailmessage',compact('data'));
    }

    public function storeMessage(Request $request)
    {
        DB::beginTransaction();
        try{
            $data=$request->all();
            $data['status']=1;
            $this->email_message->fill($data);
            $this->email_message->save();
            DB::commit();
            $request->session()->flash('success','Message ');
            return redirect()->back();
        }catch(\Exception $ex){

            $request->session()->flash('error',$ex->getMessage());
            return redirect()->back();
        }
    }

    public function updateMessage(Request $request,EmailMessage $id)
    {
        DB::beginTransaction();
        try {
            $id->update([
                'message'=>$request->message,
                'footer_message'=>$request->footer_message,
                'note'=>$request->note ?? null
            ]);
            session()->flash('success', "Updated Successfully");
            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back();
        }
    }
}