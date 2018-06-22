<div class="row">
	<div class="col-6">
		<div class="module-headline">
			<h4><?php echo $sPageTitle; ?>
				<small class="module-tagline"><?php echo $sTagline; ?></small>
			</h4>
		</div>
	</div>
	<div class="col-6">
		<div class="module-headline">
			<div class="float-right">

					<div class="btn-group icwp-top-buttons" role="group">

					<a href="javascript:void(0)" class="btn btn-outline-success icwp-carousel-0">
						<?php echo $strings[ 'btn_options' ]; ?></a>

					<a class="btn btn-dark btn-icwp-wizard disabled" aria-disabled="true"
					   title="Launch Guided Walk-Through Wizards" href="javascript:void(0)">
						<?php echo $strings[ 'btn_wizards' ]; ?></a>

					<a href="javascript:void(0)" class="btn btn-outline-info icwp-carousel-2"><?php echo $strings[ 'btn_help' ]; ?></a>

					<?php if ( $flags[ 'show_content_actions' ] ) : ?>
						<a class="btn btn-outline-secondary icwp-carousel-3" href="javascript:void(0)">
							<?php echo $strings[ 'btn_actions' ]; ?></a>
					<?php else : ?>
						<a class="btn btn-outline-secondary disabled" href="javascript:void(0)">
							<?php echo $strings[ 'btn_actions' ]; ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row no-gutters content-wizards">
	<div class="col">
	<?php echo $content['wizard_landing']; ?>
	</div>
</div>