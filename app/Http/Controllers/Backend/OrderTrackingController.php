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
        $order = Cartorder::with('workflowSteps')->first();
        if (!$order) {
            // Try to get any cartorder
            $order = Cartorder::first();
        }
        if (!$order) {
            return redirect()->route('ordertracking.list')->with('error', 'No orders found. Please create an order first.');
        }
        return view('backend.ordertracking.index', compact('order'));
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
        $order = Cartorder::with(['orderProcessingWorkflow', 'workflowSteps'])->find($id);
        if (!$order) {
            abort(404, 'Order not found');
        }
        return view('backend.ordertracking.index', compact('order'));
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

        // Handle file uploads
        $uploadedFiles = [];
        if ($request->hasFile('mockup_files')) {
            foreach ($request->file('mockup_files') as $file) {
                $path = $file->store('order-workflow-mockups', 'public');
                $uploadedFiles[] = $path;
            }
        }

        // Handle Step 8 specific file uploads
        $deliveryProof = [];
        if ($request->hasFile('delivery_proof')) {
            foreach ($request->file('delivery_proof') as $file) {
                $path = $file->store('order-workflow-mockups', 'public');
                $deliveryProof[] = $path;
            }
        }

        $dispatchProof = [];
        if ($request->hasFile('dispatch_proof')) {
            foreach ($request->file('dispatch_proof') as $file) {
                $path = $file->store('order-workflow-mockups', 'public');
                $dispatchProof[] = $path;
            }
        }

        // Prepare data
        $stepData = [
            'cartorder_id' => $cartorderId,
            'step_number' => $step,
            'mockup_files' => $uploadedFiles,
            'customer_message' => $request->customer_message,
            'approval_status' => $request->approval_status,
            'approved_date' => $request->approved_date ?: null,
            'send_notification' => $request->has('send_notification') ? 1 : 0,
        ];

        // Add Step 8 specific fields
        if ($step == 8) {
            $stepData['delivery_type'] = $request->delivery_type;
            $stepData['delivery_date'] = $request->delivery_date;
            $stepData['delivered_to'] = $request->delivered_to;
            $stepData['delivery_address'] = $request->delivery_address;
            $stepData['delivery_proof'] = $deliveryProof;
            $stepData['tracking_type'] = $request->tracking_type;
            // Store tracking number based on type (awb_number field is used for all tracking types)
            $stepData['awb_number'] = $request->awb_number;
            $stepData['courier_partner'] = $request->courier_partner;
            $stepData['dispatch_date'] = $request->dispatch_date;
            $stepData['dispatch_proof'] = $dispatchProof;
            $stepData['internal_remarks'] = $request->internal_remarks;
        }

        // Add Step 10 specific fields
        if ($step == 10) {
            $stepData['rating'] = $request->rating;
        }

        // For Step 2 and Step 4, always create new entry (multiple entries support)
        if (in_array($step, [2, 4])) {
            $workflowStep = OrderWorkflowStep::create($stepData);
        } else {
            // For other steps, use updateOrCreate
            $workflowStep = OrderWorkflowStep::updateOrCreate(
                [
                    'cartorder_id' => $cartorderId,
                    'step_number' => $step,
                ],
                $stepData
            );
        }

        // Load fresh data with cartorder relationship
        $workflowStep->load('cartorder');

        return response()->json([
            'success' => true,
            'message' => 'Step ' . $step . ' saved successfully',
            'data' => $workflowStep,
        ]);
    }
}
