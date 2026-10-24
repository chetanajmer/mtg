<?php

namespace App\Http\Controllers;

use App\Models\Cartorder;
use App\Models\OrderProcessingWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderProcessingController extends Controller
{
    public function index()
    {
        $workflows = OrderProcessingWorkflow::with('cartorder')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('backend.orderprocessing.index', compact('workflows'));
    }

    public function createWorkflow($cartorderId)
    {
        $cartorder = Cartorder::findOrFail($cartorderId);

        $existingWorkflow = OrderProcessingWorkflow::where('cartorder_id', $cartorderId)->first();
        if ($existingWorkflow) {
            return redirect()->route('orderprocessing.show', $existingWorkflow->id)
                ->with('info', 'Order processing workflow already exists for this order.');
        }

        $workflow = OrderProcessingWorkflow::create([
            'cartorder_id' => $cartorderId,
            'current_step' => 1,
            'step1_received' => false,
            'step2_verification' => false,
            'step3_packing' => false,
            'step4_shipped' => false,
            'step5_delivered' => false,
        ]);

        return redirect()->route('orderprocessing.show', $workflow->id)
            ->with('success', 'Order processing workflow created successfully.');
    }

    public function show($id)
    {
        $workflow = OrderProcessingWorkflow::with('cartorder')->findOrFail($id);
        $cartorder = $workflow->cartorder;

        $steps = [
            1 => [
                'title' => 'Order Received',
                'description' => 'Review and acknowledge the new order',
                'icon' => 'ti-inbox',
                'completed' => $workflow->step1_received,
                'locked' => false,
                'completed_at' => $workflow->step1_completed_at,
            ],
            2 => [
                'title' => 'Order Verification',
                'description' => 'Verify customer and order details',
                'icon' => 'ti-check-box',
                'completed' => $workflow->step2_verification,
                'locked' => !$workflow->step1_received,
                'completed_at' => $workflow->step2_completed_at,
            ],
            3 => [
                'title' => 'Order Packing',
                'description' => 'Pack the order items',
                'icon' => 'ti-package',
                'completed' => $workflow->step3_packing,
                'locked' => !$workflow->step2_verification,
                'completed_at' => $workflow->step3_completed_at,
            ],
            4 => [
                'title' => 'Order Shipped',
                'description' => 'Ship the order with tracking',
                'icon' => 'ti-truck',
                'completed' => $workflow->step4_shipped,
                'locked' => !$workflow->step3_packing,
                'completed_at' => $workflow->step4_completed_at,
            ],
            5 => [
                'title' => 'Order Delivered',
                'description' => 'Confirm order delivery',
                'icon' => 'ti-check',
                'completed' => $workflow->step5_delivered,
                'locked' => !$workflow->step4_shipped,
                'completed_at' => $workflow->step5_completed_at,
            ],
        ];

        return view('backend.orderprocessing.show', compact('workflow', 'cartorder', 'steps'));
    }

    public function completeStep(Request $request, $id, $step)
    {
        $workflow = OrderProcessingWorkflow::findOrFail($id);

        // Validate step transition
        if (!$this->canCompleteStep($workflow, $step)) {
            return redirect()->back()
                ->with('error', 'Cannot complete this step. Previous steps must be completed first.');
        }

        DB::beginTransaction();
        try {
            switch ($step) {
                case 1:
                    $workflow->step1_received = true;
                    $workflow->step1_completed_at = now();
                    $workflow->current_step = 2;
                    break;

                case 2:
                    $workflow->step2_verification = true;
                    $workflow->step2_completed_at = now();
                    $workflow->current_step = 3;
                    break;

                case 3:
                    $workflow->step3_packing = true;
                    $workflow->step3_completed_at = now();
                    $workflow->current_step = 4;
                    break;

                case 4:
                    $request->validate([
                        'courier_name' => 'required|string|max:255',
                        'tracking_number' => 'required|string|max:255',
                        'dispatch_date' => 'required|date',
                    ]);

                    $workflow->step4_shipped = true;
                    $workflow->step4_completed_at = now();
                    $workflow->courier_name = $request->courier_name;
                    $workflow->tracking_number = $request->tracking_number;
                    $workflow->dispatch_date = $request->dispatch_date;
                    $workflow->current_step = 5;
                    break;

                case 5:
                    $request->validate([
                        'delivery_date' => 'required|date',
                    ]);

                    $workflow->step5_delivered = true;
                    $workflow->step5_completed_at = now();
                    $workflow->delivery_date = $request->delivery_date;
                    $workflow->current_step = 6; // Completed
                    $workflow->completed_by = session()->get('userid');
                    break;

                default:
                    return redirect()->back()->with('error', 'Invalid step.');
            }

            $workflow->notes = $request->notes ?? $workflow->notes;
            $workflow->save();

            DB::commit();

            $message = "Step {$step} completed successfully!";
            return redirect()->route('orderprocessing.show', $workflow->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error completing step: ' . $e->getMessage());
        }
    }

    private function canCompleteStep($workflow, $step)
    {
        switch ($step) {
            case 1:
                return !$workflow->step1_received;
            case 2:
                return $workflow->step1_received && !$workflow->step2_verification;
            case 3:
                return $workflow->step2_verification && !$workflow->step3_packing;
            case 4:
                return $workflow->step3_packing && !$workflow->step4_shipped;
            case 5:
                return $workflow->step4_shipped && !$workflow->step5_delivered;
            default:
                return false;
        }
    }

    public function availableOrders()
    {
        $orders = Cartorder::where('orderstatus', '!=', 'Cancelled')
            ->where('ordercomplete', '!=', 'Cancelled')
            ->whereDoesntHave('workflow')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('backend.orderprocessing.available', compact('orders'));
    }

    public function resetStep(Request $request, $id, $step)
    {
        $workflow = OrderProcessingWorkflow::findOrFail($id);

        DB::beginTransaction();
        try {
            switch ($step) {
                case 1:
                    $workflow->step1_received = false;
                    $workflow->step1_completed_at = null;
                    $workflow->current_step = 1;
                    break;

                case 2:
                    $workflow->step2_verification = false;
                    $workflow->step2_completed_at = null;
                    $workflow->current_step = 2;
                    break;

                case 3:
                    $workflow->step3_packing = false;
                    $workflow->step3_completed_at = null;
                    $workflow->current_step = 3;
                    break;

                case 4:
                    $workflow->step4_shipped = false;
                    $workflow->step4_completed_at = null;
                    $workflow->courier_name = null;
                    $workflow->tracking_number = null;
                    $workflow->dispatch_date = null;
                    $workflow->current_step = 4;
                    break;

                case 5:
                    $workflow->step5_delivered = false;
                    $workflow->step5_completed_at = null;
                    $workflow->delivery_date = null;
                    $workflow->current_step = 5;
                    break;

                default:
                    return redirect()->back()->with('error', 'Invalid step.');
            }

            $workflow->save();
            DB::commit();

            return redirect()->route('orderprocessing.show', $workflow->id)
                ->with('success', "Step {$step} has been reset.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error resetting step: ' . $e->getMessage());
        }
    }
}

