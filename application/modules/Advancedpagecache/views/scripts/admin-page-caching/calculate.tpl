<style>
#global_content_simple {
	display: block;
}
</style>
<?php if(!$this->withoutapc_load_time && !$this->withoutapc_load_time) :?>

<h3>This URL is not reachable.Please check it once and provide a valid url.</h3>
<?php else: ?>
<?php if($this->withoutapc_load_time):?>
<div class="advancepagecache_caching_estimation advancepagecache_with_caching">
  <h4>Without Cache</h4>
  <p>Response Time</p>
  <div class="advancepagecache_load_time"> <?php echo $this->withoutapc_load_time?> <em>second(s)</em> </div>
</div>
<?php endif; ?>
<?php if($this->withoutapc_load_time):?>
<div class="advancepagecache_caching_estimation advancepagecache_without_caching">
  <h4>With Cache</h4>
  <p>Response Time</p>
  <div class="advancepagecache_load_time"> <?php echo $this->apc_load_time?> <em>second(s)</em> </div>
</div>
<?php endif; ?>
<?php endif;?>
