<?php
function truncateHtml($text, $length)
{
    if (strlen($text) <= $length) {
        return $text;
    }

    $doc = new DOMDocument();
    $doc->loadHTML('<?xml encoding="utf-8" ?>' . $text, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $body = $doc->getElementsByTagName('body')->item(0);
    if (!$body) {
        return substr($text, 0, $length) . '...';
    }

    $totalLength = 0;
    $output = '';

    foreach ($body->childNodes as $node) {
        if ($totalLength >= $length) {
            break;
        }

        $nodeTextLength = strlen($doc->saveHTML($node));
        if ($totalLength + $nodeTextLength > $length) {
            $remainingLength = $length - $totalLength;
            $nodeContent = $doc->saveHTML($node);

            // Check if node is a text node or an element node
            if ($node->nodeType == XML_TEXT_NODE) {
                $output .= substr($nodeContent, 0, $remainingLength);
            } else {
                $output .= substr($nodeContent, 0, $remainingLength) . '...';
            }

            break;
        } else {
            $output .= $doc->saveHTML($node);
            $totalLength += $nodeTextLength;
        }
    }

    return $output . (strlen($text) > $length ? '...' : '');
}
include('includes/header.php');
include('includes/navbar.php');

if (isset($_GET['action']) && $_GET['action'] == 'del') {
    if (isset($_GET['id'])) {
        $postid = intval($_GET['id']);
        $query = deactivate_post_by_id($postid);
        if ($query) {
            $msg = "Post deleted ";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    } else {
        $error = "Post ID is missing.";
    }
}

$total_updates = get_total_updates();
?>
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">REGISTERED USERS</h6>
            <a href="">Show All</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Date Posted</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($total_updates as $update) : ?>
                        <tr>
                            <td><?php echo $update['update_title']; ?></td>
                            <td><?php echo truncateHtml($update['update_desc'], 50); ?></td>
                            <td><?php echo date('F j, Y', strtotime($update['update_date'])); ?></td>
                            <td class="text-center"><a href="edit-post.php?id=<?php echo $update['update_num']; ?>" 
                            class="btn btn-sm btn-primary" href="">EDIT</a> 
                            &nbsp;
                            <a href="manage-update.php?id=<?php echo $update['update_num']; ?>&&action=del" 
                            class="btn btn-sm btn-danger" onclick="return confirm('Do you reaaly want to delete ?')">DELETE</a> 
                            </td>
                        </tr><?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
include('includes/footer.php');
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>