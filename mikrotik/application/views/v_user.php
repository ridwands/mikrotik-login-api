<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
    <!--CSS JS LOAD-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
		integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
		integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
	</script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
		integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
	</script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
		integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
	</script>
	<style>
		body {}

		.content {
			max-width: 500px;
			margin: auto;
			background: white;
			padding: 50px;
		}
	</style>
</head>

<body>
	<div class="container-sm">
		<div class="content">
			<a href="<?php echo base_url()?>welcome/print"><button type="submit"
					class="btn btn-warning">Print</button></a>
					<a href="<?php echo base_url()?>welcome/logout"><button type="submit"
					class="btn btn-warning">Logout</button></a>
			<br>
            <?php 
            //FlashData From PHP
            echo $this->session->flashdata('msg'); 
            ?>
            <!--Load Data From USer DB-->
			<table class="table table-striped">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Email</th>
						<th scope="col">Password</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php if (empty($user)) { ?>
					<tr>
						<td colspan="8">Data Belum Ada</td>
					</tr>
					<?php } else {
                $no=1;
                foreach ($user as $user){
                    
               
                ?>
					<th scope="row"><?php echo $no++?></th>
					<td><?php echo $user->email?></td>
					<td><?php echo $user->pass?></td>
					<td>
						<a class="btn btn-sm btn-primary"
							href="<?php echo base_url() ?>welcome/edit/<?php echo $user->id ?>"><i
								class="fa fa-edit"></i> Edit</a>
						<a class="btn btn-sm btn-warning"
							href="<?php echo base_url() ?>welcome/delete/<?php echo $user->id?>"><i
								class="fa fa-edit"></i> Delete</a>
					</td>
					</tr>
					<?php } }?>
				</tbody>
			</table>

</body>

</html>
