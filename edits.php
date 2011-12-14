<?php 
$admin=true;
$page = 'Edits';
require 'top.php'; ?>
	<h2>Edits</h2><?php 
	if($Auth->isModerator()){ ?>
	<table class="display" id="facilities">
		<thead>
		<tr>
			<th>Name</th>
			<th>County</th>
			<th>State</th>
			<th>Status</th>
			<th>Date</th>
			<th><img src="/images/view.png" alt="View" /></th>
			<th><div style="color:#991111;">&#10008;</div></th>
		</tr>
		</thead>
		<tbody><?php
		$edits = DBObject::glob('Edit',"SELECT * FROM facilities_edits WHERE facility_state IN (SELECT state FROM permissions WHERE user_id={$Auth->user->id}) ORDER BY status ASC,created DESC");
		foreach($edits as $facility){ ?>
			<tr id="edit-<?=$facility->id; ?>" class="<?=$facility->status;?>">
			<td><?=nonempty(array($facility->name,$facility->authority)); ?></td>
			<td><?=$facility->facility_county; ?></td>
			<td><?=$facility->facility_state; ?></td>
			<td><?=$facility->status;?></td>
			<td><?=date('m/d/y g:ia',strtotime($facility->created)); ?></td>
			<td><a href="edit.php?id=<?=$facility->id; ?>" target="_blank" ><img src="/images/view.png" alt="View" /></a></td>
			<td><button onclick="deleteEdit(<?=$facility->id;?>);"><span style="color:#991111;">&#10008;</span></button></td>
			</tr><?php
		} ?>
		</table><?php
	}
require 'bottom.php'; ?>