@extends('web_frontend.layouts.app')

@section('title', 'My Orders | More Than Giftz')
@section('meta')
  <meta name="description" content="View and manage your corporate orders.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/orders.css') }}">
@endsection

@section('content')
@php
  $items_array = $items_array ?? collect();
@endphp

<div class="dashboard-wrapper">

<aside class="sidebar">
  <button class="company"><i class="bi bi-buildings"></i><span><strong>{{ session()->get('fullname') ?? session()->get('username') ?? 'User' }}</strong><small>Switch Company <i class="bi bi-arrow-right"></i></small></span></button>
  <nav>
    <a href="{{ route('corporate.portal') }}"><i class="bi bi-gift"></i>Dashboard</a>
    <a class="active" href="/orders"><i class="bi bi-cart3"></i>Orders</a>
    <a href="#addresses"><i class="bi bi-geo-alt"></i>Addresses</a>
    <a href="#branding"><i class="bi bi-image"></i>Branding Assets</a>
    <a href="/collections-all"><i class="bi bi-bookmark"></i>Saved Collections</a>
    <a href="#employees"><i class="bi bi-people-fill"></i>Employees</a>
    <a href="#reports"><i class="bi bi-bar-chart-line"></i>Reports</a>
    <a href="/corporate-account-settings"><i class="bi bi-gear"></i>Account Settings</a>
    <a href="{{ route('corporate.logout') }}" style="color: #dc3545;"><i class="bi bi-box-arrow-right"></i>Logout</a>
  </nav>
  <div class="help"><i class="bi bi-headset"></i><div><strong>Need Help?</strong><p>Your dedicated account manager is here.</p></div><button class="outline full" data-toast="Contact request started.">Contact Us <i class="bi bi-arrow-right"></i></button></div>
</aside>

<main class="content">
  <section class="hero" style="min-height: 200px;">
    <div style="width: 100%; text-align: center;">
      <h1>My Orders</h1>
      <p>View and track all your corporate orders in one place.</p>
    </div>
  </section>

  <section class="orders-section">
    @if($items_array->count() > 0)
      <div class="orders-table">
        <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
          <thead style="background: #f9fafb;">
            <tr>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Order ID</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Product</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Order Date</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Total</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Status</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($items_array as $order)
              <tr style="border-bottom: 1px solid #e5e7eb; transition: background 0.3s;">
                <td style="padding: 16px; color: #4b5563;">
                  <strong>#MTG-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong>
                </td>
                <td style="padding: 16px; color: #4b5563;">
                  {{ $order->name ?? 'Order Item' }}
                </td>
                <td style="padding: 16px; color: #4b5563;">
                  {{ $order->created_at ? $order->created_at->format('d M Y') : 'N/A' }}
                </td>
                <td style="padding: 16px; color: #4b5563; font-weight: 600;">
                  AED {{ number_format($order->ordertotal ?? 0, 2) }}
                </td>
                <td style="padding: 16px;">
                  @php
                    $status = $order->orderstatus ?? 'Pending';
                    $statusColors = [
                      'Confirmed' => '#10b981',
                      'In Transit' => '#3b82f6',
                      'Delivered' => '#10b981',
                      'Cancelled' => '#ef4444',
                      'Pending' => '#f59e0b'
                    ];
                    $statusColor = isset($statusColors[$status]) ? $statusColors[$status] : '#6b7280';
                  @endphp
                  <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: {{ $statusColor }}20; color: {{ $statusColor }};">
                    {{ $status }}
                  </span>
                </td>
                <td style="padding: 16px;">
                  <a href="/order-details/{{ $order->orderid }}" style="display: inline-block; padding: 8px 16px; background: #15171a; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600; transition: background 0.3s;">
                    View Details
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <i class="bi bi-cart-x" style="font-size: 64px; color: #d1d5db; margin-bottom: 20px;"></i>
        <h2 style="color: #374151; margin-bottom: 10px;">No Orders Yet</h2>
        <p style="color: #6b7280; margin-bottom: 30px;">You haven't placed any orders yet. Start exploring our products!</p>
        <a href="{{ url('/') }}" style="display: inline-block; padding: 12px 24px; background: #15171a; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; transition: background 0.3s;">
          Browse Products
        </a>
      </div>
    @endif
  </section>
</main>
</div>
@endsection

