<?php
    global $wpdb;
    $query = "SELECT * FROM {$wpdb->prefix}bl_links";
    $results = $wpdb->get_results($query, ARRAY_A);
?>

<div class="wrap">
	<h1><?php echo get_admin_page_title() ?></h1><br>
	<table class="wp-list-table widefat fixed striped table-view-list pages">
		<thead>
			<th>URL</th>
			<th>Status</th>
			<th>Origin</th>
		</thead>
		<tbody id="the-list">
            <?php
            foreach ($results as $key => $value) { ?>
                <tr>
                    <td><?php echo $value['bl_url'] ?></td>
                    <td><?php echo $value['bl_status'] ?></td>
                    <td><?php echo $value['bl_origin'] ?></td>
                </tr>
            <?php
            } ?>
		</tbody>
	</table>
</div>