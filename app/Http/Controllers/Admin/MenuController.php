<?php

namespace App\Http\Controllers\Admin;

use App\Data\Menu\MenuContentData;
use App\Data\Menu\MenuData;
use App\Enum\Menu\MenuShowOn;
use App\Enum\Menu\MenuTypeEnum;
use App\Models\Menu;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MenuCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class MenuController extends Controller
{
    protected $menu = null;
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }
    public function index(Request $request)
    {
        return view('admin.menu.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // if ($request->user()->can("create-menu")) {
        //     $menu_categories = MenuCategory::latest()->get();
        //     $parent_menus = Menu::where('parent_id', null)->get();

        // } else {
        //     session()->flash('error', "You can't See this Page.");
        //     return back();
        // }


        $menu = new Menu();
        $data['menu'] = $menu;
        $data['menu_types'] = (new MenuTypeEnum)->getAllValues();
        $data['show_on'] = (new MenuShowOn)->getAllValues();
        $data['menus']  = (new MenuData())->getData();
        return view('admin.menu.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "name" => "required"
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // return $validator->errors();
            return back()->withInput()->withErrors($validator->errors());
        }
        DB::beginTransaction();
        try {
            Menu::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'model' => $request->model ?? null,
                'model_id' => $request->model_id ?? null,
                'banner_image' => $request->banner_image ?? null,
                'content' => $request->content ?? null,
                'external_link' => $request->external_link ?? null,
                'parent_id' => $request->parent_id ?? null,
                'menu_type' => $request->menu_type ?? null,
                'show_on' => $request->show_on ?? null,
                'image' => $request->image ?? null,
                'og_image' => $request->og_image ?? null,
                'meta_title' => $request->meta_title ?? null,
                'meta_keywords' => $request->meta_keywords ?? null,
                'meta_description' => $request->meta_description ?? null,
            ]);
            DB::commit();
            session()->flash('success', 'Successfully Created');
            return redirect()->route('menu.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Menu $menu)
    {
        $data['menu'] = $menu;
        $data['menu_types'] = (new MenuTypeEnum)->getAllValues();
        $data['show_on'] = (new MenuShowOn)->getAllValues();
        $data['menus']  = (new MenuData())->getData();
        return view('admin.menu.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $this->validate($request, [
            "name" => "required"
        ]);
        // dd($menu);
        // dd($request->all());
        DB::beginTransaction();
        try {
            $menu->update([
                'name' => $request->name,
                //    'slug'=>Str::slug($request->name),
                'model' => $request->model ?? $menu->model,
                'model_id' => $request->model_id ?? $menu->model_id,
                'banner_image' => $request->banner_image ?? null,
                'content' => $request->content ?? null,
                'external_link' => $request->external_link ?? null,
                'parent_id' => $request->parent_id ?? null,
                'menu_type' => $request->menu_type ?? null,
                'show_on' => $request->show_on ?? null,
                'image' => $request->image ?? null,
                'og_image' => $request->og_image ?? null,
                'meta_title' => $request->meta_title ?? null,
                'meta_keywords' => $request->meta_keywords ?? null,
                'meta_description' => $request->meta_description ?? null,
                'status' => $request->status ?? 0,
            ]);
            DB::commit();
            session()->flash('success', 'Successfully Updated');
            return redirect()->route('menu.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Menu $menu)
    {
        if ($menu->children()->count()) {
            throw new Exception('Category has children, cannot be deleted');
        }

        try {
            $menu->delete();
            session()->flash('success', 'Succesfully deleted.');
            return redirect()->route('menu.index');
        } catch (\Throwable $th) {
            session()->flash('error', 'something is wrong');
        }
    }

    // public function menuLinkCourse()
    // {
    //     return Course::forMenu()->select('id','slug','title')->get();
    // }

    public function updateMenuOrder(Request $request)
    {
        if ($request->user()->can("edit-menu")) {
            parse_str($request->sort, $arr);
            $order = 1;
            if (isset($arr['menuItem'])) {
                foreach ($arr['menuItem'] as $key => $value) {  //id //parent_id
                    $this->menu->where('id', $key)
                        ->update([
                            'position' => $order,
                            'parent_id' => ($value == "null") ? NULL : $value,
                            'main_child' => ($value == "null") ? 0 : 1,
                        ]);
                    $order++;
                }
            }
            return true;
        } else {
            session()->flash('error', "You can't See this Page.");
            return back();
        }
    }

    private function update_child(Request $request, $id)
    {
        if ($request->user()->can("edit-menu")) {
            $menus = Menu::where('parent_id', $id)->get();
            if ($menus->count() > 1) {
                foreach ($menus as $child) {
                    Menu::where('id', $child->id)->update(['parent_id' => $child->id]);
                    $this->update_child($child->id);
                }
                // $this->forgetMenuCache();
            }
        } else {
            session()->flash('error', "You can't See this Page.");
            return back();
        }
    }

    public function create_menuCategory(Request $request)
    {
        $menuCategory = MenuCategory::create([
            'name' => $request['name'],
            'slug' => Str::slug($request->name),
        ]);
        $menuCategory->save();
    }

    public function getContent(Request $request)
    {
        $contents =  (new MenuContentData($request))->getData();
        return response()->json($contents, 200);
    }
}
