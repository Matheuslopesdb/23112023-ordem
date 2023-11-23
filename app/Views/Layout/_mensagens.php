<?php if(session()->has('sucesso')): ?>


<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Tudo certo!</strong> <?php echo session('sucesso'); ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php endif; ?>



<?php if(session()->has('info')): ?>


<div class="alert alert-info alert-dismissible fade show" role="alert">
  <strong>Informacao!</strong> <?php echo session('info'); ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php endif; ?>



<?php if(session()->has('atencao')): ?>


<div class="alert alert-info alert-dismissible fade show" role="alert">
  <strong>Atencao!</strong> <?php echo session('atencao'); ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php endif; ?>


<!-- Utilizaremos quando fizermos um post sem ajax request -->
<?php if(session()->has('erros_model')): ?>

    <ul>

    <?php foreach($erros_model as $erro): ?>

        <li class="text-danger"><?php echo $erro; ?></li>

    <?php endforeach; ?>

    </ul>

<?php endif; ?>


<!-- Utilizamos quando o formulario � interceptado por erro no backend 
ou quando estamos fazendo um debug para ver o que esta vindo no post-->
<?php if(session()->has('error')): ?>


<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Error!</strong> <?php echo session('error'); ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php endif; ?>








