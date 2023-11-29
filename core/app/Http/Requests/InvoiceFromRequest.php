<?php namespace App\Http\Requests;

class InvoiceFromRequest extends Request {

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
            'client_id'     => 'required',
            'currency'      => 'required',
            'invoice_no'    => 'required|unique:invoices,invoice_no',
            'invoice_date'  => 'required',
            'status'        => 'required',
			'items.*.item_name' => 'required',
            'items.*.quantity' => 'required|numeric|gt:0',
            'items.*.price' => 'required|numeric|gt:0',
        ];
        if($id =  $this->invoice){
            $rules['invoice_no']  .= ','.$id.',uuid';
        }
		return $rules;
	}

}
