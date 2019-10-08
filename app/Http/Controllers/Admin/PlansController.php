<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Plan;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plans = Plan::all();
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $priceTypes = $this->priceTypes();
        return view('admin.plans.create', compact('priceTypes'));
    }

    protected function priceTypes(){
        return ['ETH', 'FELTA'];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());
        $plan = new Plan([
            'price' => $request->get('price'),
            'price_type' => $request->get('price_type'),
            'label' => $request->get('label'),
            'user_comm' => $request->get('user_comm'),
            'company_comm' => $request->get('company_comm'),
            'bonus' => $request->get('bonus')
        ]);
        $plan->save();
        return redirect(route('admin.plans.index'))->with('success', 'Plan has been created');
    }

    public function rules()
    {
        return [
            'price' => 'required|numeric',
            'price_type' => 'required',
            'label' => 'required',
            'user_comm' => 'required',
            'company_comm' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_comm.required' => "The investor profit field is required.",
            'company_comm.required' => "The company profit field is required.",
        ];
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
        $plan = Plan::findOrFail($id);
        $priceTypes = $this->priceTypes();
        return view('admin.plans.edit', compact('plan', 'priceTypes'));
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
        $plan = Plan::findOrFail($id);
        $request->validate($this->rules(), $this->messages());
        $plan->fill($request->all())->save();
        return redirect(route('admin.plans.index'))->with('success', 'Plan has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();
        return redirect(route('admin.plans.index'))->with('success', 'Plan has been deleted');
    }
}
