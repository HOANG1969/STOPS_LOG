<?php

namespace App\Helpers;

class RequestHelper
{
    /**
     * Get priority configuration
     */
    public static function getPriorityConfig($priority)
    {
        $priorityConfig = [
            'low' => ['class' => 'bg-light text-dark', 'text' => 'Thấp'],
            'Low' => ['class' => 'bg-light text-dark', 'text' => 'Thấp'],
            'normal' => ['class' => 'bg-primary', 'text' => 'Bình thường'],
            'Normal' => ['class' => 'bg-primary', 'text' => 'Bình thường'],
            'high' => ['class' => 'bg-warning text-dark', 'text' => 'Cao'],
            'High' => ['class' => 'bg-warning text-dark', 'text' => 'Cao'],
            'urgent' => ['class' => 'bg-danger', 'text' => 'Khẩn cấp'],
            'Urgent' => ['class' => 'bg-danger', 'text' => 'Khẩn cấp']
        ];

        return $priorityConfig[$priority] ?? $priorityConfig['normal'];
    }

    /**
     * Get status configuration
     */
    public static function getStatusConfig($status)
    {
        $statusConfig = [
            'approved' => ['class' => 'bg-warning text-dark', 'text' => 'Chờ kiểm tra'],
            'tchc_checked' => ['class' => 'bg-info', 'text' => 'Đã kiểm tra'],
            'tchc_approved' => ['class' => 'bg-success', 'text' => 'Đã phê duyệt'],
            'tchc_rejected' => ['class' => 'bg-danger', 'text' => 'Đã từ chối']
        ];

        return $statusConfig[$status] ?? $statusConfig['approved'];
    }

    /**
     * Format request items for display (lấy 2 items đầu)
     */
    public static function formatRequestItemsDisplay($requestItems)
    {
        $count = $requestItems->count();
        $displayItems = $requestItems->take(2);
        $hasMore = $count > 2;
        
        return [
            'items' => $displayItems,
            'has_more' => $hasMore,
            'more_count' => $hasMore ? $count - 2 : 0,
            'total_count' => $count
        ];
    }
}