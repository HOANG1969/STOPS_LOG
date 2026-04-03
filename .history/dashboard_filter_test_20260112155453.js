// Test filtering functionality console output
console.log('=== DASHBOARD FILTERING DEBUG ===');

// Test the filtering function
function testFilterByStatus() {
    console.log('Testing filterByStatus function...');
    
    // Mock data to test with
    const mockData = [
        { id: 1, status: 'pending', created_at: '2026-01-12', approved_at: null },
        { id: 2, status: 'approved', created_at: '2026-01-12', approved_at: '2026-01-12' },
        { id: 3, status: 'approved', created_at: '2026-01-01', approved_at: '2026-01-01' },
        { id: 4, status: 'rejected', created_at: '2026-01-10', approved_at: null }
    ];
    
    // Set global data
    allRequestsData = mockData;
    
    console.log('Mock data loaded:', allRequestsData);
    
    // Test different filters
    console.log('\n--- Testing pending filter ---');
    filterByStatus('pending');
    
    console.log('\n--- Testing approved_today filter ---');
    filterByStatus('approved_today');
    
    console.log('\n--- Testing all filter ---');
    filterByStatus('all');
}

// Add to the dashboard script section
console.log('Dashboard filtering test ready. Call testFilterByStatus() to test.');