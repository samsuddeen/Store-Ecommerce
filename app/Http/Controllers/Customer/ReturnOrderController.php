<?php
namespace App\Http\Controllers\Customer;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Customer\ReturnOrder;

class ReturnOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $returnOrder = ReturnOrder::paginate(20);
        return view("admin.ReturnOrder.index",compact("returnOrder"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $returnOrder = new ReturnOrder();
        return view("admin.ReturnOrder.form",compact("returnOrder"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request;        
        DB::beginTransaction();
        try{
            ReturnOrder::create($request->all());
            request()->session()->flash('success',"new ReturnOrder created successfully");
            DB::commit();
            return redirect()->route('ReturnOrder.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer\ReturnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function show(ReturnOrder $returnOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer\ReturnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(ReturnOrder $returnOrder)
    {
        return view("admin.ReturnOrder.form",compact("returnOrder"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer\ReturnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReturnOrder $returnOrder)
    {
         DB::beginTransaction();
          try{
           $returnOrder->update($request->all());
            request()->session()->flash('success',"new ReturnOrder created successfully");
            DB::commit();
            return redirect()->route('ReturnOrder.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer\ReturnOrder  $returnOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReturnOrder $returnOrder)
    {
        try{
             $returnOrder->delete();
              request()->session()->flash('success',"ReturnOrder deleted successfully");
            return redirect()->route('ReturnOrder.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
