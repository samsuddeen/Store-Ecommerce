<?php

namespace App\Http\Controllers\Seller;

use DataTables;
use App\Http\Controllers\Controller;
use App\Models\New_Customer;
use App\Models\Product;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Symfony\Component\Console\Question\Question;

class SellerCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $question_answer = null;

    public function __construct(QuestionAnswer $question_answer)
    {
        $this->question_answer = $question_answer;
    }

    public function index(Request $request)
    {
        $comments = QuestionAnswer::where('seller_id', auth()->guard('seller')->user()->id)->get();
        if ($request->ajax()) {
            $comments = QuestionAnswer::where('parent_id', null)->get();
            foreach ($comments as $key => $data) {
                $data->setAttribute('sn', $key + 1);
            }

            return Datatables::of($comments)
                ->addIndexColumn()
                ->addColumn('id', function ($row) {
                    return $row->sn;
                })
                ->addColumn('from', function ($row) {
                    $customer = New_Customer::where('id', $row->customer_id)->first();
                    return $customer->name ?? null;
                })
                ->addColumn('product_name', function ($row) {
                    $product = Product::where('id', $row->product_id)->first();
                    $route = route('product.details', $product->slug);
                    $html = "<a href='" . $route . "'>" . $product->name . " </a>";
                    return $html ?? null;
                })
                ->addColumn('status', function ($row) {
                    $val = "InActive";
                    if ($row->status == 1) {
                        $val = "Active";
                    }
                    $html = "";
                    $html = "<span class=''>" . $val . "</span>";
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $route = route('comments.edit', $row);
                    $delete = route('comments.delete', $row->id);
                    $btn = "";
                    $btn .= '<a href="' . $route . '" class="btn btn-primary m-1"  >Reply</a>';
                    $btn .= '<a href="' . $delete . '" class="btn btn-danger"  >Delete</a>';
                    return $btn;
                })
                ->rawColumns(['id', 'from', 'product_name', 'status', 'action'])
                ->make(true);
        }
        return view('seller.comments.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = QuestionAnswer::where('id', $id)->first();
        $customer = New_Customer::where('id', $comment->customer_id)->first();
        if ($comment != null) {
            return view('seller.comments.reply', compact('comment', 'customer'));
        } else {
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $question = QuestionAnswer::where('id', $id)->first();
        $input['product_id'] = $question->product_id;
        $input['customer_id'] = $question->customer_id;
        $input['seller_id'] = auth()->guard('seller')->id();
        $input['parent_id'] = $question->id;
        $input['question_answer'] = $request->question_answer;
        $input['status'] = 1;

        try {
            $this->question_answer->fill($input);
            $status = $this->question_answer->save();
            if ($status == 1) {
                $question->status = 1;
                $question->save();
            }

            session()->flash('success', 'successfully replied on the comment');
            return back();
        } catch (\Throwable $th) {
            session()->flash('error', 'OOPs, Please Try Again.');
            return back();
        }
    }

    public function updateAnswer(Request $request, $id)
    {
        $answer = QuestionAnswer::where('parent_id', $id)->first();        
        try {            
            $answer->question_answer = $request->question_answer;        
            $answer->save();
            session()->flash('success', 'Successfully Updated Answer.');
            return back();
        } catch (\Throwable $th) {
            session()->flash('error', 'OOPs, Please try again.');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $comment = QuestionAnswer::where('id', $id)->first();
        try {
            $comment->delete();
            session()->flash('success', 'Successfully deletd comment.');
            return back();
        } catch (\Throwable $th) {
            session()->flash('error', 'OOPs, Please try again.');
            return back();
        }
    }
}
