<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    QuestionAnswer,
    Product,
    User,
    Province
};

class QuestionAnswerController extends Controller
{
    protected $questionanswer = null;
    protected $product = null;
    protected $user = null;

    public function __construct(QuestionAnswer $questionanswer, Product $product, User $user)
    {
        $this->questionanswer = $questionanswer;
        $this->product = $product;
        $this->user = $user;
    }

    public function question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            // 'user_id'=>'required|exists:users,id',
            'question_answer' => 'required|string',
            'parent_id' => 'nullable|exists:question_answers,id',
            'status' => 'nullable|in:0,1'
        ]);

        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validator->errors()
            ];

            return response()->json($response, 500);
        }

        try {
            $product = Product::where('id', $request->product_id)->first();


            $data = $request->all();



            $data['customer_id'] = \Auth::user()->id;
            $data['seller_id'] = $product->user_id;





            $this->questionanswer->fill($data);
            $status = $this->questionanswer->save();


            if ($status) {
                $response = [
                    'error' => false,
                    'data' => $this->questionanswer,
                    'msg' => 'Question Sent Successfully !!'
                ];

                return response()->json($response, 200);
            } else {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! There Was A Problem While Sending Your Question'
                ];

                return response()->json($response, 500);
            }
        } catch (\Exception $ex) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];

            return response()->json($response, 200);
        }
    }

    public function questionList(Request $request, $product)
    {

        $this->product = $this->product->where('id', $product)->first();



        if (!$this->product) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! Product Not Found'
            ];
            return response()->json($response, 500);
        }

        $user = \Auth::user();


        if ($user) {

            $user_question = $this->questionanswer->where('customer_id', $user->id)->where('product_id', $this->product->id)->where('status', 0)->orderBy('id', 'DESC')->whereNull('parent_id')->with('user', 'answer')->get();

            $question = $this->questionanswer->where('product_id', $this->product->id)->where('status', 1)->orderBy('id', 'DESC')->whereNull('parent_id')->with('user', 'answer.user')->get();

            $q = array_merge($user_question->toArray(), $question->toArray());
        } else {
            $q = $this->questionanswer->where('product_id', $this->product->id)->where('status', 1)->whereNull('parent_id')->with('user', 'answer.user')->get();
            $q->makeHidden(
                [
                    "created_at",
                    "updated_at",
                    'status',
                    "customer_id",
                    "seller_id",
                    "parent_id",
                ]
            );
            foreach ($q as $data) {
                $data->user->makeHidden(
                    [
                        "created_at",
                        "updated_at"
                    ]
                );
                $data->answer->makeHidden(
                    [
                        "customer_id",
                        "seller_id",
                        "parent_id",
                        'status',
                        "created_at",
                        "updated_at",
                    ]
                );
                $data->answer->user->makeHidden(
                    [
                        "created_at",
                        "updated_at"
                    ]
                );
            }
        }

        $response = [
            'error' => false,
            'data' => $q,
            'msg' => 'Question With Answer'
        ];

        return response()->json($response, 200);
    }
}
