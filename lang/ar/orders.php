<?php

return [

  'title' => 'الطلبات',

  'index' => [
    'title'       => 'الطلبات',
    'heading'     => 'الطلبات',
    'subheading'  => 'إدارة جميع طلبات العملاء.',
  ],

  'show' => [
    'title'                  => 'الطلب رقم :id',
    'heading'                => 'الطلب رقم #:id',
    'subheading'             => 'تفاصيل الطلب والعناصر المرتبطة به.',
    'back_to_list'           => 'العودة إلى قائمة الطلبات',
    'summary_title'          => 'ملخص الطلب',
    'order_id'               => 'رقم الطلب',
    'customer'               => 'العميل',
    'subtotal'               => 'المجموع الفرعي',
    'discount'               => 'الخصم',
    'coupon'                 => 'الكوبون',
    'total'                  => 'الإجمالي',
    'status'                 => 'الحالة',
    'created_at'             => 'تاريخ الإنشاء',
    'change_status_title'    => 'تغيير حالة الطلب',
    'status_field'           => 'الحالة الجديدة',
    'change_status_button'   => 'تحديث الحالة',
    'items_title'            => 'عناصر الطلب',
    'items_empty'            => 'لا توجد عناصر في هذا الطلب.',
  ],

  'filters' => [
    'search_label'           => 'بحث',
    'search_placeholder'     => 'البحث في جميع الأعمدة',
    'status_label'           => 'الحالة',
    'status_all'             => 'كل الحالات',
    'total_min'              => 'الحد الأدنى للإجمالي',
    'total_max'              => 'الحد الأعلى للإجمالي',
    'submit'                 => 'تطبيق الفلتر',
    'reset'                  => 'إعادة التعيين',
  ],

  'table' => [
    'customer'        => 'العميل',
    'total'           => 'الإجمالي',
    'discount'        => 'الخصم',
    'status'          => 'الحالة',
    'items_count'     => 'العناصر',
    'payment_method'  => 'طريقة الدفع',
    'payment_status'  => 'حالة الدفع',
    'created_at'      => 'تاريخ الإنشاء',
    'actions'         => 'الإجراءات',
    'view'            => 'عرض',
    'empty'           => 'لا توجد طلبات.',
  ],

  'items' => [
    'design'      => 'التصميم',
    'size'        => 'المقاس',
    'quantity'    => 'الكمية',
    'unit_price'  => 'سعر الوحدة',
    'total_price' => 'السعر الكلي',
    'options'     => 'الخيارات',
  ],

  'statuses' => [
    'pending'   => 'معلّق',
    'accepted'  => 'مقبول',
    'rejected'  => 'مرفوض',
    'canceled'  => 'ملغى',
    'paid'      => 'مكتمل (مدفوع)',
  ],

  'payment_methods' => [
    'cod'    => 'الدفع عند الاستلام',
    'stripe' => 'بطاقة ائتمانية',
    'wallet' => 'المحفظة',
  ],

  'payment_statuses' => [
    'unpaid'   => 'غير مدفوع',
    'pending'  => 'معلّق',
    'paid'     => 'مدفوع',
    'failed'   => 'فاشل',
    'canceled' => 'ملغى',
  ],

  'messages' => [
    'status_updated' => 'تم تحديث حالة الطلب بنجاح.',
  ],

  'payment' => [
    'started' => 'تم بدء الدفع بنجاح.',
  ],
];
