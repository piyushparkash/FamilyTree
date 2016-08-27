<html>
    <body>
        <form name="sendtopage" method="post" action="{$sendtopage}">
            {foreach $data as $value}
                <input type="hidden" name="{$value@key}" value="{$value}" />
            {/foreach}
            <input type="hidden" name="sending" value="sending" />
        </form>
        <script type="text/javascript">
            document.sendtopage.submit();
        </script>
    </body>
</html>