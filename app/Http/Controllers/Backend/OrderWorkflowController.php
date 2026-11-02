<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Services\OrderWorkflow;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Staff order page: one order, its 10 steps, step actions and timeline.
 */
class OrderWorkflowController extends Controller
{
    public function show(Request $request, $order)
    {
        $order = Order::with(['items', 'stepStates', 'events'])->findOrFail($order);
        $steps = OrderWorkflow::steps();
        $selected = (int) $request->query('step', $order->current_step);
        if (!isset($steps[$selected])) {
            $selected = $order->current_step;
        }
        $isSuper = optional(Admin::find(session('admin_id')))->role === 'superadmin';

        return view('backend.orders.workflow', compact('order', 'steps', 'selected', 'isSuper'));
    }

    public function act(Request $request, OrderWorkflow $workflow, $order, $step, $action)
    {
        $order = Order::with('stepStates')->findOrFail($order);
        try {
            $workflow->run($order, (int) $step, $action, $request);
        } catch (ValidationException $e) {
            return redirect()->route('orders.show', [$order->id, 'step' => $step])
                ->withErrors($e->errors())->withInput()->with('form', $action);
        }

        return redirect()->route('orders.show', [$order->id, 'step' => $step])
            ->with('success', $action === 'note' ? 'Note added.' : (OrderWorkflow::ACTIONS[OrderWorkflow::type($step)][$action]['label'] ?? 'Saved') . ': done.');
    }
}
