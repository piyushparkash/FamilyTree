<div align="center" id="vanshavali_user">
    <div class="btn-group">
        <a class="btn btn-large">Vanshavali</a>
        {if $authenticated eq False}
            <a class="btn btn-large" onclick="login()">Login</a>
        {else}
            <script type="text/javascript">
                is_authenticated = true;
            </script>
            <a class="btn btn-large">
                {$membername}
            </a>
        {/if}
    </div>
</div>
