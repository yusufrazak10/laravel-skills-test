<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Entry Form</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Enter Product Details</h2>
    <!-- Product entry form -->
    <form id="productForm" method="POST" action="/product-entry">
        <!-- Product Name input -->
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
        <!-- Quantity in Stock input -->
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
        <!-- Price per Item input -->
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
</div>

<script>
// Handle form submission
document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Stop default form action

    // Get form data
    const formData = new FormData(this);

    // Send AJAX request
    fetch('/product-entry', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        // Handle non-OK response
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    
    // Handle success
    .then(data => {
        alert(data.message);
        this.reset(); // Clear form
    })
    
    // Handle errors
    .catch(error => {
        console.error('Error:', error);
        if (error.errors) {
            alert(Object.values(error.errors).join('\n'));
        }
    });
});
</script>


<!-- Bootstrap JS bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php


    

