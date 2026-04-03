<?php

namespace App\Exports;

use App\Models\RequestItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SupplyItemsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->items;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'STT',
            'Bộ phận',
            'Người yêu cầu',
            'Mã văn phòng phẩm',
            'Tên văn phòng phẩm',
            'Đơn vị tính',
            'Quy cách',
            'Số lượng yêu cầu',
            'Tồn kho hiện tại',
            'Ngày yêu cầu',
            'Kỳ đăng ký',
            'Trạng thái',
        ];
    }

    /**
     * @param mixed $item
     * @return array
     */
    public function map($item): array
    {
        static $index = 0;
        $index++;

        $statusLabels = [
            'draft' => 'Nháp',
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'rejected' => 'Từ chối',
            'approved_by_head' => 'Trưởng phòng duyệt',
            'rejected_by_head' => 'Trưởng phòng từ chối',
            'checked_by_tchc' => 'TCHC đã kiểm tra',
            'approved_by_tchc' => 'TCHC đã duyệt',
            'rejected_by_tchc' => 'TCHC từ chối',
        ];

        return [
            $index,
            $item->supplyRequest->requester_department ?? '',
            $item->supplyRequest->user->full_name ?? $item->supplyRequest->user->name ?? '',
            $item->officeSupply->code ?? '',
            $item->officeSupply->name ?? '',
            $item->officeSupply->unit ?? '',
            $item->officeSupply->specifications ?? '',
            $item->quantity,
            $item->officeSupply->current_stock ?? 0,
            $item->supplyRequest->created_at ? $item->supplyRequest->created_at->format('d/m/Y') : '',
            $item->supplyRequest->created_at ? 'Tháng ' . $item->supplyRequest->created_at->format('n') . '/' . $item->supplyRequest->created_at->format('Y') : '',
            $statusLabels[$item->supplyRequest->status] ?? $item->supplyRequest->status,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as header
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}
