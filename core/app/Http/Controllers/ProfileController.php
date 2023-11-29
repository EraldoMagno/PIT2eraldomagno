<?php namespace App\Http\Controllers;

use App\Http\Forms\ProfileForm;
use App\Http\Requests\ProfileFormRequest;
use App\Invoicer\Repositories\Contracts\ProfileInterface as Profile;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;

class ProfileController extends Controller {
    use FormBuilderTrait;
    protected $formClass = ProfileForm::class;
    private $profile;
    public function __construct(Profile $profile){
        View::share('heading', trans('app.users'));
        View::share('headingIcon', 'user');
        $this->profile = $profile;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(){
        if (auth()->guard('admin')->user()){
            $user = $this->profile->getById(auth()->guard('admin')->user()->uuid);
            unset($user->password);
            $form = $this->form($this->formClass, [
                'method' => 'POST',
                'url' => route('users.profile'),
                'class' => 'needs-validation row ajax-submit',
                'novalidate',
                'model'=> $user
            ]);
            $heading = trans('app.edit_profile');
            return view('crud.form', compact('heading','form'));
        }
        return redirect('profile');
	}
    /**
     * Update the specified resource in storage.
     * @param ProfileFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ProfileFormRequest $request){
        if (auth()->guard('admin')->user()){
            $user = $this->profile->getById(auth()->guard('admin')->user()->uuid);
            $data =  array(
                      'username'=>$request->username,
                      'name'=>$request->name,
                      'email'=>$request->email,
                      'phone'=> $request->phone,
            );
            if ($request->hasFile('photo')){
                $file = $request->file('photo');
                $filename = strtolower(Str::random(50) . '.' . $file->getClientOriginalExtension());
                $file->move(config('app.uploads_path'), $filename);
                \Image::make(sprintf(config('app.uploads_path').'%s', $filename))->resize(200, 200)->save();
                \File::delete(config('app.uploads_path').$user->photo);
                $data['photo']= $filename;
            }
            if($request->get('password') != ''){
                $data['password']= bcrypt($request->password);
            }
            $this->profile->updateById($user->uuid, $data);
            Flash::success(trans('app.record_updated'));
        }
        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => trans('app.record_updated'),
                'action' => 'reload'
            ]);
        } else {
            return redirect('profile');
        }
	}
}
