<?php

namespace App\Exports;

use App\Models\SupplyRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SupplyRequestsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $requests;

    public function __construct($requests)
    {
        $this->requests = $requests;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->requests;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'STT',
            'Mã yêu cầu',
            'Bộ phận',
            'Người yêu cầu',
            'Chức vụ',
            'Số điện thoại',
            'Kỳ đăng ký',
            'Trạng thái',
            'Trạng thái TCHC',
            'Người kiểm tra TCHC',
            'Người phê duyệt TCHC',
            'Tổng số mặt hàng',
            'Ngày tạo',
            'Ngày cập nhật',
        ];
    }

    /**
     * @param mixed $request
     * @return array
     */
    public function map($request): array
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

        $tchcStatusLabels = [
            'pending' => 'Chờ kiểm tra',
            'checked' => 'Đã kiểm tra',
            'approved' => 'Đã duyệt',
            'rejected' => 'Từ chối',
        ];

        return [
            $index,
            $request->request_number,
            $request->requester_department,
            $request->user->full_name ?? $request->user->name,
            $request->requester_position,
            $request->user->phone,
            'Tháng ' . $request->created_at->format('n') . '/' . $request->created_at->format('Y'),
            $statusLabels[$request->status] ?? $request->status,
            $tchcStatusLabels[$request->tchc_status] ?? $request->tchc_status,
            $request->tchcChecker->full_name ?? '',
            $request->tchcManager->full_name ?? '',
            $request->requestItems->count(),
            $request->created_at->format('d/m/Y H:i'),
            $request->updated_at->format('d/m/Y H:i'),
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
