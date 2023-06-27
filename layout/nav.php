<?php
function nav($conn, $page, $state) {
    switch ($page) {
        case 'import_brand_series':
            $import_brand_series = 'active';
            $import_min_metadata = '';
            $import_bca_file = '';
        break;
        case 'import_min_metadata':
            $import_brand_series = '';
            $import_min_metadata = 'active';
            $import_bca_file = '';
        break;
        case 'import_bca_file':
            $import_brand_series = '';
            $import_min_metadata = '';
            $import_bca_file = 'active';
        break;
        default:
            $import_brand_series = '';
            $import_min_metadata = '';
            $import_bca_file = '';
    }

    if ($state == 'active') {
        $navigation = "
                        <ul class='v-menu'>
                            <li class='menu-title'>General</li>
                            <li><a href='../'><span class='mif-home icon'></span>Home</a></li>
                            <li class='menu-title'>Import</li>
                            <li class='$import_brand_series'><a href='./import.php?ref=brand_series'><span class='mif-cloud-upload icon'></span>Brand Series</a></li>
                            <li class='$import_min_metadata'><a href='./import.php?ref=min_metadata'><span class='mif-cloud-upload icon'></span>Min Metadata</a></li>
                            <li class='$import_bca_file'><a href='./import.php?ref=bca_file'><span class='mif-cloud-upload icon'></span>BCA File</a></li>
                        </ul>
                        ";
    } else {
        $navigation = "
                        <ul class='v-menu'>
                            <li class='menu-title'>General</li>
                            <li><a href='../'><span class='mif-home icon'></span>Home</a></li>
                        </ul>
                        ";
    }

    return $navigation;
}
?>