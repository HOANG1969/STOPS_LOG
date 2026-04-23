<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Access denied. Admin role required.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', 'like', '%' . $request->department . '%');
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by ca/kip (phone field)
        if ($request->filled('phone')) {
            $query->where('phone', $request->phone);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active' ? 1 : 0);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Append query parameters to pagination links
        $users->appends($request->query());

        // Get unique departments for filter dropdown
        $departments = User::distinct()->whereNotNull('department')
                          ->where('department', '!=', '')
                          ->pluck('department')
                          ->sort()
                          ->values();

        return view('admin.users.index', compact('users', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Show import users form.
     */
    public function showImportForm()
    {
        return view('admin.users.import');
    }

    /**
     * Import users from Excel/CSV.
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx,xls|max:4096',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        try {
            $rows = $extension === 'csv'
                ? $this->parseCsvFile($file)
                : $this->parseExcelFile($file);

            $result = $this->processUserImportData($rows);

            return redirect()->back()->with('success',
                "Import hoàn tất: thêm mới {$result['created']} nhân sự, " .
                "bỏ qua {$result['skipped']} dòng, " .
                "lỗi {$result['errors']} dòng."
            );
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Có lỗi khi import: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template for user import.
     */
    public function downloadImportTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'ho_ten',
            'email',
            'mat_khau',
            'xac_nhan_mat_khau',
            'vai_tro',
            'ca_kip',
            'phong_ban',
            'chuc_vu',
            'trang_thai',
        ];

        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray([
            'Thái Việt Hùng',
            'hung.tv2@pvgas.com.vn',
            '12345678',
            '12345678',
            'employee',
            'VH01',
            'KCTV',
            'Công nhân VH',
            '1',
        ], null, 'A2');

        $sheet->fromArray([
            'Nguyễn Văn A',
            'vana@pvgas.com.vn',
            '12345678',
            '12345678',
            'approver',
            'HTSX',
            'KTVH',
            'Trưởng ca',
            '1',
        ], null, 'A3');

        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $fileName = 'mau_import_nhan_su.xlsx';
        $tempPath = storage_path('app/' . $fileName);
        (new Xlsx($spreadsheet))->save($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,approver,employee,tchc_checker,tchc_manager',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'phone'=> 'required|in:VH01,VH02,VH03,VH04,VH05,HTSX',
            // 'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'department' => $request->department,
            'position' => $request->position,
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Nhân sự đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,approver,employee,tchc_checker,tchc_manager',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'department' => $request->department,
            'position' => $request->position,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('users.index')
            ->with('success', 'Thông tin nhân sự đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting the currently logged-in user
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Không thể xóa tài khoản đang đăng nhập!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Nhân sự đã được xóa thành công!');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deactivating the currently logged-in user
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Không thể vô hiệu hóa tài khoản đang đăng nhập!');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'kích hoạt' : 'vô hiệu hóa';
        return redirect()->route('users.index')
            ->with('success', "Đã {$status} tài khoản thành công!");
    }

    /**
     * Get user statistics for dashboard
     */
    public function getStats()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();

        return response()->json([
            'total' => $totalUsers,
            'active' => $activeUsers,
            'inactive' => $inactiveUsers
        ]);
    }

    private function parseCsvFile($file): array
    {
        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setHeaderOffset(0);

        $records = [];
        foreach ($csv as $record) {
            $normalized = [];
            foreach ($record as $key => $value) {
                $normalized[$this->normalizeColumnName($key)] = is_string($value) ? trim($value) : $value;
            }
            $records[] = $normalized;
        }

        return $records;
    }

    private function parseExcelFile($file): array
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        if (count($rows) < 2) {
            return [];
        }

        $headers = array_map(fn ($header) => $this->normalizeColumnName((string) $header), $rows[0]);

        $records = [];
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (count(array_filter($row, fn ($value) => filled($value))) === 0) {
                continue;
            }

            $record = [];
            foreach ($headers as $index => $header) {
                $record[$header] = isset($row[$index]) && is_string($row[$index])
                    ? trim($row[$index])
                    : ($row[$index] ?? null);
            }
            $records[] = $record;
        }

        return $records;
    }

    private function processUserImportData(array $rows): array
    {
        $created = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($rows as $index => $row) {
            try {
                $data = $this->mapUserColumns($row);

                if (blank($data['name']) || blank($data['email'])) {
                    $skipped++;
                    continue;
                }

                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors++;
                    continue;
                }

                if (!in_array($data['role'], ['admin', 'approver', 'employee', 'tchc_checker', 'tchc_manager'], true)) {
                    $errors++;
                    continue;
                }

                if (filled($data['password_confirmation']) && $data['password'] !== $data['password_confirmation']) {
                    $errors++;
                    continue;
                }

                if (User::where('email', $data['email'])->exists()) {
                    $skipped++;
                    continue;
                }

                User::create([
                    'name' => $data['name'],
                    'full_name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password'] ?: '12345678'),
                    'role' => $data['role'],
                    'department' => $data['department'],
                    'position' => $data['position'],
                    'phone' => $data['phone'],
                    'is_active' => $data['is_active'],
                ]);

                $created++;
            } catch (\Throwable $e) {
                $errors++;
                \Log::error('User import row failed', [
                    'row_number' => $index + 2,
                    'row' => $row,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return [
            'created' => $created,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    private function mapUserColumns(array $row): array
    {
        $name = $this->pickColumn($row, ['ho_ten', 'hova_ten', 'name', 'full_name', 'ho_va_ten']);
        $email = $this->pickColumn($row, ['email', 'mail']);
        $password = $this->pickColumn($row, ['mat_khau', 'password', 'mk']);
        $passwordConfirmation = $this->pickColumn($row, ['xac_nhan_mat_khau', 'password_confirmation', 'confirm_password']);
        $role = $this->normalizeRole((string) $this->pickColumn($row, ['vai_tro', 'role']));
        $phone = $this->pickColumn($row, ['ca_kip', 'ca', 'kip', 'phone']);
        $department = $this->pickColumn($row, ['phong_ban', 'department']);
        $position = $this->pickColumn($row, ['chuc_vu', 'position']);
        $isActiveRaw = $this->pickColumn($row, ['trang_thai', 'is_active', 'active']);

        return [
            'name' => is_string($name) ? trim($name) : $name,
            'email' => is_string($email) ? trim($email) : $email,
            'password' => is_string($password) ? trim($password) : '',
            'password_confirmation' => is_string($passwordConfirmation) ? trim($passwordConfirmation) : '',
            'role' => $role,
            'phone' => is_string($phone) ? trim($phone) : $phone,
            'department' => is_string($department) ? trim($department) : $department,
            'position' => is_string($position) ? trim($position) : $position,
            'is_active' => $this->parseBoolean($isActiveRaw),
        ];
    }

    private function pickColumn(array $row, array $candidates)
    {
        foreach ($candidates as $candidate) {
            $normalized = $this->normalizeColumnName($candidate);
            if (array_key_exists($normalized, $row) && $row[$normalized] !== null && $row[$normalized] !== '') {
                return $row[$normalized];
            }
        }

        return null;
    }

    private function normalizeColumnName(string $column): string
    {
        $column = trim(mb_strtolower($column));
        $column = str_replace([' ', '-', '.'], '_', $column);
        return preg_replace('/_+/', '_', $column);
    }

    private function normalizeRole(string $role): string
    {
        $role = trim(mb_strtolower($role));

        return match ($role) {
            'admin' => 'admin',
            'approver', 'phe_duyet', 'phê_duyệt', 'truong_ca', 'trưởng_ca' => 'approver',
            'tchc_checker', 'checker', 'tchc_kiem_tra' => 'tchc_checker',
            'tchc_manager', 'manager', 'tchc_quan_ly' => 'tchc_manager',
            'employee', 'nhan_vien', 'nhân_viên' => 'employee',
            default => 'employee',
        };
    }

    private function parseBoolean($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $normalized = trim(mb_strtolower((string) $value));
        if ($normalized === '') {
            return true;
        }

        return in_array($normalized, ['1', 'true', 'yes', 'y', 'active', 'hoat_dong', 'hoạt_động'], true);
    }
}
