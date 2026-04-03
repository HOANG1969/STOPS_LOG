<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\RequestHelper;

class RequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        $priorityConfig = RequestHelper::getPriorityConfig($this->priority);
        $statusConfig = RequestHelper::getStatusConfig($this->status);
        $itemsDisplay = RequestHelper::formatRequestItemsDisplay($this->requestItems);

        return [
            'id' => $this->id,
            'request_code' => $this->request_code ?? '#'.$this->id,
            'user' => [
                'name' => $this->user->name,
                'position' => $this->user->position ?? '',
            ],
            'requester_department' => $this->requester_department,
            'approver' => [
                'name' => $this->approver->name ?? '',
                'department' => $this->approver->department ?? '',
            ],
            'approved_at' => [
                'date' => $this->approved_at ? $this->approved_at->format('d/m/Y') : '',
                'time' => $this->approved_at ? $this->approved_at->format('H:i') : '',
            ],
            'priority' => [
                'class' => $priorityConfig['class'],
                'text' => $priorityConfig['text'],
            ],
            'status' => [
                'class' => $statusConfig['class'],
                'text' => $statusConfig['text'],
            ],
            'items_display' => $itemsDisplay,
            'original' => $this->resource, // Để truy cập các thuộc tính khác nếu cần
        ];
    }
}