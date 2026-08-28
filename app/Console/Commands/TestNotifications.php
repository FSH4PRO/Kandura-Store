<?php

namespace App\Console\Commands;

use App\Events\DesignCreated;
use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Design;
use App\Models\Item;
use App\Models\Order;
use App\Models\Size;
use App\Models\User;
use Illuminate\Console\Command;

class TestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test all notifications by firing events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing notifications...');

        // Ensure we have an admin
        $admin = Admin::first();
        if (!$admin) {
            $this->warn('No admin found. Creating a test admin...');
            $admin = Admin::create([
                'email' => 'testadmin@example.com',
                'password' => bcrypt('password'),
                'super_admin' => true,
            ]);
            $admin->user()->create([
                'name' => ['en' => 'Test Admin', 'ar' => 'مشرف اختبار'],
                'is_active' => true,
            ]);
        }

        // Ensure we have a size
        $size = Size::first();
        if (!$size) {
            $this->warn('No size found. Creating a test size...');
            $size = Size::create([
                'code' => 'M',
                'name' => ['en' => 'Medium', 'ar' => 'وسط'],
                'sort_order' => 1,
            ]);
        }

        // Ensure we have a customer
        $customer = Customer::first();
        if (!$customer) {
            $this->warn('No customer found. Creating a test customer...');
            $customer = Customer::create([
                'phone' => '0912345678',
                'password' => bcrypt('password'),
            ]);
            $customer->user()->create([
                'name' => ['en' => 'Test Customer', 'ar' => 'زبون اختبار'],
                'is_active' => true,
            ]);
        }

        // Test DesignCreated
        $this->info('Testing DesignCreated notification...');
        $design = Design::create([
            'customer_id' => $customer->id,
            'name' => ['en' => 'Test Design', 'ar' => 'تصميم اختبار'],
            'description' => ['en' => 'A test design', 'ar' => 'تصميم للاختبار'],
            'price' => 100.00,
        ]);
        event(new DesignCreated($design));

        // Test OrderCreated
        $this->info('Testing OrderCreated notifications...');
        $order = Order::create([
            'customer_id' => $customer->id,
            'address_id' => null, // Assuming no address needed for test
            'subtotal' => 100.00,
            'discount_total' => 0.00,
            'total' => 100.00,
            'status' => \App\Enums\OrderStatus::Pending,
            'payment_method' => \App\Enums\PaymentMethod::COD,
            'payment_status' => \App\Enums\PaymentStatus::Pending,
        ]);

        // Create an item for the order
        Item::create([
            'order_id' => $order->id,
            'design_id' => $design->id,
            'size_id' => $size->id,
            'quantity' => 1,
            'unit_price' => 100.00,
            'line_total' => 100.00,
        ]);

        event(new OrderCreated($order));

        // Test OrderStatusChanged
        $this->info('Testing OrderStatusChanged notification...');
        $oldStatus = $order->status;
        $order->update(['status' => \App\Enums\OrderStatus::Accepted]);
        event(new OrderStatusChanged($order, $oldStatus->value, $order->status->value));

        $this->info('All notifications tested!');
    }
}
