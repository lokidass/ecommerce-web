<div class="windows-sub-sidebar">
    <ul class="sidebar-list">
        <li>
            <a href="#imagesSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="bi bi-image"></i> Manage Images
            </a>
            <ul class="collapse list-unstyled" id="imagesSubmenu">
                <li><a href="add_image.php">Add Image</a></li>
                <li><a href="edit_images.php">Edit Images</a></li>
            </ul>
        </li>
        <li>
            <a href="#textsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="bi bi-fonts"></i> Manage Texts
            </a>
            <ul class="collapse list-unstyled" id="textsSubmenu">
                <li><a href="add_text.php">Add Text</a></li>
                <li><a href="edit_texts.php">Edit Texts</a></li>
            </ul>
        </li>
        <li>
            <a href="#infoSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="bi bi-info-circle"></i> Edit Information
            </a>
            <ul class="collapse list-unstyled" id="infoSubmenu">
                <li><a href="edit_info.php">Update Info</a></li>
            </ul>
        </li>
    </ul>
</div>

<style>
    .windows-sub-sidebar {
        width: 250px;
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        font-family: 'Sofia', sans-serif;
    }
    
    .sidebar-list {
        list-style: none;
        padding: 0;
    }

    .sidebar-list li {
        margin: 10px 0;
    }

    .sidebar-list li a {
        text-decoration: none;
        font-size: 16px;
        color: #333;
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .sidebar-list li a:hover {
        background-color: #ddd;
    }

    .sidebar-list li a i {
        font-size: 20px;
        margin-right: 10px;
    }

    .collapse {
        background-color: #f1f1f1;
        margin-left: 15px;
    }

    .collapse li a {
        font-size: 14px;
        color: #555;
    }

    .collapse li a:hover {
        color: #000;
    }
</style>
