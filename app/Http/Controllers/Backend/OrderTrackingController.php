<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cartorder;
use App\Models\OrderProcessingWorkflow;
use App\Models\OrderWorkflowStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderTrackingController extends Controller
{
    public function index()
    {
        // Order tracking is opened per order from the Store Enquiries page
        return redirect()->route('orders.index');
    }

    public function list(Request $request)
    {
        $query = OrderProcessingWorkflow::with('cartorder');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('cartorder', function ($q) use ($search) {
                $q->where('orderid', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $workflows = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('backend.ordertracking.list', compact('workflows'));
    }

    public function show($id)
    {
        $order = Cartorder::with('workflowSteps')->find($id);
        if (!$order) {
            abort(404, 'Order not found');
        }
        // Order tracking now lives on the order page; old links land there
        if ($order->order_id) {
            return redirect()->route('orders.show', $order->order_id);
        }
        $stepStatuses = self::stepStatuses($order->id);
        return view('backend.ordertracking.index', compact('order', 'stepStatuses'));
    }

    public function saveStep(Request $request, $step)
    {
        // Handle GET request to retrieve saved data
        if ($request->isMethod('GET')) {
            $cartorderId = $request->query('cartorder_id');
            if (!$cartorderId) {
                return response()->json(['success' => false, 'message' => 'cartorder_id is required'], 400);
            }

            // For Step 2 and Step 4, return all entries (multiple entries support)
            if (in_array($step, [2, 4])) {
                $workflowSteps = OrderWorkflowStep::where('cartorder_id', $cartorderId)
                    ->where('step_number', $step)
                    ->orderBy('created_at', 'desc')
                    ->get();

                if ($workflowSteps->count() > 0) {
                    return response()->json([
                        'success' => true,
                        'data' => $workflowSteps,
                        'multiple' => true,
                    ]);
                }
            } else {
                // For other steps, return single entry
                $workflowStep = OrderWorkflowStep::where('cartorder_id', $cartorderId)
                    ->where('step_number', $step)
                    ->first();

                if ($workflowStep) {
                    return response()->json([
                        'success' => true,
                        'data' => $workflowStep,
                        'multiple' => false,
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'No saved data found for this step',
            ]);
        }

        // Handle POST request to save data
        // Manual validation with detailed error messages
        $errors = [];

        if (!$request->has('cartorder_id') || empty($request->cartorder_id)) {
            $errors[] = 'cartorder_id is required';
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $errors),
                'errors' => $errors
            ], 422);
        }

        $cartorderId = $request->cartorder_id;
        $order = Cartorder::find($cartorderId);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $store = function ($field) use ($request) {
            $paths = [];
            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    $paths[] = $file->store('order-workflow-mockups', 'public');
                }
            }
            return $paths;
        };
        $newMockups = $store('mockup_files');
        $newDelivery = $store('delivery_proof');
        $newDispatch = $store('dispatch_proof');

        // Only change the fields the form actually sent. The status dropdown
        // sends just approval_status; it must not erase the rest of the step.
        $fields = ['customer_message', 'approval_status', 'approved_date'];
        if ($step == 8) {
            $fields = array_merge($fields, ['delivery_type', 'delivery_date', 'delivered_to', 'delivery_address',
                'tracking_type', 'awb_number', 'courier_partner', 'dispatch_date', 'internal_remarks']);
        }
        if ($step == 10) {
            $fields[] = 'rating';
        }
        $stepData = [];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $value = $request->input($field);
                $stepData[$field] = ($value === '' && in_array($field, ['approved_date', 'delivery_date', 'dispatch_date'], true)) ? null : $value;
            }
        }
        $statusOnly = array_keys($stepData) === ['approval_status'] && !$newMockups && !$newDelivery && !$newDispatch;
        if (!$statusOnly) {
            $stepData['send_notification'] = $request->has('send_notification') ? 1 : 0;
        }

        $existing = in_array($step, [2, 4])
            // Steps 2 and 4 keep a history of rounds; a status change updates the latest round
            ? ($statusOnly ? OrderWorkflowStep::where('cartorder_id', $cartorderId)->where('step_number', $step)->latest('id')->first() : null)
            : OrderWorkflowStep::where('cartorder_id', $cartorderId)->where('step_number', $step)->first();

        $workflowStep = $existing ?: new OrderWorkflowStep(['cartorder_id' => $cartorderId, 'step_number' => $step]);
        $workflowStep->fill($stepData);
        // New uploads are added to the files already saved, never replace them
        foreach (['mockup_files' => $newMockups, 'delivery_proof' => $newDelivery, 'dispatch_proof' => $newDispatch] as $field => $paths) {
            if ($paths || !$workflowStep->exists) {
                $workflowStep->{$field} = array_values(array_merge((array) ($workflowStep->{$field} ?? []), $paths));
            }
        }
        $workflowStep->save();

        $this->syncOrderStatus($order);
        if (!$statusOnly && $request->has('send_notification')) {
            $this->notifyCustomer($order, (int) $step, $request->customer_message);
        }

        $workflowStep->load('cartorder');

        return response()->json([
            'success' => true,
            'message' => 'Step ' . $step . ' saved successfully',
            'data' => $workflowStep,
        ]);
    }

    public const STEP_TITLES = [
        1 => 'Enquiry Received', 2 => 'Mockup Approval', 3 => 'Advance Payment', 4 => 'Sample Approval',
        5 => 'Mass Production', 6 => 'Quality Check & Packing', 7 => 'Balance Payment', 8 => 'Delivered',
        9 => 'Completed', 10 => 'Completed',
    ];

    /**
     * Latest status of each of the 10 steps for an order ('' when not started).
     */
    public static function stepStatuses($cartorderId)
    {
        $statuses = array_fill(1, 10, '');
        OrderWorkflowStep::where('cartorder_id', $cartorderId)->orderBy('id')->get()
            ->each(function ($row) use (&$statuses) {
                if (isset($statuses[$row->step_number])) {
                    $statuses[$row->step_number] = (string) ($row->approval_status ?: 'saved');
                }
            });
        return $statuses;
    }

    /**
     * Store Enquiries / "My Orders" status = the furthest step marked done.
     * Applied to every row of the same order number.
     */
    private function syncOrderStatus(Cartorder $order)
    {
        $done = collect(self::stepStatuses($order->id))->filter(function ($status) {
            return in_array(strtolower($status), ['completed', 'approved', 'delivered'], true);
        })->keys()->max();
        if (!$done) {
            return;
        }
        $label = self::STEP_TITLES[$done];
        $rows = $order->orderid ? Cartorder::where('orderid', $order->orderid) : Cartorder::where('id', $order->id);
        $rows->update(['orderstatus' => $label]);
    }

    private function notifyCustomer(Cartorder $order, $step, $message)
    {
        if (empty($order->customer_email)) {
            return;
        }
        try {
            \Mail::send('mail.order_tracking_update', [
                'order' => $order,
                'stepTitle' => self::STEP_TITLES[$step] ?? ('Step ' . $step),
                'note' => $message,
            ], function ($mail) use ($order) {
                $mail->to($order->customer_email)->subject('Update on your order #' . ($order->orderid ?: $order->id) . ' - More Than Giftz');
            });
        } catch (\Throwable $e) {
            \Log::error('Order tracking email failed for order ' . $order->id . ': ' . $e->getMessage());
        }
    }
}
