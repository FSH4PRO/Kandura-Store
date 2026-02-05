<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Invoice {{ $invoice->invoice_number }}</title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #222; }
    .header { text-align: center; margin-bottom: 18px; }
    .muted { color: #666; }
    .row { width: 100%; display: block; clear: both; }
    .col { width: 48%; display: inline-block; vertical-align: top; }
    .card { border: 1px solid #ddd; padding: 10px; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
    th { background: #f5f5f5; }
    .right { text-align: right; }
    .center { text-align: center; }
    .total-row td { font-weight: bold; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 11px; border: 1px solid #ccc; }
    .mb-0 { margin-bottom: 0; }
    .mt-8 { margin-top: 8px; }
    .small { font-size: 11px; }
  </style>
</head>

<body>
  @php
    $order = $invoice->order;
    $customer = $order?->customer;
    $user = $customer?->user;
    $address = $order?->address;

    $currency = $order->currency ?? 'USD';

    // Totals (حسب جداولك)
    $subtotal = (float) ($order->subtotal ?? 0);
    $discountTotal = (float) ($order->discount_total ?? 0);
    $total = (float) ($order->total ?? 0);

    $paymentMethod =  ($order->payment_method ?? 'cod');
    $paymentStatus =  ($order->payment_status ?? 'unpaid');
    $status =  ($order->status ?? 'pending');

    // if you have coupon fields:
    $couponCode = $order->coupon?->code ?? ($order->coupon_code ?? null);
    $couponDiscount = (float) ($order->coupon_discount ?? 0);

    // Wallet meta (optional)
    $paymentMeta = is_array($order->payment_meta ?? null) ? $order->payment_meta : [];
  @endphp

  <div class="header">
    <h1 class="mb-0">Kandura Store Invoice</h1>
    <p class="muted small mb-0">Invoice Number: <strong>{{ $invoice->invoice_number }}</strong></p>
    <p class="muted small mb-0">Invoice Date: {{ optional($invoice->created_at)->format('Y-m-d H:i') }}</p>
  </div>

  <div class="row">
    <div class="col card">
      <h3 class="mb-0">Order Details</h3>
      <p class="small muted">Information about the order</p>

      <p><strong>Serial Number:</strong> #{{ $order->serial_number }}</p>
      <p><strong>Order Status:</strong> <span class="badge">{{ $status }}</span></p>
      <p><strong>Created At:</strong> {{ optional($order->created_at)->format('Y-m-d H:i') }}</p>

      <p><strong>Payment Method:</strong> {{ $paymentMethod }}</p>
      <p><strong>Payment Status:</strong> <span class="badge">{{ $paymentStatus }}</span></p>
      @if(!empty($order->payment_reference))
        <p><strong>Payment Reference:</strong> {{ $order->payment_reference }}</p>
      @endif
      @if(!empty($order->paid_at))
        <p><strong>Paid At:</strong> {{ optional($order->paid_at)->format('Y-m-d H:i') }}</p>
      @endif
    </div>


    <div class="col card" >
      <h3 class="mb-0">Customer Details</h3>
      <p class="small muted">Customer & contact info</p>

      <p><strong>Customer ID:</strong> {{ $customer?->id ?? '-' }}</p>
      <p><strong>Name:</strong> {{ $user?->name ?? '-' }}</p>
      <p><strong>Phone:</strong> {{ $customer?->phone ?? '-' }}</p>
     

      <hr>

      <h4 class="mb-0">Shipping Address</h4>
      @if($address)
        <p class="small">
          {{ $address->address_line1 ?? '' }}
          @if(!empty($address->address_line2))<br>{{ $address->address_line2 }}@endif
          @if(!empty($address->city))<br>{{ $address->city }}@endif
          @if(!empty($address->state))<br>{{ $address->state }}@endif
          @if(!empty($address->country))<br>{{ $address->country }}@endif
          @if(!empty($address->postal_code))<br>{{ $address->postal_code }}@endif
        </p>
      @else
        <p class="small muted">No address attached to this order.</p>
      @endif
    </div>
  </div>

  <div class="card">
    <h3 class="mb-0">Order Items</h3>
    <p class="small muted">All items included in this order</p>

    <table>
      <thead>
        <tr>
          <th style="width: 35%;">Design</th>
          <th style="width: 15%;">Size</th>
          <th style="width: 10%;" class="center">Qty</th>
          <th style="width: 15%;" class="right">Unit Price</th>
          <th style="width: 15%;" class="right">Line Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($order->items as $item)
          <tr>
            <td>
              <strong>{{ $item->design?->name ?? ('Design #' . $item->design_id) }}</strong>
              @if(!empty($item->design?->sku))
                <div class="small muted">SKU: {{ $item->design->sku }}</div>
              @endif

              {{-- Options per item --}}
              @if($item->relationLoaded('options') || $item->options)
                @php $opts = $item->options; @endphp
                @if($opts && $opts->count())
                  <div class="small mt-8">
                    <strong>Options:</strong>
                    <ul class="mb-0">
                      @foreach($opts as $opt)
                        <li>
                          {{ $opt->option?->name ?? ('Option #' . $opt->design_option_id) }}
                          @if(!is_null($opt->value))
                            : {{ is_array($opt->value) ? json_encode($opt->value) : $opt->value }}
                          @endif
                        </li>
                      @endforeach
                    </ul>
                  </div>
                @endif
              @endif
            </td>

            <td>{{ $item->size?->name ?? '-' }}</td>
            <td class="center">{{ (int) $item->quantity }}</td>
            <td class="right">{{ number_format((float) $item->unit_price, 2) }} {{ $currency }}</td>
            <td class="right">{{ number_format((float) $item->line_total, 2) }} {{ $currency }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="row">
    <div class="col card">
      <h3 class="mb-0">Payment Meta</h3>
      <p class="small muted">Extra payment information (if available)</p>

      @if(!empty($paymentMeta))
        <table>
          <thead>
            <tr>
              <th>Key</th>
              <th>Value</th>
            </tr>
          </thead>
          <tbody>
            @foreach($paymentMeta as $k => $v)
              <tr>
                <td>{{ $k }}</td>
                <td>
                  @if(is_array($v))
                    <span class="small">{{ json_encode($v) }}</span>
                  @else
                    <span class="small">{{ (string) $v }}</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <p class="small muted">No payment meta available.</p>
      @endif
    </div>

    <div class="col card" style="float:right;">
      <h3 class="mb-0">Totals</h3>
      <p class="small muted">Final calculation</p>

      <table>
        <tbody>
          <tr>
            <td>Subtotal</td>
            <td class="right">{{ number_format($subtotal, 2) }} {{ $currency }}</td>
          </tr>

          @if($couponCode)
            <tr>
              <td>Coupon ({{ $couponCode }})</td>
              <td class="right">-{{ number_format($couponDiscount, 2) }} {{ $currency }}</td>
            </tr>
          @endif

          <tr>
            <td>Discount Total</td>
            <td class="right">-{{ number_format($discountTotal, 2) }} {{ $currency }}</td>
          </tr>

          <tr class="total-row">
            <td>Total</td>
            <td class="right">{{ number_format($total, 2) }} {{ $currency }}</td>
          </tr>
        </tbody>
      </table>

      <p class="small muted mt-8">
        Thank you for your purchase.
      </p>
    </div>
  </div>

</body>
</html>
    