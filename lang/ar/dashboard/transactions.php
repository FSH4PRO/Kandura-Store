<?php

return [

  'title' => 'المعاملات - Kandura Store',

  'detail' => [
    'title' => 'تفاصيل المعاملة',
    'back_to_list' => 'العودة إلى المعاملات',
    'transaction_info' => 'معلومات المعاملة',
    'customer_info' => 'معلومات العميل',
    'id' => 'رقم المعاملة',
    'type' => 'النوع',
    'amount' => 'المبلغ',
    'status' => 'الحالة',
    'created_at' => 'تاريخ الإنشاء',
    'updated_at' => 'تاريخ التحديث',
    'description' => 'الوصف',
    'reference_id' => 'رقم المرجع',
    'wallet_balance' => 'رصيد المحفظة',
    'customer_since' => 'عميل منذ',
  ],

  'filters' => [
    'title' => 'الفلاتر',
    'type' => 'نوع المعاملة',
    'all_types' => 'جميع الأنواع',
    'credit' => 'إيداع',
    'debit' => 'سحب',
    'status' => 'الحالة',
    'all_statuses' => 'جميع الحالات',
    'pending' => 'معلق',
    'completed' => 'مكتمل',
    'failed' => 'فاشل',
    'date_from' => 'من تاريخ',
    'date_to' => 'إلى تاريخ',
    'apply' => 'تطبيق الفلاتر',
    'clear' => 'مسح الفلاتر',
  ],

  'table' => [
    'id' => 'الرقم',
    'customer' => 'العميل',
    'type' => 'النوع',
    'amount' => 'المبلغ',
    'status' => 'الحالة',
    'date' => 'التاريخ',
    'actions' => 'الإجراءات',
  ],

  'types' => [
    'credit' => 'إيداع',
    'debit' => 'سحب',
  ],

  'statuses' => [
    'pending' => 'معلق',
    'completed' => 'مكتمل',
    'failed' => 'فاشل',
  ],

  'actions' => [
    'view' => 'عرض',
  ],

  'validation' => [
    'type_invalid' => 'نوع المعاملة المحدد غير صحيح.',
    'status_invalid' => 'الحالة المحددة غير صحيحة.',
    'date_from_invalid' => 'تاريخ البداية يجب أن يكون قبل أو يساوي تاريخ النهاية.',
    'date_to_invalid' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',
    'search_too_long' => 'استعلام البحث طويل جداً.',
  ],

];
