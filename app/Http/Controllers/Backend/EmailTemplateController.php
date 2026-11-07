<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Email Templates: every email the site sends, previewed with sample data,
 * with a test send to the logged-in admin to check the mail settings.
 */
class EmailTemplateController extends Controller
{
    public function index()
    {
        return view('backend.settings.email-templates', [
            'templates' => $this->templates(),
            'mail' => [
                'mailer' => config('mail.default'),
                'from' => config('mail.from.address'),
                'fromName' => config('mail.from.name'),
                'host' => config('mail.mailers.smtp.host'),
                'staff' => staff_email(),
                'queue' => config('queue.default'),
            ],
            'adminEmail' => session('admin_email'),
        ]);
    }

    public function show($key)
    {
        $template = $this->templates()[$key] ?? abort(404);
        return response(view($template['view'], $template['data'])->render())
            ->header('Content-Security-Policy', "script-src 'none'")->header('X-Frame-Options', 'SAMEORIGIN');
    }

    /** Sends the sample to the logged-in admin, to check the mail settings and how it looks in a real inbox. */
    public function test(Request $request, $key)
    {
        $template = $this->templates()[$key] ?? abort(404);
        $to = session('admin_email');
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', 'Your admin login has no valid email address to send the test to.');
        }
        try {
            Mail::send($template['view'], $template['data'], function ($message) use ($to, $template) {
                $message->to($to)->subject('[Test] ' . $template['subject']);
            });
        } catch (\Throwable $e) {
            Log::error('Test email "' . $key . '" failed: ' . $e->getMessage());
            return back()->with('error', 'The test email could not be sent: ' . $e->getMessage());
        }
        $where = config('mail.default') === 'log' ? ' Mail is in log mode, so it was written to storage/logs/laravel.log instead of being sent.' : '';
        return back()->with('success', 'Test "' . $template['name'] . '" sent to ' . $to . '.' . $where);
    }

    /** Every email, its subject as sent, who gets it and sample data. */
    private function templates()
    {
        $order = (object) ['orderid' => 10165, 'id' => 1, 'customer_name' => 'Sara Ahmed'];
        $orderUrl = url('/orders/10165');
        $items = collect([
            (object) ['name' => 'Premium Vacuum Bottle', 'modelno' => 'MO9341', 'quantity' => 100, 'price' => 45.00],
            (object) ['name' => 'A5 Notebook with 16GB USB', 'modelno' => 'MO6233', 'quantity' => 100, 'price' => 60.00],
        ]);
        $enquiry = (object) ['orderid' => 10165, 'name' => 'Sara Ahmed', 'email' => 'sara@example.com', 'phone' => '+971 50 000 0000',
            'company' => 'Example Trading LLC', 'message' => 'Please quote with our logo printed on both items.', 'subtotal' => 10500, 'tax' => 525, 'total' => 11025, 'created_at' => now()];
        $company = config('company.name');

        return [
            'order_update' => ['name' => 'Order update', 'to' => 'Customer', 'when' => 'Staff complete a step or add a customer-visible note on the order page',
                'subject' => 'Update on your order #10165 - ' . $company, 'view' => 'mail.order_tracking_update',
                'data' => ['order' => $order, 'stepTitle' => config('order_steps.2.customer'), 'note' => 'Your mockup (version 1) is ready. Please review it and approve or ask for changes.',
                    'button' => ['label' => 'Review version 1', 'url' => url('/order-response/10165/2/1')], 'orderUrl' => $orderUrl]],
            'invoice' => ['name' => 'Payment invoice', 'to' => 'Customer', 'when' => 'Staff generate the advance or balance invoice',
                'subject' => 'Your advance payment invoice MTG-ADV-2026-0001 - order #10165', 'view' => 'mail.order_tracking_update',
                'data' => ['order' => $order, 'stepTitle' => config('order_steps.3.customer'), 'note' => 'Invoice MTG-ADV-2026-0001 for your advance payment is ready.',
                    'button' => ['label' => 'View invoice', 'url' => $orderUrl], 'orderUrl' => $orderUrl]],
            'quote_customer' => ['name' => 'Quote request received', 'to' => 'Customer', 'when' => 'The customer sends their quote cart',
                'subject' => 'We received your quote request #10165', 'view' => 'mail.quote_request_customer', 'data' => compact('enquiry', 'items')],
            'welcome' => ['name' => 'Welcome', 'to' => 'Customer', 'when' => 'The customer creates an account',
                'subject' => 'Welcome to ' . $company, 'view' => 'mail.registrationmail', 'data' => ['name' => 'Sara']],
            'password_reset' => ['name' => 'Password reset', 'to' => 'Customer', 'when' => 'The customer asks to reset their password',
                'subject' => 'Reset your password - ' . $company, 'view' => 'emails.password_reset_link', 'data' => ['resetUrl' => url('/corporate/reset-password/sample'), 'minutes' => 60]],
            'team_invite' => ['name' => 'Team invitation', 'to' => 'Invited colleague', 'when' => 'An account owner invites a team member',
                'subject' => 'You are invited to Example Trading LLC on ' . $company, 'view' => 'mail.team_invite',
                'data' => ['member' => (object) ['name' => 'Omar'], 'company' => 'Example Trading LLC', 'inviter' => 'Sara Ahmed', 'link' => url('/corporate-employees/team/accept/sample'), 'role' => 'Buyer', 'days' => 7]],
            'abandoned_cart' => ['name' => 'Quote cart reminder', 'to' => 'Customer', 'when' => 'Staff send a reminder from Abandoned Order',
                'subject' => 'You left items in your quote cart - ' . $company, 'view' => 'mail.abandoned_cart_reminder',
                'data' => ['customer' => (object) ['fname' => 'Sara'], 'items' => $items]],
            'contact_customer' => ['name' => 'Contact form reply', 'to' => 'Customer', 'when' => 'The customer sends the Contact Us form',
                'subject' => 'We received your message - ' . $company, 'view' => 'mail.contact_customer', 'data' => ['name' => 'Sara']],
            'quote_staff' => ['name' => 'New quote request', 'to' => 'Staff', 'when' => 'A customer sends their quote cart',
                'subject' => 'New quote request #10165 from Sara Ahmed', 'view' => 'mail.quote_request_staff', 'data' => compact('enquiry', 'items')],
            'customer_response' => ['name' => 'Customer response', 'to' => 'Staff', 'when' => 'A customer approves, asks for changes, rejects or rates',
                'subject' => 'Customer requested changes on order #10165: ' . config('order_steps.2.title'), 'view' => 'mail.order_tracking_update',
                'data' => ['order' => (object) ['orderid' => 10165, 'id' => 1, 'customer_name' => 'team'], 'stepTitle' => config('order_steps.2.title'),
                    'note' => 'Sara Ahmed: Please make the logo a little larger.', 'button' => ['label' => 'Open the order', 'url' => url('/admin/orders/1?step=2')], 'orderUrl' => null]],
            'contact_staff' => ['name' => 'New contact message', 'to' => 'Staff', 'when' => 'A customer sends the Contact Us form',
                'subject' => 'Website contact message from Sara Ahmed', 'view' => 'mail.contact_staff',
                'data' => ['name' => 'Sara Ahmed', 'email' => 'sara@example.com', 'phone' => '+971 50 000 0000', 'msg' => 'Do you deliver to Abu Dhabi within a week?']],
        ];
    }
}
