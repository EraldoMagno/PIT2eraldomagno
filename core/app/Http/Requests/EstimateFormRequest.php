<?php namespace App\Http\Requests;

class EstimateFormRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return auth()->guard('admin')->check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
        $rules = [
            'client_id'        => 'required',
            'currency'      => 'required',
            'estimate_date' => 'required',
            'estimate_no'   => 'required|unique:estimates,estimate_no',
            'items.*.item_name' => 'required',
            'items.*.quantity' => 'required|numeric|gt:0',
            'items.*.price' => 'required|numeric|gt:0',
        ];
        if($id =  $this->estimate){
            $rules['estimate_no']  .= ','.$id.',uuid';
        }
		return $rules;
	}

}
