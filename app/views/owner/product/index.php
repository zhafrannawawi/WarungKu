<h1>Product List</h1>

<?php if (empty($product_list)): ?>
    <p>No products found.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($product_list as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['ProductId'] ?? $product['id'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($product['ProductName'] ?? $product['name'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
