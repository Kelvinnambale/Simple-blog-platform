<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "blog_platform");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert a new post
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_post'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // Prepare and execute the insertion
    $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);
    if ($stmt->execute()) {
        $message = "Post added successfully!";
    } else {
        $error = "Error adding post: " . $stmt->error;
    }
    $stmt->close();
}

// Insert a new comment
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_comment'])) {
    $post_id = (int)$_POST['post_id'];
    $comment = $_POST['comment'];
    
    // Prepare and execute the insertion
    $stmt = $conn->prepare("INSERT INTO comments (post_id, comment) VALUES (?, ?)");
    $stmt->bind_param("is", $post_id, $comment);
    if ($stmt->execute()) {
        $comment_message = "Comment added successfully!";
    } else {
        $comment_error = "Error adding comment: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all posts
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: none;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .comments {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Blog Platform</h1>

        <?php if (isset($message)): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="content" placeholder="Content" required></textarea>
            <button type="submit" name="add_post">Add Post</button>
        </form>

        <h2>All Posts</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Created At</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['content']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <button onclick="document.getElementById('comment-form-<?= $row['id'] ?>').style.display='block';">Comment</button>
                            <div id="comment-form-<?= $row['id'] ?>" style="display:none;">
                                <form method="POST">
                                    <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                                    <textarea name="comment" placeholder="Add a comment" required></textarea>
                                    <button type="submit" name="add_comment">Add Comment</button>
                                </form>
                                <?php if (isset($comment_message)): ?>
                                    <div class="message success"><?= htmlspecialchars($comment_message) ?></div>
                                <?php endif; ?>
                                <?php if (isset($comment_error)): ?>
                                    <div class="message error"><?= htmlspecialchars($comment_error) ?></div>
                                <?php endif; ?>
                            </div>
                            <h4>Comments:</h4>
                            <div class="comments">
                                <?php
                                $comment_result = $conn->query("SELECT * FROM comments WHERE post_id = " . $row['id']);
                                while ($comment = $comment_result->fetch_assoc()):
                                ?>
                                    <p><?= htmlspecialchars($comment['comment']) ?> <small>(<?= $comment['created_at'] ?>)</small></p>
                                <?php endwhile; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>