<h1>Manage elections <small>Event: <?php echo $this->session->userdata('manage_event_event'); ?></small></h1>
<ul class="nav nav-pills nav-admin">
  <li class="active"><?php echo anchor('admin/elections', '<span class="glyphicon glyphicon-list"></span> List all'); ?></li>
  <li><?php echo anchor('admin/elections/add', '<span class="glyphicon glyphicon-plus"></span> Add new'); ?></li>
</ul>
<?php echo alert(validation_errors('&nbsp;', '<br />'), $this->session->flashdata('messages')); ?>
<table class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th class="text-center">#</th>
      <th>Election</th>
      <th>Admin</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($elections as $i => $election): ?>
    <tr>
      <td class="text-center"><?php echo $i + 1; ?></td>
      <td><?php echo $election['election']; ?></td>
      <td><?php echo $election['admin']; ?></td>
      <td>
        <?php echo anchor('admin/elections/manage/' . $election['id'], '<span class="glyphicon glyphicon-wrench"></span> Manage', 'class="btn btn-default"'); ?>
        <?php echo anchor('admin/elections/edit/' . $election['id'], '<span class="glyphicon glyphicon-pencil"></span> Edit', 'class="btn btn-default"'); ?>
        <?php echo anchor('admin/elections/delete/' . $election['id'], '<span class="glyphicon glyphicon-trash"></span> Delete', 'class="btn btn-danger"'); ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
