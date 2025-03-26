<?php
namespace App\Actions\Seller;

use App\Data\Role\RoleData;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellerAction
{
    protected $request;
    protected $input;
    function __construct(Request $request)
    {
        $this->request = $request;
        $this->input = $request->all();
    }

    public function store()
    {
        $this->input['password'] = Hash::make('123456');
        $user = User::create($this->input);
        $role = (new RoleData())->getRoleByName('seller');
        $user->syncRoles([$role->id]);
        $this->input['user_id'] = $user->id;
        $this->input['created_by'] = auth()->user()->id;
        Seller::create($this->input);
    }
    public function update(Seller $seller)
    {
        $this->input['created_by']=auth()->user()->id;
        $this->input['photo']=$this->request->image ?? null;
        $seller->user->update($this->input);
        $seller->update($this->input);
    }

}