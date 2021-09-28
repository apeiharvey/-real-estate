<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class MemberController extends Controller implements CreatesNewUsers
{

    use PasswordValidationRules;

    public function index()
    {

        $vendor_id = auth()->user()->user_ref_id;

        $member_list = User::where('user_type', 'VENDOR')
            ->where('user_ref_id', $vendor_id)
            ->where('is_active', true)
            ->orderBy('created_at','asc')
            ->get();

        $data = array(
            "member_list" => $member_list
        );
        return view('pages.member.index',$data);
    }

    public function add(){
        if(auth()->user()->id===auth()->user()->vendor->created_by){
            return view('pages.member.add');
        }else{
            abort(404);
        }
    }

    public function save(Request $request){
        if(auth()->user()->id===auth()->user()->vendor->created_by){
            $param = $request->all();
            return view('pages.member.add', $this->create($param));
        }else{
            abort(404);
        }
    }

    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'terms' => ['required']
        ])->validate();

         $user_saved = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'user_type' => config('app.type'),
            'password' => Hash::make($input['password']),
            'user_ref_id' => auth()->user()->user_ref_id,
            'created_by' => auth()->user()->id,
        ]);

        return array(
            'success' => true,
            'last_id' => $user_saved->id
        );
    }

    public function delete(Request $request, $hash){
        if($request->ajax()) {
            if(auth()->user()->id===auth()->user()->vendor->created_by){

                $result_status = "SUCCESS";
                $result_message = "Member was released successfully!";

                $id = Crypt::decryptString($hash);

                $updated = User::where("id", $id)
                    ->update([
                        "updated_by" => auth()->user()->id,
                        "user_ref_id" => null
                    ]);

                if(!$updated){

                    $result_status = "ERROR";
                    $result_message = "An error occurred while releasing member!";

                }

                $result = array(
                    "result_status" => $result_status,
                    "result_message" => $result_message
                );

                return json_encode($result);
            }else{
                abort(404);
            }
        }
    }
}
