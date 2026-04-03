

<?php $__env->startSection('title', 'Test API'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Test API Functions</h1>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>API Tests</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" onclick="testLoadSupplies()">Test Load Office Supplies</button>
                        <button type="button" class="btn btn-secondary" onclick="testSubmitRequest()">Test Submit Request</button>
                    </div>
                    
                    <div id="test-results" class="mt-4">
                        <!-- Results will appear here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function logResult(message, data = null) {
    const timestamp = new Date().toLocaleTimeString();
    let html = `<div class="alert alert-info">[${timestamp}] ${message}`;
    if (data) {
        html += `<pre class="mt-2 mb-0">${JSON.stringify(data, null, 2)}</pre>`;
    }
    html += '</div>';
    $('#test-results').prepend(html);
}

function testLoadSupplies() {
    logResult('Testing office supplies API...');
    
    $.ajax({
        url: '<?php echo e(route("office-supplies.api.for-request")); ?>',
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(supplies) {
            logResult(`✅ Success! Loaded ${supplies.length} office supplies`, supplies.slice(0, 3));
        },
        error: function(xhr, status, error) {
            logResult(`❌ Error loading supplies`, {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error
            });
        }
    });
}

function testSubmitRequest() {
    logResult('Testing submit request API...');
    
    const testData = {
        items: [
            {
                supply_id: 1,
                quantity: 2,
                purpose: 'Test purpose'
            }
        ],
        priority: 'normal',
        notes: 'Test notes',
        needed_date: '<?php echo e(date("Y-m-d", strtotime("+7 days"))); ?>',
        status: 'draft',
        _token: $('meta[name="csrf-token"]').attr('content')
    };
    
    $.ajax({
        url: '<?php echo e(route("supply-requests.store")); ?>',
        method: 'POST',
        data: testData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            logResult('✅ Submit request success!', response);
        },
        error: function(xhr, status, error) {
            logResult('❌ Submit request failed', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error
            });
        }
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\test-api.blade.php ENDPATH**/ ?>