<?php namespace App\Http\Controllers;

use App\Datatables\ExpenseDatatable;
use App\Http\Forms\ExpenseForm;
use App\Http\Requests\ExpenseFormRequest;
use App\Invoicer\Repositories\Contracts\ExpenseInterface as Expense;
use App\Invoicer\Repositories\Contracts\ExpenseCategoryInterface as Category;
use Illuminate\Support\Facades\Response;
use Laracasts\Flash\Flash;
use App\Invoicer\Repositories\Contracts\CurrencyInterface as Currency;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class ExpensesController extends Controller {
    use FormBuilderTrait;
    private $expense,$category,$currency;
    protected $formClass = ExpenseForm::class;
    protected $datatable = ExpenseDatatable::class;
    protected $routes = [
        'index'     => 'expenses.index',
        'create'    => 'expenses.create',
        'show'      => 'expenses.show',
        'edit'      => 'expenses.edit',
        'store'     => 'expenses.store',
        'destroy'   => 'expenses.destroy',
        'update'    => 'expenses.update'
    ];
    public function __construct(Expense $expense,Category $category,Currency $currency){
        $this->expense = $expense;
        $this->category = $category;
        $this->currency  = $currency;
        View::share('heading', trans('app.expenses'));
        View::share('headingIcon', 'credit-card');
        View::share('showBtnCreate', true);
        View::share('btnCreateText', trans('app.new_expense'));
        View::share('createDisplayMode', 'ajax-modal');
        View::share('routes', $this->routes);
        View::share('iconCreate', 'credit-card');
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return View
	 */
	public function index()
	{
        $datatable = App::make($this->datatable);
        return $datatable->render('crud.index');
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return View
	 */
	public function create()
	{
        if(!hasPermission('add_expense', true)) return redirect('expenses');
        $form = $this->form($this->formClass, [
            'method' => 'POST',
            'url' => route($this->routes['store']),
            'class' => 'needs-validation row ajax-submit',
            'novalidate'
        ]);
        $heading = trans('app.add_expense');
        return view('crud.modal',compact('heading','form'));
	}
    /**
     * Store a newly created resource in storage.
     * @param ExpenseFormRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(ExpenseFormRequest $request)
	{
        if($this->expense->create($request->all())){
            Flash::success(trans('app.record_created'));
            return Response::json(array('success'=>true, 'msg' => trans('app.record_created')), 201);
        }
        return Response::json(array('success'=>false, 'msg' => trans('app.record_creation_failed')), 422);
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function edit($id)
	{
        if(!hasPermission('edit_expense', true)) return redirect('expenses');
        $expense = $this->expense->getById($id);
        $form = $this->form($this->formClass, [
            'method' => 'PATCH',
            'url' => route($this->routes['update'],$expense->uuid),
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> $expense
        ]);
        $heading = trans('app.edit_expense');
        return view('crud.modal', compact('heading','form'));
	}
    /**
     *  Update the specified resource in storage.
     * @param ExpenseFormRequest $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(ExpenseFormRequest $request, $id)
	{
        if($this->expense->updateById($id,$request->all())){
            Flash::success(trans('app.record_updated'));
            return Response::json(array('success'=>true, 'msg' => trans('app.record_updated')), 201);
        }
        return Response::json(array('success'=>false, 'msg' => trans('app.record_update_failed')), 422);
	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        if(!hasPermission('delete_expense', true)) return redirect('expenses');
        if($this->expense->deleteById($id))
            Flash::success(trans('app.record_deleted'));
        else
            Flash::error(trans('app.record_deletion_failed'));

        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => trans('app.record_deletion_failed'),
                'action' => 'refresh_datatable'
            ]);
        } else {
            return redirect(route($this->routes['index']));
        }    
	}
}
