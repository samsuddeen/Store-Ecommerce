<?php

namespace App\Http\Controllers;

use App\Models\Trash\Trash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TrashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.trash.index");
    }
    public function restore(Request $request)
    {
        $trash = Trash::findOrFail($request->id);
        $model = $trash->model::onlyTrashed()->where('id', $trash->model_id)->first();
        
        DB::beginTransaction();
        try {
            if(!$model)
            {
                $trash->delete();
            }
            else
            {

                $model->restore();
                $trash->delete();
            }
            DB::commit();
            session()->flash('success', 'Successfully Restored');
            return redirect()->route('trash.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Something is wrorng');
            return back()->withInput();
        }
    }
    public function destroy(Trash $trash)
    {
        Schema::disableForeignKeyConstraints();
        DB::beginTransaction();
        try{
            $model = $trash->model::onlyTrashed()->where('id', $trash->model_id)->first();
            if($model)
            {
                $model->forceDelete();
            }
            $trash->delete();
            DB::commit();
            Schema::enableForeignKeyConstraints();
            session()->flash('success', "Trash deleted successfully");
            return redirect()->route('trash.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
