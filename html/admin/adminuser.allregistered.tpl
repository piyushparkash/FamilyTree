 {* display pagination header *}
    Member {$paginate.first}-{$paginate.last} out of {$paginate.total} displayed.

    {* display results *}    
    {section name=res loop=$results}
        {$results[res]}
    {/section}

    {* display pagination info *}
    {paginate_prev} {paginate_middle} {paginate_next}