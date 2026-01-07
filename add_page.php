<?php
declare(strict_types=1);
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $description = $_POST['description'] ?? '';
    $content = $_POST['content'] ?? '';

    if ($title && $content) {
        $pageRepo->createPage($title, $slug, $description, $content);
        header("Location: index.php?page_created=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-dark text-white"><h2>Create Website Page</h2></div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Page Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">HTML Content</label>
                        <textarea name="content" class="form-control" rows="10" placeholder="Enter full HTML elements here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Publish Page</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>