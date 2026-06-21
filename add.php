<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Trucking Service Item</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Trucking Service Item</h1>

        <nav>
            <a href="index.php" class="btn secondary">Back to Item Master</a>
        </nav>

        <section class="add-item-full">
            <form action="add_item.php" method="POST">
                <div class="form-group">
                    <label for="get_in">Get In:</label>
                    <input type="text" id="get_in" name="get_in" required>
                </div>
                <div class="form-group">
                    <label for="get_out">Get Out:</label>
                    <input type="text" id="get_out" name="get_out" required>
                </div>
                <div class="form-group">
                    <label for="destination">Destination:</label>
                    <input type="text" id="destination" name="destination" required>
                </div>
                <div class="form-group">
                    <label for="container">Container:</label>
                    <input type="text" id="container" name="container" required>
                </div>
                <div class="form-group">
                    <label for="vendor">Vendor:</label>
                    <input type="text" id="vendor" name="vendor" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <button type="submit" class="btn primary">Save Item</button>
            </form>
        </section>
    </div>
</body>
</html>
