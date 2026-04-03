<!DOCTYPE html>
<html>
<head>
    <title>Test Import</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Test Import Debug</h3>
        
        <form id="testForm">
            <div class="mb-3">
                <label for="file">Choose File:</label>
                <input type="file" id="file" name="file" class="form-control" accept=".csv,.txt,.xlsx">
            </div>
            <button type="submit" class="btn btn-primary">Test Import</button>
        </form>
        
        <div id="result" class="mt-4"></div>
        
        <div class="mt-4">
            <h5>Test CSV File:</h5>
            <a href="/test_import.csv" target="_blank">Download Test File</a>
        </div>
    </div>

    <script>
        document.getElementById('testForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('file');
            const resultDiv = document.getElementById('result');
            
            if (!fileInput.files[0]) {
                alert('Please choose a file');
                return;
            }
            
            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            resultDiv.innerHTML = '<div class="alert alert-info">Processing...</div>';
            
            try {
                const response = await fetch('/test-import', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <h5>Success!</h5>
                            <p>${result.message}</p>
                            <pre>${JSON.stringify(result, null, 2)}</pre>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <h5>Error!</h5>
                            <p>${result.message}</p>
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <h5>Exception!</h5>
                        <p>${error.message}</p>
                    </div>
                `;
            }
        });
    </script>
</body>
</html><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\test-import.blade.php ENDPATH**/ ?>