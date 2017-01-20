<div class="container well">
    {if $error eq 1}
        {include file="error_low.tpl"}
    {/if}
    <h4>Please update callback URL of the WP Rest Client in Wordpress and then press Done</h4>
    <div class="alert alert-success alert-block">
        {$callback}
    </div>
    <a href="index.php?mode=check_wp_login&sub=2" class="btn btn-primary">Done</a>
</div>