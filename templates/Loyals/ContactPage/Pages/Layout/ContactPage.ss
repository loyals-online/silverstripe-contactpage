<section class="content">
    <div class="row">
        <div class="medium-8 <% if not $SiteBlocks %>small-centered<% end_if %> columns">
            <div class="panel">
                <% if $Success %>
                    $SubmitText
                <% else %>
                    $Content
                    $ContactForm
                <% end_if %>
            </div>
        </div>
    </div>
</section>