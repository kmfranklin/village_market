<?php
if (!isset($product)) {
  $product = new Product();
}
?>

<fieldset>
  <legend>Product Information</legend>
  <dl>
    <dt><label for="product_name">Product Name</label></dt>
    <dd><input type="text" name="product[product_name]" id="product_name" value="<?php echo h($product->product_name); ?>" required /></dd>

    <dt><label for="product_description">Description</label></dt>
    <dd>
      <textarea name="product[product_description]" id="product_description" required><?php echo h($product->product_description); ?></textarea>
    </dd>

    <dt><label for="category_id">Category</label></dt>
    <dd>
      <select name="product[category_id]" id="category_id" aria-describedby="category-help" required>
        <option value="">Select Category</option>
        <?php foreach (Product::get_categories() as $category) { ?>
          <option value="<?php echo h($category['category_id']); ?>"
            <?php if ($product->category_id == $category['category_id']) echo 'selected'; ?>>
            <?php echo h($category['category_name']); ?>
          </option>
        <?php } ?>
      </select>
      <small id="category-help">Select the category that best describes your product.</small>
    </dd>


    <dt><label for="price">Price</label></dt>
    <dd><input type="number" step="0.01" name="product[price]" id="price" value="<?php echo h($product->price ?? ''); ?>" required /></dd>

    <dt><label for="product_image">Product Image</label></dt>
    <dd>
      <input type="file" name="product_image" id="product_image" aria-describedby="image-help">
      <small id="image-help">Upload a clear image of your product. Only JPG, PNG formats are allowed.</small>
    </dd>

    <dt><label for="is_active">Product Availability</label></dt>
    <dd>
      <input type="checkbox" name="product[is_active]" id="is_active" value="1"
        <?php if ($product->is_active) echo 'checked'; ?>
        aria-labelledby="is-active-label">
      <label id="is-active-label" for="is_active">Mark as Available for Sale</label>
    </dd>
  </dl>
</fieldset>
