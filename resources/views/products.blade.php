<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Entry Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Enter Product Details</h2>

    <!-- Product entry form -->
    <form id="productForm" method="POST" action="/product-entry">
        @csrf <!-- CSRF protection token -->
        
        <!-- Product Name -->
        <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input 
                type="text" 
                name="productName" 
                id="productName" 
                class="form-control" 
                required
            >
        </div>
        
        <!-- Quantity -->
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity in Stock</label>
            <input 
                type="number" 
                name="quantity" 
                id="quantity" 
                class="form-control" 
                min="0" 
                required
            >
        </div>
        
        <!-- Price -->
        <div class="mb-3">
            <label for="price" class="form-label">Price per Item</label>
            <input 
                type="number" 
                step="0.01" 
                name="price" 
                id="price" 
                class="form-control" 
                min="0" 
                required
            >
        </div>
        
        <!-- Submit button -->
        <button type="submit" class="btn btn-primary">Submit Product</button>
    </form>

    <!-- Product list -->
    @if (!empty($products))
        <h3 class="mt-5">Saved Products</h3>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $p)
                    <tr>
                        <td>{{ $p['productName'] }}</td>
                        <td>{{ $p['quantity'] }}</td>
                        <td>${{ number_format($p['price'], 2) }}</td>
                        <td>{{ $p['submitted_at'] ?? 'N/A' }}</td>
                        <td>${{ number_format($p['quantity'] * $p['price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No products submitted yet.</p>
    @endif
</div>

<script>
// AJAX form submission handler
document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent normal form submit

    const formData = new FormData(this);

    fetch('/product-entry', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token for AJAX
        }
    })
    .then(response => {
        if (!response.ok) {
            // Extract validation errors if any
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        alert(data.message);  // Show success message
        this.reset();         // Clear form
        location.reload();    // Reload to update product list
    })
    .catch(error => {
        if (error.errors) {
            // Show validation errors
            alert(Object.values(error.errors).join('\n'));
        } else {
            alert('An unexpected error occurred.');
        }
    });
});
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

            

